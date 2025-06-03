<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InputSanitization
{
    /**
     * Suspicious patterns to detect
     */
    protected array $suspiciousPatterns = [
        // SQL Injection patterns
        '/(\bunion\b.*\bselect\b)|(\bselect\b.*\bunion\b)/i',
        '/(\bor\b\s+\d+\s*=\s*\d+)|(\band\b\s+\d+\s*=\s*\d+)/i',
        '/(\bdrop\b\s+\btable\b)|(\bdelete\b\s+\bfrom\b)/i',
        '/(\binsert\b\s+\binto\b)|(\bupdate\b.*\bset\b)/i',
        
        // XSS patterns
        '/<script[^>]*>.*?<\/script>/is',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/<iframe[^>]*>.*?<\/iframe>/is',
        
        // Path traversal
        '/\.\.\/|\.\.\\\/i',
        '/\.\.\\\\/i',
        
        // Command injection
        '/[;&|`$(){}]/i',
        '/\b(exec|system|shell_exec|passthru|eval)\b/i',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize and validate input
        $this->sanitizeRequest($request);
        
        // Check for suspicious patterns
        $this->detectSuspiciousInput($request);
        
        return $next($request);
    }

    /**
     * Sanitize request input
     */
    protected function sanitizeRequest(Request $request): void
    {
        $input = $request->all();
        $sanitized = $this->sanitizeArray($input);
        $request->replace($sanitized);
    }

    /**
     * Recursively sanitize array
     */
    protected function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->sanitizeString($value);
            }
        }
        
        return $data;
    }

    /**
     * Sanitize string input
     */
    protected function sanitizeString(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        // Remove control characters except newlines and tabs
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        return $input;
    }

    /**
     * Detect suspicious input patterns
     */
    protected function detectSuspiciousInput(Request $request): void
    {
        $input = json_encode($request->all());
        
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                Log::warning('Suspicious input detected', [
                    'pattern' => $pattern,
                    'input' => $input,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'timestamp' => now()
                ]);
                
                // You might want to block the request here
                // abort(403, 'Suspicious input detected');
                break;
            }
        }
    }
}
