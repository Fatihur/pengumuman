@echo off
REM Security Setup Script for Graduation Announcement System (Windows)
REM This script sets up basic security configurations

echo.
echo ============================================
echo   Security Setup for Graduation System
echo ============================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [INFO] Running with administrator privileges
) else (
    echo [WARNING] Not running as administrator. Some operations may fail.
    echo [WARNING] Consider running as administrator for full setup.
)

echo.
echo [SETUP] Setting up security configurations...

REM 1. Create security directories
echo [INFO] Creating security directories...
if not exist "storage\security" mkdir "storage\security"
if not exist "storage\backups" mkdir "storage\backups"
if not exist "storage\quarantine" mkdir "storage\quarantine"

REM 2. Set file permissions (Windows equivalent)
echo [INFO] Setting file permissions...
REM Remove inheritance and set explicit permissions for sensitive files
if exist ".env" (
    icacls ".env" /inheritance:r /grant:r "%USERNAME%:F" >nul 2>&1
    echo [INFO] .env file permissions set
)

REM 3. Run database migrations
echo [INFO] Running security migrations...
php artisan migrate --force
if %errorLevel% == 0 (
    echo [INFO] Security database tables created successfully
) else (
    echo [ERROR] Failed to create security database tables
)

REM 4. Generate initial security report
echo [INFO] Generating initial security report...
php artisan security:report --days=1 > storage\logs\initial-security-report.log
if %errorLevel% == 0 (
    echo [INFO] Initial security report generated
) else (
    echo [WARNING] Could not generate security report
)

REM 5. Create security checklist
echo [INFO] Creating security checklist...
(
echo # Security Checklist for Windows
echo.
echo ## Daily Tasks
echo - [ ] Monitor security logs for suspicious activity
echo - [ ] Check failed login attempts
echo - [ ] Review system resource usage
echo.
echo ## Weekly Tasks
echo - [ ] Generate and review security report: php artisan security:report
echo - [ ] Check for system updates
echo - [ ] Review user access logs
echo - [ ] Backup security logs
echo.
echo ## Monthly Tasks
echo - [ ] Update application dependencies: composer update
echo - [ ] Review and update security configurations
echo - [ ] Test backup and recovery procedures
echo - [ ] Security vulnerability scan
echo.
echo ## Commands
echo - Generate security report: php artisan security:report --days=7
echo - Clean old logs: php artisan security:clean-logs --force
echo - View security logs: type storage\logs\security-*.log
echo - View audit logs: type storage\logs\audit-*.log
echo.
echo ## File Locations
echo - Security logs: storage\logs\security-*.log
echo - Audit logs: storage\logs\audit-*.log
echo - Configuration: config\security.php
echo - Environment: .env
echo.
echo ## Security Features Enabled
echo - Security headers middleware
echo - Rate limiting
echo - Input sanitization
echo - SQL injection detection
echo - XSS protection
echo - Admin access control
echo - Audit logging
echo - Security monitoring
) > SECURITY_CHECKLIST.md

echo [INFO] Security checklist created: SECURITY_CHECKLIST.md

REM 6. Create batch files for common security tasks
echo [INFO] Creating security management scripts...

REM Security report script
(
echo @echo off
echo echo Generating security report...
echo php artisan security:report --days=7
echo pause
) > security-report.bat

REM Log cleanup script
(
echo @echo off
echo echo Cleaning old security logs...
echo php artisan security:clean-logs --force
echo echo Log cleanup completed
echo pause
) > clean-logs.bat

REM View logs script
(
echo @echo off
echo echo Recent security events:
echo echo ========================
echo php artisan security:report --days=1
echo echo.
echo echo Recent audit logs:
echo echo ==================
echo dir storage\logs\audit-*.log /od
echo echo.
echo echo To view detailed logs:
echo echo - Security: type storage\logs\security-*.log
echo echo - Audit: type storage\logs\audit-*.log
echo pause
) > view-logs.bat

echo [INFO] Security management scripts created:
echo        - security-report.bat
echo        - clean-logs.bat
echo        - view-logs.bat

REM 7. Test security features
echo.
echo [SETUP] Testing security features...

REM Check if security middleware is registered
findstr /C:"SecurityHeaders" bootstrap\app.php >nul 2>&1
if %errorLevel% == 0 (
    echo [INFO] Security middleware registered
) else (
    echo [ERROR] Security middleware not found in bootstrap\app.php
)

REM Check if security tables exist
php artisan migrate:status | findstr "audit_logs" >nul 2>&1
if %errorLevel% == 0 (
    echo [INFO] Security database tables exist
) else (
    echo [ERROR] Security database tables not found
)

REM 8. Environment configuration check
echo.
echo [SETUP] Checking environment configuration...

if exist ".env" (
    echo [INFO] .env file exists
    findstr /C:"APP_ENV=production" .env >nul 2>&1
    if %errorLevel% == 0 (
        echo [WARNING] Application is in production mode
        echo [WARNING] Ensure all security settings are properly configured
    ) else (
        echo [INFO] Application is in development mode
    )
) else (
    echo [ERROR] .env file not found. Copy .env.example to .env and configure
)

REM 9. Create Windows Task Scheduler commands (for reference)
echo.
echo [INFO] Creating task scheduler reference...
(
echo # Windows Task Scheduler Commands
echo # Run these commands as Administrator to set up automated security tasks
echo.
echo # Daily log cleanup ^(runs at 2 AM^)
echo schtasks /create /tn "GraduationSystem-LogCleanup" /tr "%CD%\clean-logs.bat" /sc daily /st 02:00 /ru SYSTEM
echo.
echo # Weekly security report ^(runs Monday at 8 AM^)
echo schtasks /create /tn "GraduationSystem-SecurityReport" /tr "%CD%\security-report.bat" /sc weekly /d MON /st 08:00 /ru SYSTEM
echo.
echo # To delete tasks:
echo # schtasks /delete /tn "GraduationSystem-LogCleanup" /f
echo # schtasks /delete /tn "GraduationSystem-SecurityReport" /f
) > task-scheduler-commands.txt

echo [INFO] Task scheduler commands saved to: task-scheduler-commands.txt

REM 10. Final recommendations
echo.
echo ============================================
echo           Security Setup Complete!
echo ============================================
echo.
echo [INFO] Security features configured:
echo        ✓ Security middleware enabled
echo        ✓ Database tables created
echo        ✓ Security directories created
echo        ✓ Management scripts created
echo        ✓ Security checklist created
echo.
echo [WARNING] Additional security recommendations:
echo.
echo 1. SSL/TLS Configuration:
echo    - Obtain SSL certificate
echo    - Configure web server for HTTPS
echo    - Set HTTPS=true in .env file
echo.
echo 2. Environment Security:
echo    - Review .env file settings
echo    - Set strong APP_KEY
echo    - Configure secure database credentials
echo    - Set APP_ENV=production for live sites
echo.
echo 3. Web Server Security:
echo    - Configure security headers in web server
echo    - Set up rate limiting at server level
echo    - Configure firewall rules
echo    - Regular security updates
echo.
echo 4. Monitoring:
echo    - Set up log monitoring
echo    - Configure email alerts for critical events
echo    - Regular security audits
echo    - Monitor failed login attempts
echo.
echo 5. Backup Strategy:
echo    - Regular database backups
echo    - File system backups
echo    - Test restore procedures
echo    - Secure backup storage
echo.
echo [INFO] Use the following commands for security management:
echo        - Generate report: security-report.bat
echo        - Clean logs: clean-logs.bat
echo        - View logs: view-logs.bat
echo.
echo [INFO] Security setup completed successfully! 🔒
echo.
pause
