<?php

namespace IndianIra\Http\Middleware;

use Closure;

class SuperAdminAlreadyLoggedIn
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
        if (auth()->check() && auth()->id() == 1) {
            return $next($request);
        }

        return redirect(route('admin.login'));
    }
}
