<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes  /dashboard
|--------------------------------------------------------------------------
*/

// ── Auth (no middleware) ────────────────────────────────────────
Route::prefix('dashboard')->name('admin.')->group(function () {
    Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// ── Protected dashboard ─────────────────────────────────────────
Route::prefix('dashboard')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // My profile (any authenticated admin user)
    Route::get('profile',  [AdminAuthController::class, 'editProfile'])->name('profile');
    Route::put('profile',  [AdminAuthController::class, 'updateProfile'])->name('profile.update');

    // Orders — view requires orders.view, mutations require orders.edit / delete
    Route::get('orders',              [OrderController::class, 'index'])->middleware('can.do:orders.view')->name('orders.index');
    Route::get('orders/{order}',      [OrderController::class, 'show'])->middleware('can.do:orders.view')->name('orders.show');
    Route::put('orders/{order}',      [OrderController::class, 'update'])->middleware('can.do:orders.edit')->name('orders.update');
    Route::patch('orders/{order}',    [OrderController::class, 'update'])->middleware('can.do:orders.edit');
    Route::delete('orders/{order}',   [OrderController::class, 'destroy'])->middleware('can.do:orders.delete')->name('orders.destroy');

    // Categories
    Route::resource('categories', CategoryController::class)->middleware('can.do:categories.manage');

    // Products — manage for CRUD, delete for destroy
    Route::get('products',                   [ProductController::class, 'index'])->middleware('can.do:products.manage')->name('products.index');
    Route::get('products/create',            [ProductController::class, 'create'])->middleware('can.do:products.manage')->name('products.create');
    Route::post('products',                  [ProductController::class, 'store'])->middleware('can.do:products.manage')->name('products.store');
    Route::get('products/{product}/edit',    [ProductController::class, 'edit'])->middleware('can.do:products.manage')->name('products.edit');
    Route::put('products/{product}',         [ProductController::class, 'update'])->middleware('can.do:products.manage')->name('products.update');
    Route::patch('products/{product}',       [ProductController::class, 'update'])->middleware('can.do:products.manage');
    Route::get('products/{product}',         [ProductController::class, 'show'])->middleware('can.do:products.manage')->name('products.show');
    Route::delete('products/{product}',      [ProductController::class, 'destroy'])->middleware('can.do:products.delete')->name('products.destroy');

    // Customers (read-only permission controls whole resource)
    Route::resource('customers', CustomerController::class)->middleware('can.do:customers.view');

    // Discounts / Coupons
    Route::resource('discounts', DiscountController::class)->middleware('can.do:discounts.manage');

    // Banners
    Route::resource('banners', BannerController::class)->middleware('can.do:banners.manage');

    // Admins (super_admin only via admins.manage)
    Route::resource('admins', AdminController::class)->middleware('can.do:admins.manage');

    // Roles (super_admin only via admins.manage)
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit'])->middleware('can.do:admins.manage');

    // Reports
    Route::get('reports', [DashboardController::class, 'reports'])->middleware('can.do:reports.view')->name('reports');

    // Settings (super_admin only)
    Route::get('settings',  [DashboardController::class, 'settings'])->middleware('can.do:settings.manage')->name('settings');
    Route::post('settings', [DashboardController::class, 'settingsUpdate'])->middleware('can.do:settings.manage')->name('settings.update');
});
