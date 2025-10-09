<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\checkSettingsService;

class checkSetting
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $keywords
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $keywords)
    {
        $checkSettings = new checkSettingsService();
        if ($checkSettings->TraderSettings($keywords)) {
            return $next($request);
        }
        return redirect('/');
    }
}
