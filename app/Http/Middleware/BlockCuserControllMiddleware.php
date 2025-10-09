<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockCuserControllMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        
        if ($request->user() && $request->user()->active_status === 1) {
            
            return $next($request);
        }

        // return response('Access denied. User is blocked.', 403);
        abort(403,'Access denied. User is blocked.');
    }
}
