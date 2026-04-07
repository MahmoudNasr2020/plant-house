<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.layouts.partials.sidebar', function ($view) {
            $view->with([
                'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
                'todaySales'    => (float) Order::whereDate('created_at', today())->sum('total'),
            ]);
        });
    }
}
