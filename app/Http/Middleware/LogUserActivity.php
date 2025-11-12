<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $routeName = $request->route() ? $request->route()->getName() : 'N/A';
            $method = $request->method();
            $action = "{$method} - {$routeName}";

            // Avoid logging the logs page itself to prevent recursion
            if (!str_contains($request->path(), 'activities')) {
                UserActivity::create([
                    'user_id' => $user->id,
                    'action' => $action,
                    'model_type' => $request->route()->getActionName(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            }
        }

        return $response;
    }
}
