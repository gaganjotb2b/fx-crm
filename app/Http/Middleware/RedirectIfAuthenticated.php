<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (auth()->user()->type === "admin" || auth()->user()->type === "manager") {
                    return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
                } elseif (auth()->user()->type === "manager") {
                    return redirect(RouteServiceProvider::MANAGER_DASHBOARD);
                } elseif (auth()->user()->type === "trader") {
                    return redirect(RouteServiceProvider::TRADER_DASHBOARD);
                } elseif (auth()->user()->type === "ib") {
                    return redirect(RouteServiceProvider::IB_DASHBOARD);
                } elseif (auth()->user()->type === "system") {
                    return redirect(RouteServiceProvider::SYSTEM_DASHBOARD);
                } else {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
