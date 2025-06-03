#!/bin/bash

# Security Setup Script for Graduation Announcement System
# This script sets up security configurations and permissions

echo "🔒 Setting up security for Graduation Announcement System..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}[SETUP]${NC} $1"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    print_warning "Running as root. Consider running as a regular user with sudo privileges."
fi

# 1. Set proper file permissions
print_header "Setting file permissions..."

# Set ownership (adjust user:group as needed)
if [ -n "$1" ]; then
    USER_GROUP="$1"
else
    USER_GROUP="www-data:www-data"
fi

print_status "Setting ownership to $USER_GROUP"
sudo chown -R $USER_GROUP .

# Set directory permissions
print_status "Setting directory permissions..."
find . -type d -exec chmod 755 {} \;

# Set file permissions
print_status "Setting file permissions..."
find . -type f -exec chmod 644 {} \;

# Make specific files executable
chmod +x artisan
chmod +x setup-security.sh

# Secure sensitive directories
print_status "Securing sensitive directories..."
chmod 700 storage/logs
chmod 700 storage/framework/sessions
chmod 700 storage/framework/cache
chmod 700 bootstrap/cache

# Secure configuration files
chmod 600 .env
chmod 600 config/database.php

# 2. Create security directories
print_header "Creating security directories..."

mkdir -p storage/security
mkdir -p storage/backups
mkdir -p storage/quarantine

chmod 700 storage/security
chmod 700 storage/backups
chmod 700 storage/quarantine

# 3. Setup log rotation
print_header "Setting up log rotation..."

# Create logrotate configuration
sudo tee /etc/logrotate.d/graduation-system > /dev/null <<EOF
$(pwd)/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 $USER_GROUP
    postrotate
        # Reload application if needed
    endscript
}

$(pwd)/storage/logs/security*.log {
    daily
    missingok
    rotate 90
    compress
    delaycompress
    notifempty
    create 600 $USER_GROUP
    postrotate
        # Security logs need special handling
    endscript
}
EOF

print_status "Log rotation configured"

# 4. Setup cron jobs for security maintenance
print_header "Setting up security maintenance cron jobs..."

# Create cron job for log cleanup
(crontab -l 2>/dev/null; echo "0 2 * * 0 cd $(pwd) && php artisan security:clean-logs --force") | crontab -

# Create cron job for security reports
(crontab -l 2>/dev/null; echo "0 8 * * 1 cd $(pwd) && php artisan security:report --days=7 > storage/logs/weekly-security-report.log") | crontab -

print_status "Cron jobs configured"

# 5. Configure fail2ban (if available)
if command -v fail2ban-client &> /dev/null; then
    print_header "Configuring fail2ban..."
    
    sudo tee /etc/fail2ban/jail.d/graduation-system.conf > /dev/null <<EOF
[graduation-system-auth]
enabled = true
port = http,https
filter = graduation-system-auth
logpath = $(pwd)/storage/logs/security*.log
maxretry = 5
bantime = 3600
findtime = 600

[graduation-system-dos]
enabled = true
port = http,https
filter = graduation-system-dos
logpath = $(pwd)/storage/logs/laravel*.log
maxretry = 20
bantime = 600
findtime = 60
EOF

    # Create fail2ban filters
    sudo tee /etc/fail2ban/filter.d/graduation-system-auth.conf > /dev/null <<EOF
[Definition]
failregex = .*"event_type":"failed_login".*"ip_address":"<HOST>".*
ignoreregex =
EOF

    sudo tee /etc/fail2ban/filter.d/graduation-system-dos.conf > /dev/null <<EOF
[Definition]
failregex = .*\[<HOST>\].*"GET.*HTTP.*" (4|5)\d\d.*
ignoreregex =
EOF

    sudo systemctl restart fail2ban
    print_status "fail2ban configured and restarted"
else
    print_warning "fail2ban not found. Consider installing it for additional protection."
fi

# 6. Setup SSL/TLS (basic configuration)
print_header "SSL/TLS Configuration Reminder..."
print_warning "Remember to configure SSL/TLS certificates:"
print_warning "1. Obtain SSL certificate (Let's Encrypt recommended)"
print_warning "2. Configure web server (Apache/Nginx) for HTTPS"
print_warning "3. Set HTTPS=true in .env file"
print_warning "4. Test SSL configuration"

# 7. Database security
print_header "Database Security Recommendations..."
print_warning "Database security checklist:"
print_warning "1. Use strong database passwords"
print_warning "2. Limit database user privileges"
print_warning "3. Enable database logging"
print_warning "4. Regular database backups"
print_warning "5. Keep database software updated"

# 8. Firewall configuration
if command -v ufw &> /dev/null; then
    print_header "Configuring UFW firewall..."
    
    # Enable UFW
    sudo ufw --force enable
    
    # Default policies
    sudo ufw default deny incoming
    sudo ufw default allow outgoing
    
    # Allow SSH (adjust port as needed)
    sudo ufw allow 22/tcp
    
    # Allow HTTP and HTTPS
    sudo ufw allow 80/tcp
    sudo ufw allow 443/tcp
    
    # Allow specific IPs for admin access (uncomment and modify as needed)
    # sudo ufw allow from YOUR_ADMIN_IP to any port 22
    
    sudo ufw reload
    print_status "UFW firewall configured"
else
    print_warning "UFW not found. Configure firewall manually."
fi

# 9. System updates reminder
print_header "System Security Reminders..."
print_warning "Regular maintenance tasks:"
print_warning "1. Keep system packages updated: sudo apt update && sudo apt upgrade"
print_warning "2. Monitor security logs: tail -f storage/logs/security*.log"
print_warning "3. Review failed login attempts weekly"
print_warning "4. Update application dependencies: composer update"
print_warning "5. Backup database and files regularly"

# 10. Generate security report
print_header "Generating initial security report..."
php artisan security:report --days=1 > storage/logs/initial-security-report.log
print_status "Initial security report generated: storage/logs/initial-security-report.log"

# 11. Test security features
print_header "Testing security features..."

# Test if migrations ran
if php artisan migrate:status | grep -q "audit_logs"; then
    print_status "Security database tables created successfully"
else
    print_error "Security database tables not found. Run: php artisan migrate"
fi

# Test if middleware is working
if grep -q "SecurityHeaders" bootstrap/app.php; then
    print_status "Security middleware registered"
else
    print_error "Security middleware not registered in bootstrap/app.php"
fi

# 12. Create security checklist
print_header "Creating security checklist..."

cat > SECURITY_CHECKLIST.md << 'EOF'
# Security Checklist

## Daily Tasks
- [ ] Monitor security logs for suspicious activity
- [ ] Check failed login attempts
- [ ] Review system resource usage

## Weekly Tasks
- [ ] Generate and review security report
- [ ] Check for system updates
- [ ] Review user access logs
- [ ] Backup security logs

## Monthly Tasks
- [ ] Update application dependencies
- [ ] Review and update security configurations
- [ ] Test backup and recovery procedures
- [ ] Security vulnerability scan

## Quarterly Tasks
- [ ] Change admin passwords
- [ ] Review user permissions
- [ ] Update SSL certificates (if needed)
- [ ] Security audit and penetration testing

## Emergency Procedures
1. **Security Incident Response**
   - Isolate affected systems
   - Preserve evidence (logs, files)
   - Contact security team
   - Document incident

2. **Suspected Breach**
   - Change all passwords immediately
   - Review all user accounts
   - Check for unauthorized changes
   - Monitor for data exfiltration

3. **System Compromise**
   - Take system offline if necessary
   - Restore from clean backups
   - Patch vulnerabilities
   - Implement additional monitoring

## Contact Information
- System Administrator: [EMAIL]
- Security Team: [EMAIL]
- Emergency Contact: [PHONE]
EOF

print_status "Security checklist created: SECURITY_CHECKLIST.md"

# Final summary
print_header "Security Setup Complete!"
echo ""
print_status "✅ File permissions configured"
print_status "✅ Log rotation setup"
print_status "✅ Cron jobs configured"
print_status "✅ Security directories created"
print_status "✅ Initial security report generated"
print_status "✅ Security checklist created"
echo ""
print_warning "Next steps:"
print_warning "1. Review and customize .env security settings"
print_warning "2. Configure SSL/TLS certificates"
print_warning "3. Test all security features"
print_warning "4. Set up monitoring and alerting"
print_warning "5. Train administrators on security procedures"
echo ""
print_status "Security setup completed successfully! 🔒"
EOF
