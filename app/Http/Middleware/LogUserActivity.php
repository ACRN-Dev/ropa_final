<?php

namespace App\Http\Middleware;

use App\Models\UserActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Only these HTTP method + route name combinations get logged.
     * GET requests are NEVER logged except for specific "viewed" routes below.
     */
    private array $watchedRoutes = [
        // Auth
        'login'                    => ['action' => 'login',   'model' => 'auth'],
        'logout'                   => ['action' => 'logout',  'model' => 'auth'],

        // ROPA — write actions only
        'ropa.store'               => ['action' => 'created', 'model' => 'ropa'],
        'ropa.update'              => ['action' => 'updated', 'model' => 'ropa'],
        'ropa.destroy'             => ['action' => 'deleted', 'model' => 'ropa'],

        // Users — write actions only
        'admin.users.store'        => ['action' => 'created', 'model' => 'user'],
        'admin.users.update'       => ['action' => 'updated', 'model' => 'user'],
        'admin.users.toggleStatus' => ['action' => 'updated', 'model' => 'user'],

        // Risk register — write actions only
        'risk-register.store'      => ['action' => 'created', 'model' => 'risk_register'],
        'risk-register.update'     => ['action' => 'updated', 'model' => 'risk_register'],
        'risk-register.destroy'    => ['action' => 'deleted', 'model' => 'risk_register'],

        // Reviews — write actions only
        'admin.reviews.update'     => ['action' => 'updated', 'model' => 'review'],
        'admin.reviews.destroy'    => ['action' => 'deleted', 'model' => 'review'],
    ];

    /**
     * These GET routes log a "viewed" event — only specific record views,
     * NOT index/list pages.
     */
    private array $viewedRoutes = [
        'ropa.show',
        'admin.ropa.show',
        'admin.users.show',
        'risk-register.show',
        'admin.reviews.show',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users
        if (!auth()->check()) {
            return $response;
        }

        // Only log successful responses (ignore redirects from GET, 4xx, 5xx)
        $status = $response->getStatusCode();
        if ($status >= 400) {
            return $response;
        }

        $routeName = $request->route()?->getName();
        $method    = $request->method();

        $action      = null;
        $model       = null;
        $modelId     = null;
        $description = null;

        // ── Check watched write routes (POST, PUT, PATCH, DELETE) ──
        if ($routeName && isset($this->watchedRoutes[$routeName]) && $method !== 'GET') {
            $meta        = $this->watchedRoutes[$routeName];
            $action      = $meta['action'];
            $model       = $meta['model'];
            $modelId     = $this->resolveModelId($routeName, $request);
            $description = $this->buildDescription($action, $model, $modelId, $request);
        }

        // ── Check viewed routes (GET on specific show pages) ──
        elseif ($routeName && in_array($routeName, $this->viewedRoutes) && $method === 'GET') {
            $action      = 'viewed';
            $model       = $this->modelFromRoute($routeName);
            $modelId     = $this->resolveModelId($routeName, $request);
            $description = $this->buildDescription('viewed', $model, $modelId, $request);
        }

        // ── Detect login/logout by URL when route name not matched ──
        elseif ($method === 'POST' && str_contains($request->path(), 'login') && $status < 400) {
            $action      = 'login';
            $model       = 'auth';
            $description = (auth()->user()->name ?? 'Unknown') . ' logged in';
        }
        elseif ($method === 'POST' && str_contains($request->path(), 'logout')) {
            $action      = 'logout';
            $model       = 'auth';
            $description = (auth()->user()->name ?? 'Unknown') . ' logged out';
        }

        // ── Only write to DB if we matched something ──
        if ($action) {
            UserActivity::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'model'       => $model,
                'model_id'    => $modelId,
                'description' => $description,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        return $response;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function resolveModelId(string $routeName, Request $request): ?int
    {
        // Try common route parameter names
        foreach (['ropa', 'user', 'risk_register', 'review', 'id'] as $param) {
            $val = $request->route($param);
            if ($val !== null) {
                return is_object($val) ? $val->id : (int) $val;
            }
        }
        return null;
    }

    private function modelFromRoute(string $routeName): string
    {
        if (str_contains($routeName, 'ropa'))          return 'ropa';
        if (str_contains($routeName, 'user'))          return 'user';
        if (str_contains($routeName, 'risk'))          return 'risk_register';
        if (str_contains($routeName, 'review'))        return 'review';
        return 'record';
    }

    private function buildDescription(string $action, ?string $model, ?int $modelId, Request $request): string
    {
        $user      = auth()->user()->name ?? 'Unknown';
        $modelName = $model ? ucfirst(str_replace('_', ' ', $model)) : 'record';
        $idPart    = $modelId ? " #$modelId" : '';

        return match($action) {
            'created' => "$user created a new $modelName",
            'updated' => "$user updated $modelName$idPart",
            'deleted' => "$user deleted $modelName$idPart",
            'viewed'  => "$user viewed $modelName$idPart",
            'login'   => "$user logged in",
            'logout'  => "$user logged out",
            default   => "$user performed $action on $modelName$idPart",
        };
    }
}