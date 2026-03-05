<?php

namespace App\Http\Middleware;

use App\Features\ActivityLogs\Services\ActivityLogger;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function __construct(protected ActivityLogger $logger) {}

    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        if (! $request->user()) {
            return $response;
        }

        if ($this->shouldSkip($request, $response)) {
            return $response;
        }

        $action = $this->actionFromMethod($request->method());
        if (! $action) {
            return $response;
        }

        [$subjectType, $subjectId, $routeParameters] = $this->extractSubjectFromRoute($request);

        $routeName = $request->route()?->getName();
        $description = $routeName
            ? "{$action} {$routeName}"
            : "{$action} {$request->method()} {$request->path()}";

        $metadata = [
            'description' => $description,
            'route_name' => $routeName,
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'route_parameters' => $routeParameters,
            'query' => $request->query(),
            'input_keys' => array_keys($request->except([
                'password',
                'current_password',
                'password_confirmation',
                'new_password',
                'token',
            ])),
            'response_status' => $response->getStatusCode(),
        ];

        $this->logger->log(
            action: $action,
            actorUserId: (int) $request->user()->id,
            role: $request->user()->role ?? null,
            subjectType: $subjectType,
            subjectId: $subjectId,
            metadata: $metadata,
        );

        return $response;
    }

    protected function shouldSkip(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() >= 400) {
            return true;
        }

        $method = strtoupper($request->method());
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return true;
        }

        if ($request->is('up')) {
            return true;
        }

        if ($request->is('build/*') || $request->is('storage/*') || $request->is('favicon.ico')) {
            return true;
        }

        if ($request->is('login') || $request->is('logout')) {
            return true;
        }

        if ($request->is('ai-chatbot/chat')) {
            return true;
        }

        return false;
    }

    protected function actionFromMethod(string $method): ?string
    {
        return match (strtoupper($method)) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => null,
        };
    }

    /**
     * @return array{0: string|null, 1: int|null, 2: array<string, int|string|null>}
     */
    protected function extractSubjectFromRoute(Request $request): array
    {
        $route = $request->route();

        $normalized = [];
        $subjectType = null;
        $subjectId = null;

        foreach (($route?->parameters() ?? []) as $key => $value) {
            if ($value instanceof Model) {
                $normalized[$key] = (string) $value->getRouteKey();

                if (! $subjectType) {
                    $subjectType = class_basename($value);
                    $subjectId = is_numeric($value->getKey()) ? (int) $value->getKey() : null;
                }

                continue;
            }

            if (is_scalar($value) || $value === null) {
                $normalized[$key] = is_bool($value) ? (int) $value : (string) $value;

                if (! $subjectType && $key !== 'token' && is_numeric($value)) {
                    $subjectType = $key;
                    $subjectId = (int) $value;
                }
            }
        }

        return [$subjectType, $subjectId, $normalized];
    }
}
