<?php

use App\Http\Controllers\Store\AuthController as CustomerAuthController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Store Routes
|--------------------------------------------------------------------------
*/

// ── Store home & browsing ────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('store.home');
Route::get('/category/{category:slug}', [HomeController::class, 'category'])->name('store.category');
Route::get('/search', [HomeController::class, 'search'])->name('store.search');
Route::get('/products/{product:slug}', [StoreProductController::class, 'show'])->name('store.product');

// ── Cart (session — no auth needed) ─────────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear',  [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// ── API endpoints (no auth) ─────────────────────────────────────
Route::get('/api/check-coupon', function (Illuminate\Http\Request $request) {
    $code     = strtoupper($request->query('code', ''));
    $subtotal = (float) $request->query('subtotal', 0);

    $coupon = \App\Models\Coupon::where('code', $code)->first();

    if (!$coupon || !$coupon->isValid($subtotal)) {
        return response()->json([
            'valid'   => false,
            'message' => 'كود الخصم غير صحيح أو منتهي الصلاحية',
        ]);
    }

    $discount = $coupon->calculateDiscount($subtotal);

    return response()->json([
        'valid'    => true,
        'discount' => $discount,
        'message'  => 'تم تطبيق الخصم: -' . number_format($discount, 2) . ' ر.ق',
    ]);
})->name('api.check-coupon');

// ── Checkout ─────────────────────────────────────────────────────
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// ── Customer auth ─────────────────────────────────────────────────
Route::prefix('account')->name('store.')->group(function () {
    Route::get('login',    [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('login',   [CustomerAuthController::class, 'login'])->name('login.post');
    Route::get('register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register'])->name('register.post');
    Route::post('logout',   [CustomerAuthController::class, 'logout'])->name('logout');

    // ── Authenticated customer ────────────────────────────────────
    Route::middleware('customer')->group(function () {
        Route::get('profile',        [CustomerAuthController::class, 'profile'])->name('profile');
        Route::get('profile/edit',   [CustomerAuthController::class, 'editProfile'])->name('profile.edit');
        Route::put('profile',        [CustomerAuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('orders',         [CustomerAuthController::class, 'orders'])->name('orders');
    });
});

// ── Wishlist (requires customer auth) ────────────────────────────
Route::middleware('customer')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
});
