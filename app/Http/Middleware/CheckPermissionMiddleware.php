<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (auth()->check()) {
            $permissions = explode('|', $permission);
            foreach ($permissions as $perm) {
                if (auth()->user()->hasPermission($perm)) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
