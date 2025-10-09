<?php

namespace App\Http\Middleware;

use App\Models\SoftwareSetting;
use Closure;

use Illuminate\Http\Request;

class MobileAppMiddleware
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
        $app_status = SoftwareSetting::value('app_status');
        if ($app_status === 'block') {
            return response()->json([
                'status' => false,
                'message' => 'Mobile app suspended for this CRM'
            ], 403);
        }
        return $next($request);
    }
}
