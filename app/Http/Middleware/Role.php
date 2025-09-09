<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $roleName)
    // {
    //     if (!$request->user() || !$request->user()->role || $request->user()->role->role !== $roleName) {
    //         abort(403, 'Unauthorized');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, $roleName)
    {
        if (!$request->user() || $request->user()->role !== $roleName) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
