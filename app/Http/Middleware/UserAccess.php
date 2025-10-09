<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        if ($userType === "admin|manager|system") {
            $userType = explode('|', $userType);
            if (auth()->user()->type === "admin") {
                $userType = $userType[0];
            } elseif (auth()->user()->type === "manager") {
                $userType = $userType[1];
            }
            else {
                $userType = $userType[2];
            }
        }

        if ($userType === "trader|ib") {
            $userType = explode('|', $userType);
            if (auth()->user()->type === "trader") {
                $userType = $userType[0];
            } elseif (auth()->user()->type === "ib") {
                $userType = $userType[1];
            }
        }
        // if ($userType === "system|trader|ib|admin") {
        //     $userType = explode('|', $userType);
        //     if (auth()->user()->type === "trader") {
        //         $userType = $userType[0];
        //     } elseif (auth()->user()->type === "ib") {
        //         $userType = $userType[1];
        //     }
        // }
        if (auth()->user()->type == $userType) {
            return $next($request);
        } else {
            return redirect()->guest('/');
            // return response()->json(['You do not have permission to access for this page.']);
        }
    }
}
