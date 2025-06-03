<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessControl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Check if user has admin role (if role system exists)
        if (method_exists($user, 'hasRole') && !$user->hasRole('admin')) {
            Log::warning('Non-admin user attempted to access admin area', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'timestamp' => now()
            ]);
            
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda tidak memiliki izin admin.');
        }

        // Log admin access for audit trail
        Log::info('Admin access', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()
        ]);

        // Check for suspicious activity patterns
        $this->checkSuspiciousActivity($request, $user);

        return $next($request);
    }

    /**
     * Check for suspicious activity patterns
     */
    protected function checkSuspiciousActivity(Request $request, $user): void
    {
        $cacheKey = "admin_activity_{$user->id}";
        $activities = cache()->get($cacheKey, []);
        
        // Add current activity
        $activities[] = [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'timestamp' => now()->timestamp
        ];
        
        // Keep only last 10 activities
        $activities = array_slice($activities, -10);
        
        // Check for rapid requests (more than 20 requests in 1 minute)
        $recentActivities = array_filter($activities, function($activity) {
            return $activity['timestamp'] > (now()->timestamp - 60);
        });
        
        if (count($recentActivities) > 20) {
            Log::warning('Suspicious admin activity detected - rapid requests', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'request_count' => count($recentActivities),
                'timestamp' => now()
            ]);
        }
        
        // Check for multiple IP addresses in short time
        $uniqueIps = array_unique(array_column($recentActivities, 'ip'));
        if (count($uniqueIps) > 2) {
            Log::warning('Suspicious admin activity detected - multiple IPs', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ips' => $uniqueIps,
                'timestamp' => now()
            ]);
        }
        
        // Cache activities for 1 hour
        cache()->put($cacheKey, $activities, now()->addHour());
    }
}
