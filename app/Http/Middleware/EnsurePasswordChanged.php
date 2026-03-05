<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';

        if ($routeName === null) {
            return $next($request);
        }

        if (in_array($routeName, ['force-password.show', 'force-password.update', 'logout'], true)) {
            return $next($request);
        }
        if ($routeName === 'force-password.show') {
            return $next($request);
        }

        return redirect()->route('force-password.show')->with('error', 'You must change your password before continuing.');
    }
}
