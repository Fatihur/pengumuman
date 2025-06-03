<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Services\SecurityMonitoringService;
use App\Services\AuditLogService;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test security headers are applied
     */
    public function test_security_headers_are_applied()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeaderMissing('Server');
        $response->assertHeaderMissing('X-Powered-By');
    }

    /**
     * Test CSP header is present
     */
    public function test_csp_header_is_present()
    {
        $response = $this->get('/');
        
        $response->assertHeader('Content-Security-Policy');
        
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContains("default-src 'self'", $csp);
        $this->assertStringContains("frame-src 'none'", $csp);
        $this->assertStringContains("object-src 'none'", $csp);
    }

    /**
     * Test rate limiting works
     */
    public function test_rate_limiting_blocks_excessive_requests()
    {
        // Make requests up to the limit
        for ($i = 0; $i < 30; $i++) {
            $response = $this->get('/');
            $response->assertStatus(200);
        }
        
        // Next request should be rate limited
        $response = $this->get('/');
        $response->assertStatus(429);
    }

    /**
     * Test SQL injection detection
     */
    public function test_sql_injection_detection()
    {
        $maliciousInput = [
            'search' => "'; DROP TABLE students; --",
            'name' => "admin' OR '1'='1"
        ];
        
        $response = $this->post('/check-graduation', $maliciousInput);
        
        // Should log security event
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'sql_injection_attempt',
            'severity' => 'critical'
        ]);
    }

    /**
     * Test XSS detection
     */
    public function test_xss_detection()
    {
        $maliciousInput = [
            'search' => '<script>alert("XSS")</script>',
            'name' => 'javascript:alert(1)'
        ];
        
        $response = $this->post('/check-graduation', $maliciousInput);
        
        // Should log security event
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'xss_attempt',
            'severity' => 'high'
        ]);
    }

    /**
     * Test directory traversal detection
     */
    public function test_directory_traversal_detection()
    {
        $maliciousInput = [
            'file' => '../../../etc/passwd',
            'path' => '..\\..\\windows\\system32'
        ];
        
        $response = $this->post('/check-graduation', $maliciousInput);
        
        // Should log security event
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'directory_traversal_attempt',
            'severity' => 'high'
        ]);
    }

    /**
     * Test input sanitization
     */
    public function test_input_sanitization()
    {
        $input = [
            'name' => "Test\x00Name\x1F",
            'description' => "  Trimmed  \n\r\t  "
        ];
        
        $response = $this->post('/check-graduation', $input);
        
        // Input should be sanitized (null bytes removed, trimmed)
        $this->assertEquals('TestName', request()->input('name'));
        $this->assertEquals('Trimmed', request()->input('description'));
    }

    /**
     * Test audit logging service
     */
    public function test_audit_logging_service()
    {
        AuditLogService::log(
            'test.action',
            'Test audit log entry',
            ['test_data' => 'value']
        );
        
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'test.action',
            'description' => 'Test audit log entry'
        ]);
    }

    /**
     * Test security monitoring service
     */
    public function test_security_monitoring_service()
    {
        SecurityMonitoringService::logSecurityEvent(
            'test_event',
            'Test security event',
            'medium',
            ['test' => 'data']
        );
        
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'test_event',
            'description' => 'Test security event',
            'severity' => 'medium'
        ]);
    }

    /**
     * Test failed login monitoring
     */
    public function test_failed_login_monitoring()
    {
        $email = 'test@example.com';
        $ip = '192.168.1.1';
        
        // Simulate multiple failed logins
        for ($i = 0; $i < 4; $i++) {
            SecurityMonitoringService::logSecurityEvent(
                'failed_login',
                "Failed login attempt for {$email}",
                'medium',
                ['email' => $email, 'ip' => $ip]
            );
        }
        
        SecurityMonitoringService::monitorFailedLogins($email, $ip);
        
        // Should log brute force attempt
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'brute_force_email',
            'severity' => 'high'
        ]);
    }

    /**
     * Test suspicious IP detection
     */
    public function test_suspicious_ip_detection()
    {
        $ip = '192.168.1.100';
        
        // Create multiple security events from same IP
        for ($i = 0; $i < 12; $i++) {
            DB::table('security_logs')->insert([
                'event_type' => 'test_event',
                'severity' => 'medium',
                'ip_address' => $ip,
                'description' => 'Test event',
                'occurred_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $isSuspicious = SecurityMonitoringService::checkSuspiciousIP($ip);
        
        $this->assertTrue($isSuspicious);
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'suspicious_ip',
            'ip_address' => $ip
        ]);
    }

    /**
     * Test security statistics
     */
    public function test_security_statistics()
    {
        // Create test security events
        SecurityMonitoringService::logSecurityEvent('test_event_1', 'Test 1', 'critical');
        SecurityMonitoringService::logSecurityEvent('test_event_2', 'Test 2', 'high');
        SecurityMonitoringService::logSecurityEvent('test_event_1', 'Test 3', 'medium');
        
        $stats = SecurityMonitoringService::getSecurityStats(7);
        
        $this->assertEquals(3, $stats['total_events']);
        $this->assertEquals(1, $stats['critical_events']);
        $this->assertGreaterThan(0, count($stats['top_event_types']));
    }

    /**
     * Test log cleanup
     */
    public function test_log_cleanup()
    {
        // Create old audit log
        DB::table('audit_logs')->insert([
            'action' => 'old.action',
            'description' => 'Old audit log',
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMonths(7),
            'updated_at' => now()->subMonths(7)
        ]);
        
        // Create old security log
        DB::table('security_logs')->insert([
            'event_type' => 'old_event',
            'severity' => 'low',
            'ip_address' => '127.0.0.1',
            'description' => 'Old security log',
            'occurred_at' => now()->subMonths(4),
            'created_at' => now()->subMonths(4),
            'updated_at' => now()->subMonths(4)
        ]);
        
        $auditDeleted = AuditLogService::cleanOldLogs();
        $securityDeleted = SecurityMonitoringService::cleanOldLogs();
        
        $this->assertEquals(1, $auditDeleted);
        $this->assertEquals(1, $securityDeleted);
    }

    /**
     * Test admin access control middleware
     */
    public function test_admin_access_control_redirects_unauthenticated()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test security dashboard access
     */
    public function test_security_dashboard_requires_authentication()
    {
        $response = $this->get('/admin/security');
        
        $response->assertRedirect('/login');
    }
}
