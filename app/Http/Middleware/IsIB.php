<?php

namespace App\Http\Middleware;

use App\Services\CombinedService;
use Closure;
use Illuminate\Http\Request;

class IsIB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     if (CombinedService::is_combined('client', auth()->user()->id) == false) {
    //         return redirect('access-forbidden');
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next)
    {
        if (CombinedService::is_combined()) {
            if (CombinedService::is_combined('client', auth()->user()->id) == false) {
                return redirect('access-forbidden');
            }
        }

        return $next($request);
    }

}
