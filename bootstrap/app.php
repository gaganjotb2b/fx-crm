<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuspendStatusMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies for ngrok/cloudflare
        $middleware->trustProxies(at: '*');
        
        // Yahan middleware register karo

        // 1️⃣  Route middleware (single use)
        $middleware->alias([
             'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'user-access' => \App\Http\Middleware\UserAccess::class,
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        'checkSetting' => \App\Http\Middleware\checkSetting::class,
        'client_controll' => \App\Http\Middleware\ClientControll::class,
        'demo_controll' => \App\Http\Middleware\DemoControll::class,
        'is_ib' => \App\Http\Middleware\IsIB::class,
        'url.check' => \App\Http\Middleware\UrlCheckMiddleware::class,
        'check.user.block.status' => \App\Http\Middleware\BlockCuserControllMiddleware::class,

        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,

        'mobile.app' => \App\Http\Middleware\MobileAppMiddleware::class,
        'validate.app.key' => \App\Http\Middleware\MobileAppKeyMiddleware::class,

        'crm.status' => \App\Http\Middleware\MobileCrmStatusKeyMiddleware::class,
        'suspend.status' => \App\Http\Middleware\SuspendStatusMiddleware::class,
        'unsuspend.status' => \App\Http\Middleware\UnSuspendStatusMiddleware::class,


        ]);

        // 2️⃣  Agar global chahiye to:
        // $middleware->append(SuspendStatusMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
