<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | graduation announcement system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for different parts of the application.
    |
    */
    'rate_limiting' => [
        'public' => [
            'max_attempts' => env('RATE_LIMIT_PUBLIC', 30),
            'decay_minutes' => env('RATE_LIMIT_PUBLIC_DECAY', 1),
        ],
        'admin' => [
            'max_attempts' => env('RATE_LIMIT_ADMIN', 120),
            'decay_minutes' => env('RATE_LIMIT_ADMIN_DECAY', 1),
        ],
        'login' => [
            'max_attempts' => env('RATE_LIMIT_LOGIN', 5),
            'decay_minutes' => env('RATE_LIMIT_LOGIN_DECAY', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure security headers that will be sent with every response.
    |
    */
    'headers' => [
        'csp' => [
            'enabled' => env('CSP_ENABLED', true),
            'report_only' => env('CSP_REPORT_ONLY', false),
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
                'style-src' => "'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com",
                'font-src' => "'self' https://fonts.gstatic.com",
                'img-src' => "'self' data: blob: https:",
                'connect-src' => "'self'",
                'frame-src' => "'none'",
                'object-src' => "'none'",
                'base-uri' => "'self'",
                'form-action' => "'self'",
                'frame-ancestors' => "'none'",
                'upgrade-insecure-requests' => true,
            ],
        ],
        'hsts' => [
            'enabled' => env('HSTS_ENABLED', true),
            'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
            'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
            'preload' => env('HSTS_PRELOAD', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation & Sanitization
    |--------------------------------------------------------------------------
    |
    | Configure input validation and sanitization rules.
    |
    */
    'input_validation' => [
        'max_input_length' => env('MAX_INPUT_LENGTH', 10000),
        'allowed_file_types' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
        ],
        'max_file_size' => env('MAX_FILE_SIZE', 2048), // KB
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Configure audit logging settings.
    |
    */
    'audit_logging' => [
        'enabled' => env('AUDIT_LOGGING_ENABLED', true),
        'retention_days' => env('AUDIT_LOG_RETENTION_DAYS', 180), // 6 months
        'log_channels' => ['audit', 'database'],
        'events' => [
            'login' => true,
            'logout' => true,
            'student_create' => true,
            'student_update' => true,
            'student_delete' => true,
            'settings_update' => true,
            'bulk_operations' => true,
            'file_operations' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Monitoring
    |--------------------------------------------------------------------------
    |
    | Configure security monitoring and threat detection.
    |
    */
    'monitoring' => [
        'enabled' => env('SECURITY_MONITORING_ENABLED', true),
        'retention_days' => env('SECURITY_LOG_RETENTION_DAYS', 90), // 3 months
        'bot_detection_enabled' => env('BOT_DETECTION_ENABLED', false), // Disabled by default
        'suspicious_activity' => [
            'max_requests_per_minute' => env('MAX_REQUESTS_PER_MINUTE', 60),
            'max_failed_logins' => env('MAX_FAILED_LOGINS', 5),
            'max_failed_logins_per_ip' => env('MAX_FAILED_LOGINS_PER_IP', 10),
            'block_duration_minutes' => env('BLOCK_DURATION_MINUTES', 60),
        ],
        'threat_detection' => [
            'sql_injection' => true,
            'xss_attempts' => true,
            'directory_traversal' => true,
            'command_injection' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configure session security settings.
    |
    */
    'session' => [
        'secure_cookies' => env('SESSION_SECURE_COOKIES', true),
        'http_only' => env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'strict'),
        'regenerate_on_login' => true,
        'timeout_minutes' => env('SESSION_TIMEOUT_MINUTES', 120), // 2 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    |
    | Configure password security requirements.
    |
    */
    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', true),
        'max_age_days' => env('PASSWORD_MAX_AGE_DAYS', 90),
        'history_count' => env('PASSWORD_HISTORY_COUNT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist/Blacklist
    |--------------------------------------------------------------------------
    |
    | Configure IP-based access control.
    |
    */
    'ip_control' => [
        'enabled' => env('IP_CONTROL_ENABLED', false),
        'whitelist' => env('IP_WHITELIST', ''),
        'blacklist' => env('IP_BLACKLIST', ''),
        'admin_whitelist' => env('ADMIN_IP_WHITELIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Configure 2FA settings (for future implementation).
    |
    */
    '2fa' => [
        'enabled' => env('2FA_ENABLED', false),
        'required_for_admin' => env('2FA_REQUIRED_FOR_ADMIN', false),
        'backup_codes_count' => env('2FA_BACKUP_CODES_COUNT', 8),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure file upload security settings.
    |
    */
    'file_upload' => [
        'scan_for_malware' => env('SCAN_UPLOADS_FOR_MALWARE', false),
        'quarantine_suspicious' => env('QUARANTINE_SUSPICIOUS_FILES', true),
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup & Recovery
    |--------------------------------------------------------------------------
    |
    | Configure backup and recovery settings.
    |
    */
    'backup' => [
        'enabled' => env('BACKUP_ENABLED', true),
        'frequency' => env('BACKUP_FREQUENCY', 'daily'),
        'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
        'encrypt_backups' => env('ENCRYPT_BACKUPS', true),
    ],

];
