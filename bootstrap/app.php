<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureCustomer;
use App\Http\Middleware\EnsurePermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/dashboard.php'));

            Route::middleware('web')
                ->group(base_path('routes/store.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'    => EnsureAdmin::class,
            'customer' => EnsureCustomer::class,
            'can.do'   => EnsurePermission::class,
        ]);

        // Redirect guests based on which guard they needed
        $middleware->redirectGuestsTo(function ($request) {
            // If trying to access dashboard routes → admin login
            if ($request->is('dashboard*')) {
                return route('admin.login');
            }
            // Otherwise → customer store login
            return route('store.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
