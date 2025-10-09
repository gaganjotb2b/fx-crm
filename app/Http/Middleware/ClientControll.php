<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientControll
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        
        // for http request
        if ($permission !== 'access') {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You dont have available permission to this request',
                    'error' => 'Access forbidden'
                ], 403);
            }else {
                return redirect('access-forbidden');
            }
            
        }
        return $next($request);
    }
    private function isApiRequest(Request $request)
    {
        if ($request->ajax()) {
            return true;
        }
        return $request->is('api/*');
    }
}
