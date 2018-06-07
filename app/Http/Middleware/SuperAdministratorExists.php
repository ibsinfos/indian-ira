<?php

namespace IndianIra\Http\Middleware;

use Closure;

class SuperAdministratorExists
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
        if (cache('superAdminExists') == true || \IndianIra\User::first() != null) {
            return $next($request);
        }

        return redirect(route('admin.generate'));
    }
}
