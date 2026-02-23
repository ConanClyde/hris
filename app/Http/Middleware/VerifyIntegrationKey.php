<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIntegrationKey
{
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $expected = (string) config('services.integration_api_key', '');
        $provided = (string) $request->header('X-Integration-Key', '');

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
