# Panduan Keamanan Website Pengumuman Kelulusan

## Overview
Sistem keamanan yang komprehensif telah diimplementasikan untuk melindungi website pengumuman kelulusan dari berbagai ancaman cyber. Sistem ini mencakup multiple layers of security untuk memastikan integritas, kerahasiaan, dan ketersediaan data.

## Fitur Keamanan yang Diimplementasikan

### 1. Security Headers
**File**: `app/Http/Middleware/SecurityHeaders.php`

- **Content Security Policy (CSP)**: Mencegah XSS attacks
- **X-Frame-Options**: Mencegah clickjacking
- **X-Content-Type-Options**: Mencegah MIME type sniffing
- **X-XSS-Protection**: Browser XSS protection
- **Strict-Transport-Security (HSTS)**: Memaksa HTTPS
- **Referrer-Policy**: Kontrol informasi referrer

### 2. Rate Limiting
**File**: `app/Http/Middleware/RateLimitMiddleware.php`

- **Public Routes**: 30 requests per minute
- **Admin Routes**: 120 requests per minute
- **Login Routes**: 5 attempts per 15 minutes
- **Automatic IP blocking** untuk excessive requests

### 3. Input Sanitization & Validation
**File**: `app/Http/Middleware/InputSanitization.php`

- **SQL Injection Detection**: Pattern matching untuk SQL injection
- **XSS Prevention**: Deteksi script injection
- **Directory Traversal Protection**: Mencegah path traversal
- **Command Injection Prevention**: Deteksi command injection
- **Input Sanitization**: Pembersihan input otomatis

### 4. Admin Access Control
**File**: `app/Http/Middleware/AdminAccessControl.php`

- **Authentication Verification**: Memastikan user terautentikasi
- **Role-based Access**: Kontrol akses berdasarkan role
- **Activity Monitoring**: Monitoring aktivitas admin
- **Suspicious Activity Detection**: Deteksi aktivitas mencurigakan

### 5. Login Attempt Monitoring
**File**: `app/Http/Middleware/LoginAttemptLogger.php`

- **Brute Force Protection**: Deteksi serangan brute force
- **Failed Login Tracking**: Tracking percobaan login gagal
- **IP-based Blocking**: Blocking berdasarkan IP address
- **Email-based Blocking**: Blocking berdasarkan email

## Services Keamanan

### 1. Audit Log Service
**File**: `app/Services/AuditLogService.php`

**Fungsi**:
- Log semua aktivitas admin
- Track perubahan data siswa
- Monitor operasi file
- Record authentication events

**Methods**:
```php
AuditLogService::log($action, $description, $data);
AuditLogService::logStudentChange($action, $student, $changes);
AuditLogService::logSettingsChange($setting, $oldValue, $newValue);
AuditLogService::logFileOperation($action, $filename, $path);
AuditLogService::logAuth($event, $email, $data);
```

### 2. Security Monitoring Service
**File**: `app/Services/SecurityMonitoringService.php`

**Fungsi**:
- Monitor security events
- Detect suspicious activities
- Track failed login attempts
- Generate security reports

**Methods**:
```php
SecurityMonitoringService::logSecurityEvent($type, $description, $severity);
SecurityMonitoringService::checkSuspiciousIP($ip);
SecurityMonitoringService::detectSQLInjection($request);
SecurityMonitoringService::detectXSS($request);
SecurityMonitoringService::getSecurityStats($days);
```

## Database Security

### 1. Audit Logs Table
**Migration**: `database/migrations/2024_01_01_000001_create_audit_logs_table.php`

**Columns**:
- user_id, user_email
- action, description
- ip_address, user_agent
- url, method
- data (JSON)
- timestamps

### 2. Security Logs Table
**Migration**: `database/migrations/2024_01_01_000002_create_security_logs_table.php`

**Columns**:
- event_type, severity
- ip_address, user_agent
- email, description
- metadata (JSON)
- is_blocked, occurred_at

## Monitoring & Reporting

### 1. Security Dashboard
**Route**: `/admin/security`
**Controller**: `app/Http/Controllers/Admin/SecurityController.php`

**Features**:
- Real-time security statistics
- Recent security events
- Admin activity logs
- Security event charts

### 2. Security Commands
**Files**:
- `app/Console/Commands/CleanSecurityLogs.php`
- `app/Console/Commands/SecurityReport.php`

**Usage**:
```bash
# Generate security report
php artisan security:report --days=7

# Clean old logs
php artisan security:clean-logs --force
```

## Configuration

### 1. Security Config
**File**: `config/security.php`

**Sections**:
- Rate limiting settings
- Security headers configuration
- Input validation rules
- Audit logging settings
- Security monitoring options
- Session security
- Password requirements

### 2. Logging Config
**File**: `config/logging.php`

**New Channels**:
- `security`: Daily security logs
- `audit`: Daily audit logs

## Environment Variables

Tambahkan ke `.env`:

```env
# Rate Limiting
RATE_LIMIT_PUBLIC=30
RATE_LIMIT_ADMIN=120
RATE_LIMIT_LOGIN=5

# Security Headers
CSP_ENABLED=true
HSTS_ENABLED=true

# Security Monitoring
SECURITY_MONITORING_ENABLED=true
MAX_FAILED_LOGINS=5
MAX_FAILED_LOGINS_PER_IP=10

# Audit Logging
AUDIT_LOGGING_ENABLED=true
AUDIT_LOG_RETENTION_DAYS=180
SECURITY_LOG_RETENTION_DAYS=90

# Session Security
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
SESSION_TIMEOUT_MINUTES=120

# Password Security
PASSWORD_MIN_LENGTH=8
PASSWORD_REQUIRE_UPPERCASE=true
PASSWORD_REQUIRE_NUMBERS=true
PASSWORD_REQUIRE_SYMBOLS=true
```

## Middleware Registration

**File**: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    // Global middleware
    $middleware->append([
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\InputSanitization::class,
    ]);
    
    // Route-specific middleware aliases
    $middleware->alias([
        'admin.access' => \App\Http\Middleware\AdminAccessControl::class,
        'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
        'login.logger' => \App\Http\Middleware\LoginAttemptLogger::class,
    ]);
})
```

## Routes Security

**File**: `routes/web.php`

```php
// Public routes with rate limiting
Route::middleware(['rate.limit:30,1'])->group(function () {
    // Public routes
});

// Admin routes with security middleware
Route::middleware(['auth', 'admin.access', 'rate.limit:120,1'])
    ->prefix('admin')->name('admin.')->group(function () {
    // Admin routes
});
```

## Best Practices

### 1. Regular Security Audits
- Review security logs weekly
- Monitor failed login attempts
- Check for suspicious IP activities
- Analyze security event patterns

### 2. Log Management
- Clean old logs regularly using artisan commands
- Monitor log file sizes
- Backup security logs before cleaning
- Set up log rotation

### 3. Incident Response
- Investigate critical security events immediately
- Block suspicious IPs when necessary
- Update security rules based on new threats
- Document security incidents

### 4. Maintenance
- Update security configurations regularly
- Review and update CSP policies
- Monitor for new vulnerability patterns
- Keep security middleware updated

## Security Checklist

- [ ] Security headers properly configured
- [ ] Rate limiting active on all routes
- [ ] Input sanitization working
- [ ] Audit logging enabled
- [ ] Security monitoring active
- [ ] Failed login protection working
- [ ] Admin access control implemented
- [ ] Security dashboard accessible
- [ ] Log cleanup scheduled
- [ ] Environment variables configured

## Troubleshooting

### Common Issues

1. **CSP Violations**: Update CSP directives in SecurityHeaders middleware
2. **Rate Limit Errors**: Adjust rate limits in security config
3. **False Positives**: Review and update threat detection patterns
4. **Log Storage Issues**: Implement log rotation and cleanup

### Monitoring Commands

```bash
# Check recent security events
php artisan security:report --days=1

# View security logs
tail -f storage/logs/security-*.log

# View audit logs
tail -f storage/logs/audit-*.log

# Clean old logs
php artisan security:clean-logs
```

## Future Enhancements

1. **Two-Factor Authentication**: Implement 2FA for admin accounts
2. **IP Geolocation**: Add location tracking for security events
3. **Real-time Alerts**: Email/SMS notifications for critical events
4. **Machine Learning**: AI-based threat detection
5. **Security Scanning**: Automated vulnerability scanning
6. **Backup Encryption**: Encrypted backup system
