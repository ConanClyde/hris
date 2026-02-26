<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    /**
     * Set Cache-Control and Vary for HTML responses so caches and CDNs behave correctly.
     * Does not cache HTML (dynamic); adds Vary for proper downstream caching.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $type = $response->headers->get('Content-Type', '');
            if (str_contains($type, 'text/html')) {
                $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $response->headers->set('Vary', 'Accept-Encoding, Cookie');
            }
        }

        return $response;
    }
}
