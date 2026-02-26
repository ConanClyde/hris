<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyIntegrationKey
{
    public function handle(Request $request, Closure $next): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $expected = (string) env('INTEGRATION_API_KEY', '');
        $provided = (string) $request->header('X-Integration-Key', '');

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
