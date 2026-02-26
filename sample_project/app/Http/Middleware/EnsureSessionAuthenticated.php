<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Uses Laravel's Auth guard instead of raw session variables.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! (Auth::check() || session('user_id'))) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
