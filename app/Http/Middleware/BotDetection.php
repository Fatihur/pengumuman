<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityMonitoringService;
use Symfony\Component\HttpFoundation\Response;

class BotDetection
{
    /**
     * Known bot user agents
     */
    protected array $botUserAgents = [
        'bot', 'crawler', 'spider', 'scraper', 'parser',
        'googlebot', 'bingbot', 'slurp', 'duckduckbot',
        'baiduspider', 'yandexbot', 'facebookexternalhit',
        'twitterbot', 'linkedinbot', 'whatsapp', 'telegram',
        'curl', 'wget', 'python', 'java', 'php', 'ruby',
        'perl', 'go-http-client', 'apache-httpclient',
        'okhttp', 'axios', 'requests', 'urllib',
        'headless', 'phantom', 'selenium', 'webdriver',
        'puppeteer', 'playwright', 'chrome-lighthouse'
    ];

    /**
     * Suspicious patterns in user agents
     */
    protected array $suspiciousPatterns = [
        '/^Mozilla\/5\.0$/',
        '/^Mozilla\/4\.0$/',
        '/^User-Agent:/',
        '/^$/i',
        '/\b(test|scan|hack|exploit|inject|attack)\b/i',
        '/\b(nikto|nmap|sqlmap|burp|zap|acunetix)\b/i'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if bot detection is enabled
        if (!config('security.monitoring.bot_detection_enabled', false)) {
            return $next($request);
        }

        $userAgent = $request->userAgent() ?? '';
        $ip = $request->ip();
        
        // Check for bot user agents
        if ($this->isBotUserAgent($userAgent)) {
            $this->logBotDetection($request, 'bot_user_agent', $userAgent);
            
            // Allow legitimate search engine bots
            if ($this->isLegitimateBot($userAgent, $ip)) {
                return $next($request);
            }
            
            // Block suspicious bots
            return $this->blockBot($request, 'Suspicious bot detected');
        }
        
        // Check for suspicious patterns
        if ($this->hasSuspiciousPattern($userAgent)) {
            $this->logBotDetection($request, 'suspicious_user_agent', $userAgent);
            return $this->blockBot($request, 'Suspicious user agent');
        }
        
        // Check for missing common headers
        if ($this->isMissingCommonHeaders($request)) {
            $this->logBotDetection($request, 'missing_headers', [
                'accept' => $request->header('accept'),
                'accept_language' => $request->header('accept-language'),
                'accept_encoding' => $request->header('accept-encoding')
            ]);
            
            // Don't block immediately, just log
        }
        
        // Check request frequency
        if ($this->isHighFrequencyRequest($ip)) {
            $this->logBotDetection($request, 'high_frequency', ['ip' => $ip]);
            return $this->blockBot($request, 'Too many requests');
        }
        
        return $next($request);
    }

    /**
     * Check if user agent indicates a bot
     */
    protected function isBotUserAgent(string $userAgent): bool
    {
        $userAgentLower = strtolower($userAgent);
        
        foreach ($this->botUserAgents as $botPattern) {
            if (strpos($userAgentLower, $botPattern) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if it's a legitimate search engine bot
     */
    protected function isLegitimateBot(string $userAgent, string $ip): bool
    {
        $legitimateBots = [
            'googlebot' => ['66.249.', '209.85.', '216.239.'],
            'bingbot' => ['40.77.', '157.55.', '207.46.'],
            'slurp' => ['72.30.', '98.137.', '202.160.'],
            'duckduckbot' => ['23.21.', '50.16.', '107.21.'],
            'baiduspider' => ['119.63.', '123.125.', '180.76.'],
            'yandexbot' => ['5.45.', '37.9.', '87.250.', '95.108.', '178.154.', '199.21.']
        ];
        
        $userAgentLower = strtolower($userAgent);
        
        foreach ($legitimateBots as $bot => $ipRanges) {
            if (strpos($userAgentLower, $bot) !== false) {
                foreach ($ipRanges as $range) {
                    if (strpos($ip, $range) === 0) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Check for suspicious patterns in user agent
     */
    protected function hasSuspiciousPattern(string $userAgent): bool
    {
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if request is missing common browser headers
     */
    protected function isMissingCommonHeaders(Request $request): bool
    {
        $requiredHeaders = ['accept', 'accept-language', 'accept-encoding'];
        $missingCount = 0;
        
        foreach ($requiredHeaders as $header) {
            if (!$request->hasHeader($header)) {
                $missingCount++;
            }
        }
        
        // If missing 2 or more common headers, it's suspicious
        return $missingCount >= 2;
    }

    /**
     * Check for high frequency requests from same IP
     */
    protected function isHighFrequencyRequest(string $ip): bool
    {
        $cacheKey = "bot_detection_freq_{$ip}";
        $requests = cache()->get($cacheKey, 0);
        
        // More than 30 requests per minute is suspicious
        if ($requests > 30) {
            return true;
        }
        
        cache()->put($cacheKey, $requests + 1, now()->addMinute());
        return false;
    }

    /**
     * Log bot detection
     */
    protected function logBotDetection(Request $request, string $type, $details): void
    {
        SecurityMonitoringService::logSecurityEvent(
            "bot_detection_{$type}",
            "Bot or automated tool detected: {$type}",
            'medium',
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'details' => $details
            ]
        );
    }

    /**
     * Block bot request
     */
    protected function blockBot(Request $request, string $reason): Response
    {
        SecurityMonitoringService::logSecurityEvent(
            'bot_blocked',
            "Bot request blocked: {$reason}",
            'high',
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'reason' => $reason
            ],
            true
        );

        // Return different responses based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'Automated requests are not allowed'
            ], 403);
        }

        // For web requests, return a simple HTML page
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Access Denied</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #e74c3c; margin-bottom: 20px; }
        p { color: #666; line-height: 1.6; }
        .code { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚫 Access Denied</h1>
        <p>Your request has been identified as automated or suspicious.</p>
        <p>If you believe this is an error, please contact the administrator.</p>
        <div class="code">
            Error Code: BOT_DETECTED<br>
            Timestamp: ' . now()->toISOString() . '<br>
            Reference: ' . substr(md5($request->ip() . $request->userAgent()), 0, 8) . '
        </div>
        <p><small>This page is protected by automated security systems.</small></p>
    </div>
</body>
</html>';

        return response($html, 403)
            ->header('Content-Type', 'text/html')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
