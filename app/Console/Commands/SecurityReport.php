<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SecurityMonitoringService;
use App\Services\AuditLogService;

class SecurityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:report {--days=7 : Number of days to include in report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate security monitoring report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        $this->info("Security Report - Last {$days} days");
        $this->info(str_repeat('=', 50));

        // Get security statistics
        $stats = SecurityMonitoringService::getSecurityStats($days);
        
        $this->info('Security Events Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Security Events', $stats['total_events']],
                ['Critical Events', $stats['critical_events']],
                ['Blocked Attempts', $stats['blocked_attempts']],
                ['Unique IP Addresses', $stats['unique_ips']],
            ]
        );

        if (!empty($stats['top_event_types'])) {
            $this->info('Top Event Types:');
            $eventData = [];
            foreach ($stats['top_event_types'] as $event) {
                $eventData[] = [$event->event_type, $event->count];
            }
            $this->table(['Event Type', 'Count'], $eventData);
        }

        // Get recent critical events
        $recentEvents = SecurityMonitoringService::getRecentEvents(10);
        $criticalEvents = array_filter($recentEvents, function($event) {
            return $event->severity === 'critical';
        });

        if (!empty($criticalEvents)) {
            $this->info('Recent Critical Events:');
            $criticalData = [];
            foreach (array_slice($criticalEvents, 0, 5) as $event) {
                $criticalData[] = [
                    $event->event_type,
                    $event->ip_address,
                    $event->description,
                    $event->occurred_at
                ];
            }
            $this->table(
                ['Type', 'IP', 'Description', 'Time'],
                $criticalData
            );
        }

        // Get recent audit logs
        $recentAuditLogs = AuditLogService::getRecentLogs(5);
        if (!empty($recentAuditLogs)) {
            $this->info('Recent Admin Activities:');
            $auditData = [];
            foreach ($recentAuditLogs as $log) {
                $auditData[] = [
                    $log->user_email ?? 'System',
                    $log->action,
                    substr($log->description, 0, 50) . '...',
                    $log->created_at
                ];
            }
            $this->table(
                ['User', 'Action', 'Description', 'Time'],
                $auditData
            );
        }

        $this->info(str_repeat('=', 50));
        $this->info('Report generated successfully!');

        return 0;
    }
}
