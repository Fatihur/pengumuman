<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AuditLogService;
use App\Services\SecurityMonitoringService;

class CleanSecurityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:clean-logs {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old security and audit logs to maintain database performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will permanently delete old security and audit logs. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting log cleanup...');

        // Clean audit logs (older than 6 months)
        $this->info('Cleaning audit logs...');
        $auditDeleted = AuditLogService::cleanOldLogs();
        $this->info("Deleted {$auditDeleted} old audit log entries.");

        // Clean security logs (older than 3 months)
        $this->info('Cleaning security logs...');
        $securityDeleted = SecurityMonitoringService::cleanOldLogs();
        $this->info("Deleted {$securityDeleted} old security log entries.");

        $this->info('Log cleanup completed successfully!');
        $this->info("Total entries deleted: " . ($auditDeleted + $securityDeleted));

        return 0;
    }
}
