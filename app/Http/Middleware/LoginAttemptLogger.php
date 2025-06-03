<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoginAttemptLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only process login attempts
        if ($request->isMethod('POST') && $request->routeIs('login.post')) {
            $this->logLoginAttempt($request);
            
            // Check for brute force attempts
            if ($this->isBruteForceAttempt($request)) {
                Log::critical('Brute force login attempt detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'email' => $request->input('email'),
                    'timestamp' => now()
                ]);
                
                // Block the request
                return response()->json([
                    'error' => 'Too many failed login attempts. Please try again later.'
                ], 429);
            }
        }
        
        $response = $next($request);
        
        // Log successful/failed login after processing
        if ($request->isMethod('POST') && $request->routeIs('login.post')) {
            $this->logLoginResult($request, $response);
        }
        
        return $response;
    }

    /**
     * Log login attempt
     */
    protected function logLoginAttempt(Request $request): void
    {
        Log::info('Login attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'email' => $request->input('email'),
            'timestamp' => now(),
            'referer' => $request->header('referer')
        ]);
    }

    /**
     * Log login result
     */
    protected function logLoginResult(Request $request, Response $response): void
    {
        $isSuccessful = $response->isRedirection() && !$response->getTargetUrl() === $request->url();
        
        if ($isSuccessful) {
            Log::info('Successful login', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'timestamp' => now()
            ]);
            
            // Clear failed attempts on successful login
            $this->clearFailedAttempts($request);
        } else {
            Log::warning('Failed login attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->input('email'),
                'timestamp' => now()
            ]);
            
            // Track failed attempts
            $this->trackFailedAttempt($request);
        }
    }

    /**
     * Check if this is a brute force attempt
     */
    protected function isBruteForceAttempt(Request $request): bool
    {
        $ip = $request->ip();
        $email = $request->input('email');
        
        // Check failed attempts by IP
        $ipAttempts = Cache::get("failed_login_ip_{$ip}", 0);
        
        // Check failed attempts by email
        $emailAttempts = Cache::get("failed_login_email_{$email}", 0);
        
        // Block if more than 5 attempts from same IP in 15 minutes
        // or more than 3 attempts for same email in 15 minutes
        return $ipAttempts >= 5 || $emailAttempts >= 3;
    }

    /**
     * Track failed login attempt
     */
    protected function trackFailedAttempt(Request $request): void
    {
        $ip = $request->ip();
        $email = $request->input('email');
        
        // Track by IP
        $ipKey = "failed_login_ip_{$ip}";
        $ipAttempts = Cache::get($ipKey, 0) + 1;
        Cache::put($ipKey, $ipAttempts, now()->addMinutes(15));
        
        // Track by email
        $emailKey = "failed_login_email_{$email}";
        $emailAttempts = Cache::get($emailKey, 0) + 1;
        Cache::put($emailKey, $emailAttempts, now()->addMinutes(15));
    }

    /**
     * Clear failed attempts on successful login
     */
    protected function clearFailedAttempts(Request $request): void
    {
        $ip = $request->ip();
        $email = $request->input('email');
        
        Cache::forget("failed_login_ip_{$ip}");
        Cache::forget("failed_login_email_{$email}");
    }
}
