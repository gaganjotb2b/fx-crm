<?php

namespace App\Http\Middleware;

use App\Models\SoftwareSetting;
use Closure;
use Illuminate\Http\Request;

class UnSuspendStatusMiddleware
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
        $crm_status = SoftwareSetting::value('crm_status');
        if ($crm_status === 'active') {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
