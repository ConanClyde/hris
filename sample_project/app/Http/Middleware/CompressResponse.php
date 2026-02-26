<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Compress response with gzip when client supports it and content is compressible.
     * Improves transfer time on standard broadband. Many hosts already enable gzip at server level;
     * this helps when PHP is served directly (e.g. built-in server or some shared hosts).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldCompress($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || $content === '') {
            return $response;
        }

        $compressed = @gzencode($content, 6);
        if ($compressed === false) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Vary', 'Accept-Encoding');
        $response->headers->remove('Content-Length');

        return $response;
    }

    private function shouldCompress(Request $request, Response $response): bool
    {
        if (! str_contains((string) $request->headers->get('Accept-Encoding', ''), 'gzip')) {
            return false;
        }

        if ($response->headers->get('Content-Encoding')) {
            return false;
        }

        $type = $response->headers->get('Content-Type', '');
        $compressible = str_contains($type, 'text/html')
            || str_contains($type, 'application/json')
            || str_contains($type, 'text/plain')
            || str_contains($type, 'text/css')
            || str_contains($type, 'application/javascript');

        return $compressible && $response->getStatusCode() === 200;
    }
}
