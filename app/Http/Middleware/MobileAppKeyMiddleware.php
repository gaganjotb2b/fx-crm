<?php

namespace App\Http\Middleware;

use App\Models\SoftwareSetting;
use Closure;
use Illuminate\Http\Request;

class MobileAppKeyMiddleware
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
        $db_app_key = SoftwareSetting::value('app_key');
        $req_app_key = $request->route('app_key');
        if ($req_app_key !== $db_app_key) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid app key'
            ], 403);
        }
        return $next($request);
    }
}
