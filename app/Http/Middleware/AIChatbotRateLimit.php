<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AIChatbotRateLimit
{
    public function __construct(
        private RateLimiter $limiter
    ) {}

    public function handle(Request $request, Closure $next, string $type = 'chat'): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        $key = "ai_chatbot:rate_limit:{$type}:{$user->id}";
        $maxAttempts = $type === 'stream' ? 10 : 30;
        $decayMinutes = 1;

        if (Cache::has($key) && Cache::get($key) >= $maxAttempts) {
            return response()->json([
                'error' => 'Too many requests. Please try again in a moment.',
                'retry_after' => Cache::get($key.':timer') ?? 60,
            ], 429);
        }

        $current = Cache::get($key, 0);
        if ($current === 0) {
            Cache::put($key.':timer', now()->addMinutes($decayMinutes)->timestamp, $decayMinutes * 60);
        }
        Cache::increment($key);
        Cache::put($key, $current + 1, $decayMinutes * 60);

        return $next($request);
    }
}
