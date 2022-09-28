<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // user:keyword that register in kernel
        if (auth()->user() === null) {
            abort(403, 'Unauthorized action.');
        }
        $actions = request()->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : Null;

        if (auth()->user()->hasAnyRole($roles) && $roles !== Null) {
            return $next($request);
        }


        abort(403, 'Unauthorized action.');
    }
}
