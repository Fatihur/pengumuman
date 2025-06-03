<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SecurityMonitoringService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SecurityLogController extends Controller
{
    /**
     * Log client-side security attempts
     */
    public function logSecurityAttempt(Request $request)
    {
        // Rate limit security logging to prevent spam
        $key = 'security-log:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['status' => 'rate_limited'], 429);
        }
        
        RateLimiter::hit($key, 60); // 10 attempts per minute
        
        try {
            $type = $request->input('type', 'unknown');
            $details = $request->input('details', []);
            $userAgent = $request->userAgent();
            $url = $request->input('url', $request->header('referer'));
            
            // Map client-side events to security event types
            $eventTypeMap = [
                'right_click' => 'client_protection_right_click',
                'keyboard_shortcut' => 'client_protection_keyboard',
                'text_selection' => 'client_protection_text_selection',
                'image_drag' => 'client_protection_image_drag',
                'print_screen' => 'client_protection_print_screen',
                'devtools_detected' => 'client_protection_devtools',
                'console_access' => 'client_protection_console',
                'eval_attempt' => 'client_protection_eval',
                'window_blur' => 'client_protection_window_blur',
                'protection_initialized' => 'client_protection_initialized'
            ];
            
            $eventType = $eventTypeMap[$type] ?? 'client_protection_unknown';
            
            // Determine severity based on event type
            $severity = match($type) {
                'devtools_detected', 'console_access', 'eval_attempt' => 'high',
                'right_click', 'keyboard_shortcut', 'print_screen' => 'medium',
                'text_selection', 'image_drag', 'window_blur' => 'low',
                'protection_initialized' => 'info',
                default => 'low'
            };
            
            // Create description
            $descriptions = [
                'right_click' => 'User attempted to right-click (context menu blocked)',
                'keyboard_shortcut' => 'User attempted to use blocked keyboard shortcut',
                'text_selection' => 'User attempted to select text (selection blocked)',
                'image_drag' => 'User attempted to drag image (drag blocked)',
                'print_screen' => 'User attempted to use print screen',
                'devtools_detected' => 'Developer tools detected and blocked',
                'console_access' => 'User attempted to access browser console',
                'eval_attempt' => 'User attempted to use eval() function',
                'window_blur' => 'Window lost focus (possible DevTools activation)',
                'protection_initialized' => 'Client-side protection successfully initialized'
            ];
            
            $description = $descriptions[$type] ?? 'Unknown client-side protection event';
            
            // Enhanced metadata
            $metadata = array_merge($details, [
                'client_timestamp' => $request->input('timestamp'),
                'user_agent' => $userAgent,
                'url' => $url,
                'screen_resolution' => $request->input('screen_resolution'),
                'viewport_size' => $request->input('viewport_size'),
                'browser_language' => $request->header('accept-language'),
                'referrer' => $request->header('referer')
            ]);
            
            // Log the security event
            SecurityMonitoringService::logSecurityEvent(
                $eventType,
                $description,
                $severity,
                $metadata,
                false // Not blocked at server level
            );
            
            // Check for suspicious patterns
            $this->checkSuspiciousPatterns($request, $type);
            
            return response()->json(['status' => 'logged'], 200);
            
        } catch (\Exception $e) {
            Log::error('Failed to log client security attempt', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'ip' => $request->ip()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }
    
    /**
     * Check for suspicious patterns in client-side events
     */
    protected function checkSuspiciousPatterns(Request $request, string $eventType): void
    {
        $ip = $request->ip();
        $timeWindow = now()->subMinutes(5);
        
        // Count recent client protection events from this IP
        $recentEvents = \DB::table('security_logs')
            ->where('ip_address', $ip)
            ->where('event_type', 'like', 'client_protection_%')
            ->where('occurred_at', '>', $timeWindow)
            ->count();
        
        // If more than 10 client protection events in 5 minutes, mark as suspicious
        if ($recentEvents > 10) {
            SecurityMonitoringService::logSecurityEvent(
                'suspicious_client_activity',
                "High frequency of client-side protection events from IP: {$ip}",
                'high',
                [
                    'ip' => $ip,
                    'event_count' => $recentEvents,
                    'time_window' => '5_minutes',
                    'latest_event_type' => $eventType
                ]
            );
        }
        
        // Check for DevTools detection patterns
        if ($eventType === 'devtools_detected') {
            $devToolsEvents = \DB::table('security_logs')
                ->where('ip_address', $ip)
                ->where('event_type', 'client_protection_devtools')
                ->where('occurred_at', '>', now()->subHour())
                ->count();
            
            if ($devToolsEvents > 3) {
                SecurityMonitoringService::logSecurityEvent(
                    'persistent_devtools_attempts',
                    "Persistent developer tools usage attempts from IP: {$ip}",
                    'critical',
                    [
                        'ip' => $ip,
                        'devtools_attempts' => $devToolsEvents,
                        'time_window' => '1_hour'
                    ]
                );
            }
        }
    }
    
    /**
     * Get client protection statistics
     */
    public function getProtectionStats(Request $request)
    {
        // Only allow admin access
        if (!auth()->check()) {
            abort(403);
        }
        
        $days = $request->input('days', 7);
        $startDate = now()->subDays($days);
        
        $stats = [
            'total_events' => \DB::table('security_logs')
                ->where('event_type', 'like', 'client_protection_%')
                ->where('occurred_at', '>', $startDate)
                ->count(),
            
            'by_type' => \DB::table('security_logs')
                ->select('event_type', \DB::raw('count(*) as count'))
                ->where('event_type', 'like', 'client_protection_%')
                ->where('occurred_at', '>', $startDate)
                ->groupBy('event_type')
                ->orderBy('count', 'desc')
                ->get(),
            
            'by_severity' => \DB::table('security_logs')
                ->select('severity', \DB::raw('count(*) as count'))
                ->where('event_type', 'like', 'client_protection_%')
                ->where('occurred_at', '>', $startDate)
                ->groupBy('severity')
                ->get(),
            
            'top_ips' => \DB::table('security_logs')
                ->select('ip_address', \DB::raw('count(*) as count'))
                ->where('event_type', 'like', 'client_protection_%')
                ->where('occurred_at', '>', $startDate)
                ->groupBy('ip_address')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            
            'daily_events' => \DB::table('security_logs')
                ->select(\DB::raw('DATE(occurred_at) as date'), \DB::raw('count(*) as count'))
                ->where('event_type', 'like', 'client_protection_%')
                ->where('occurred_at', '>', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
        
        return response()->json($stats);
    }
}
