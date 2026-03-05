<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SensitiveEndpointRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limiter = 'default'): Response
    {
        $key = $this->resolveRequestSignature($request, $limiter);

        $maxAttempts = match ($limiter) {
            'login' => 5,
            'password' => 3,
            'api' => 60,
            'export' => 10,
            'bulk' => 5,
            default => 30,
        };

        $decayMinutes = match ($limiter) {
            'login' => 1,
            'password' => 60,
            'api' => 1,
            'export' => 5,
            'bulk' => 10,
            default => 1,
        };

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'error' => 'Too many requests.',
                'message' => "Please try again in {$seconds} seconds.",
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve request signature for rate limiting.
     */
    private function resolveRequestSignature(Request $request, string $limiter): string
    {
        $userId = auth()->id() ?? 'guest';
        $ip = $request->ip() ?? 'unknown';

        return "{$limiter}:{$userId}:{$ip}:".$request->route()?->getName() ?? $request->path();
    }
}
