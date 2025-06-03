<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class SecurityMonitoringService
{
    /**
     * Log security event
     */
    public static function logSecurityEvent(
        string $eventType,
        string $description,
        string $severity = 'medium',
        array $metadata = [],
        bool $isBlocked = false,
        ?string $email = null
    ): void {
        try {
            $request = request();
            
            DB::table('security_logs')->insert([
                'event_type' => $eventType,
                'severity' => $severity,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $email,
                'description' => $description,
                'metadata' => json_encode($metadata),
                'is_blocked' => $isBlocked,
                'occurred_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Log to Laravel log based on severity
            match($severity) {
                'critical' => Log::critical($description, $metadata),
                'high' => Log::error($description, $metadata),
                'medium' => Log::warning($description, $metadata),
                'low' => Log::info($description, $metadata),
                default => Log::info($description, $metadata)
            };
            
        } catch (\Exception $e) {
            Log::error('Failed to log security event', [
                'error' => $e->getMessage(),
                'event_type' => $eventType,
                'description' => $description
            ]);
        }
    }

    /**
     * Check for suspicious IP activity
     */
    public static function checkSuspiciousIP(string $ip): bool
    {
        $cacheKey = "suspicious_ip_{$ip}";
        
        if (Cache::has($cacheKey)) {
            return true;
        }
        
        // Check recent security events from this IP
        $recentEvents = DB::table('security_logs')
            ->where('ip_address', $ip)
            ->where('occurred_at', '>', now()->subHour())
            ->count();
        
        // Mark as suspicious if more than 10 security events in 1 hour
        if ($recentEvents > 10) {
            Cache::put($cacheKey, true, now()->addHours(24));
            
            self::logSecurityEvent(
                'suspicious_ip',
                "IP marked as suspicious due to high activity: {$ip}",
                'high',
                ['ip' => $ip, 'event_count' => $recentEvents]
            );
            
            return true;
        }
        
        return false;
    }

    /**
     * Monitor failed login attempts
     */
    public static function monitorFailedLogins(string $email, string $ip): void
    {
        // Count failed attempts in last 15 minutes
        $failedAttempts = DB::table('security_logs')
            ->where('event_type', 'failed_login')
            ->where('email', $email)
            ->where('occurred_at', '>', now()->subMinutes(15))
            ->count();
        
        $ipAttempts = DB::table('security_logs')
            ->where('event_type', 'failed_login')
            ->where('ip_address', $ip)
            ->where('occurred_at', '>', now()->subMinutes(15))
            ->count();
        
        // Log if suspicious pattern detected
        if ($failedAttempts >= 3) {
            self::logSecurityEvent(
                'brute_force_email',
                "Multiple failed login attempts for email: {$email}",
                'high',
                ['email' => $email, 'attempt_count' => $failedAttempts, 'ip' => $ip]
            );
        }
        
        if ($ipAttempts >= 5) {
            self::logSecurityEvent(
                'brute_force_ip',
                "Multiple failed login attempts from IP: {$ip}",
                'high',
                ['ip' => $ip, 'attempt_count' => $ipAttempts]
            );
        }
    }

    /**
     * Detect SQL injection attempts
     */
    public static function detectSQLInjection(Request $request): bool
    {
        $input = json_encode($request->all());
        
        $sqlPatterns = [
            '/(\bunion\b.*\bselect\b)|(\bselect\b.*\bunion\b)/i',
            '/(\bor\b\s+\d+\s*=\s*\d+)|(\band\b\s+\d+\s*=\s*\d+)/i',
            '/(\bdrop\b\s+\btable\b)|(\bdelete\b\s+\bfrom\b)/i',
            '/(\binsert\b\s+\binto\b)|(\bupdate\b.*\bset\b)/i',
            '/(\bexec\b|\bexecute\b).*\(/i',
            '/\b(sp_|xp_)\w+/i'
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                self::logSecurityEvent(
                    'sql_injection_attempt',
                    'Possible SQL injection attempt detected',
                    'critical',
                    [
                        'pattern' => $pattern,
                        'input' => substr($input, 0, 500),
                        'url' => $request->fullUrl(),
                        'method' => $request->method()
                    ],
                    true
                );
                return true;
            }
        }
        
        return false;
    }

    /**
     * Detect XSS attempts
     */
    public static function detectXSS(Request $request): bool
    {
        $input = json_encode($request->all());
        
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/eval\s*\(/i',
            '/expression\s*\(/i'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                self::logSecurityEvent(
                    'xss_attempt',
                    'Possible XSS attempt detected',
                    'high',
                    [
                        'pattern' => $pattern,
                        'input' => substr($input, 0, 500),
                        'url' => $request->fullUrl(),
                        'method' => $request->method()
                    ],
                    true
                );
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check for directory traversal attempts
     */
    public static function detectDirectoryTraversal(Request $request): bool
    {
        $input = json_encode($request->all());
        $url = $request->fullUrl();
        
        $traversalPatterns = [
            '/\.\.\//',
            '/\.\.\\\\/',
            '/%2e%2e%2f/',
            '/%2e%2e\\\\/',
            '/\.\.\%2f/',
            '/\.\.\%5c/'
        ];
        
        foreach ($traversalPatterns as $pattern) {
            if (preg_match($pattern, $input) || preg_match($pattern, $url)) {
                self::logSecurityEvent(
                    'directory_traversal_attempt',
                    'Possible directory traversal attempt detected',
                    'high',
                    [
                        'pattern' => $pattern,
                        'input' => substr($input, 0, 500),
                        'url' => $url,
                        'method' => $request->method()
                    ],
                    true
                );
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get security statistics
     */
    public static function getSecurityStats(int $days = 7): array
    {
        $startDate = now()->subDays($days);
        
        return [
            'total_events' => DB::table('security_logs')
                ->where('occurred_at', '>', $startDate)
                ->count(),
            
            'critical_events' => DB::table('security_logs')
                ->where('occurred_at', '>', $startDate)
                ->where('severity', 'critical')
                ->count(),
            
            'blocked_attempts' => DB::table('security_logs')
                ->where('occurred_at', '>', $startDate)
                ->where('is_blocked', true)
                ->count(),
            
            'unique_ips' => DB::table('security_logs')
                ->where('occurred_at', '>', $startDate)
                ->distinct('ip_address')
                ->count(),
            
            'top_event_types' => DB::table('security_logs')
                ->select('event_type', DB::raw('count(*) as count'))
                ->where('occurred_at', '>', $startDate)
                ->groupBy('event_type')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
                ->toArray()
        ];
    }

    /**
     * Get recent security events
     */
    public static function getRecentEvents(int $limit = 50): array
    {
        return DB::table('security_logs')
            ->orderBy('occurred_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clean old security logs (keep only last 3 months)
     */
    public static function cleanOldLogs(): int
    {
        $cutoffDate = now()->subMonths(3);
        
        return DB::table('security_logs')
            ->where('occurred_at', '<', $cutoffDate)
            ->delete();
    }
}
