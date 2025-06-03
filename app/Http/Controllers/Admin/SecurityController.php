<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use App\Services\SecurityMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    /**
     * Display security dashboard
     */
    public function index()
    {
        // Get security statistics for last 7 days
        $stats = SecurityMonitoringService::getSecurityStats(7);
        
        // Get recent security events
        $recentEvents = SecurityMonitoringService::getRecentEvents(20);
        
        // Get recent audit logs
        $recentAuditLogs = AuditLogService::getRecentLogs(20);
        
        // Get daily security events for chart
        $dailyEvents = DB::table('security_logs')
            ->select(
                DB::raw('DATE(occurred_at) as date'),
                DB::raw('COUNT(*) as count'),
                'severity'
            )
            ->where('occurred_at', '>', now()->subDays(7))
            ->groupBy('date', 'severity')
            ->orderBy('date')
            ->get();
        
        return view('admin.security.index', compact(
            'stats',
            'recentEvents',
            'recentAuditLogs',
            'dailyEvents'
        ));
    }

    /**
     * Display security events
     */
    public function events(Request $request)
    {
        $query = DB::table('security_logs')
            ->orderBy('occurred_at', 'desc');
        
        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        
        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('occurred_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('occurred_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $events = $query->paginate(50);
        
        // Get unique event types for filter
        $eventTypes = DB::table('security_logs')
            ->select('event_type')
            ->distinct()
            ->orderBy('event_type')
            ->pluck('event_type');
        
        return view('admin.security.events', compact('events', 'eventTypes'));
    }

    /**
     * Display audit logs
     */
    public function auditLogs(Request $request)
    {
        $query = DB::table('audit_logs')
            ->orderBy('created_at', 'desc');
        
        // Filter by user
        if ($request->filled('user_email')) {
            $query->where('user_email', 'like', '%' . $request->user_email . '%');
        }
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $auditLogs = $query->paginate(50);
        
        // Get unique actions for filter
        $actions = DB::table('audit_logs')
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');
        
        return view('admin.security.audit-logs', compact('auditLogs', 'actions'));
    }

    /**
     * Display security event details
     */
    public function eventDetails($id)
    {
        $event = DB::table('security_logs')->find($id);
        
        if (!$event) {
            abort(404);
        }
        
        // Decode metadata
        $event->metadata = json_decode($event->metadata, true);
        
        return view('admin.security.event-details', compact('event'));
    }

    /**
     * Display audit log details
     */
    public function auditLogDetails($id)
    {
        $auditLog = DB::table('audit_logs')->find($id);
        
        if (!$auditLog) {
            abort(404);
        }
        
        // Decode data
        $auditLog->data = json_decode($auditLog->data, true);
        
        return view('admin.security.audit-log-details', compact('auditLog'));
    }

    /**
     * Export security report
     */
    public function exportReport(Request $request)
    {
        $days = $request->input('days', 7);
        $stats = SecurityMonitoringService::getSecurityStats($days);
        $events = SecurityMonitoringService::getRecentEvents(1000);
        
        $filename = 'security_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($stats, $events) {
            $file = fopen('php://output', 'w');
            
            // Write summary
            fputcsv($file, ['Security Report Summary']);
            fputcsv($file, ['Total Events', $stats['total_events']]);
            fputcsv($file, ['Critical Events', $stats['critical_events']]);
            fputcsv($file, ['Blocked Attempts', $stats['blocked_attempts']]);
            fputcsv($file, ['Unique IPs', $stats['unique_ips']]);
            fputcsv($file, []);
            
            // Write events header
            fputcsv($file, [
                'ID', 'Event Type', 'Severity', 'IP Address', 
                'Email', 'Description', 'Is Blocked', 'Occurred At'
            ]);
            
            // Write events
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->event_type,
                    $event->severity,
                    $event->ip_address,
                    $event->email,
                    $event->description,
                    $event->is_blocked ? 'Yes' : 'No',
                    $event->occurred_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clean old logs
     */
    public function cleanLogs(Request $request)
    {
        $auditDeleted = AuditLogService::cleanOldLogs();
        $securityDeleted = SecurityMonitoringService::cleanOldLogs();
        
        AuditLogService::log(
            'security.clean_logs',
            'Old security and audit logs cleaned',
            [
                'audit_deleted' => $auditDeleted,
                'security_deleted' => $securityDeleted
            ]
        );
        
        return redirect()->back()->with('success', 
            "Successfully deleted {$auditDeleted} audit logs and {$securityDeleted} security logs."
        );
    }
}
