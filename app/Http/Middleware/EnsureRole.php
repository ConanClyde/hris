<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * Uses Auth::user()->role instead of session('role').
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $isAuthenticated = Auth::check();

        $user = Auth::user();
        $currentRole = $user?->role;

        if (! $isAuthenticated || ! $currentRole) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return redirect()->route('login');
        }

        if ((string) $currentRole !== $role) {
            // Admin can bypass role checks
            if ($user?->isAdmin()) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
