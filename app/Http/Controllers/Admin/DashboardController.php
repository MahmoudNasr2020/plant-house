<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $revenueByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueByMonth[] = [
                'label' => $month->format('M'),
                'value' => (float) Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total'),
            ];
        }

        return view('admin.dashboard', [
            'monthlySales'   => Order::whereMonth('created_at', now()->month)->sum('total'),
            'totalOrders'    => Order::count(),
            'totalCustomers' => Customer::count(),
            'avgOrderValue'  => Order::avg('total') ?? 0,
            'todaySales'     => Order::whereDate('created_at', today())->sum('total'),
            'pendingOrders'  => Order::whereIn('status', ['pending', 'processing'])->count(),
            'recentOrders'   => Order::with('customer')->latest()->take(8)->get(),
            'totalProducts'  => Product::count(),
            'lowStock'       => Product::where('stock', '<=', 5)->where('is_active', true)->count(),
            'revenueByMonth' => $revenueByMonth,
        ]);
    }

    public function reports(): View
    {
        // Only count non-cancelled orders toward revenue
        $base = Order::where('status', '!=', 'cancelled');

        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $lastMonth = now()->subMonth();

        return view('admin.reports', [
            'pendingOrders'  => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'     => (float) (clone $base)->whereDate('created_at', today())->sum('total'),
            'monthlySales'   => (float) (clone $base)->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)->sum('total'),
            'lastMonthSales' => (float) (clone $base)->whereYear('created_at', $lastMonth->year)
                ->whereMonth('created_at', $lastMonth->month)->sum('total'),
            'yearlySales'    => (float) (clone $base)->whereYear('created_at', now()->year)->sum('total'),
            'topProducts'    => $topProducts,
            'ordersByStatus' => $ordersByStatus,
            'totalOrders'    => Order::count(),
            'totalCustomers' => Customer::count(),
            'totalRevenue'   => (float) Order::where('status', 'delivered')->sum('total'),
            'avgOrderValue'  => (float) ($base->avg('total') ?? 0),
        ]);
    }

    public function settings(): View
    {
        return view('admin.settings', [
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function settingsUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'store_name'       => ['required', 'string', 'max:100'],
            'store_email'      => ['required', 'email'],
            'store_phone'      => ['nullable', 'string', 'max:30'],
            'currency'         => ['required', 'string', 'max:10'],
            'store_address'    => ['nullable', 'string', 'max:255'],
            'free_shipping_at' => ['nullable', 'numeric', 'min:0'],
            'shipping_fee'     => ['nullable', 'numeric', 'min:0'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_twitter'   => ['nullable', 'string', 'max:255'],
            'social_whatsapp'  => ['nullable', 'string', 'max:30'],
            'store_logo'       => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'store_favicon'    => ['nullable', 'file', 'mimes:png,ico,svg', 'max:512'],
        ]);

        Setting::setMany($request->only([
            'store_name', 'store_email', 'store_phone', 'currency',
            'store_address', 'free_shipping_at', 'shipping_fee',
            'social_instagram', 'social_twitter', 'social_whatsapp',
        ]));

        $this->handleBrandAsset($request, 'store_logo', 'remove_logo');
        $this->handleBrandAsset($request, 'store_favicon', 'remove_favicon');

        return redirect()->route('admin.settings')
            ->with('success', 'تم حفظ الإعدادات بنجاح!');
    }

    private function handleBrandAsset(Request $request, string $key, string $removeFlag): void
    {
        $current = Setting::get($key);

        if ($request->hasFile($key)) {
            $this->deleteBrandAsset($current);
            $path = $request->file($key)->store('branding', 'public');
            Setting::set($key, Storage::disk('public')->url($path));

            return;
        }

        if ($request->boolean($removeFlag) && $current) {
            $this->deleteBrandAsset($current);
            Setting::set($key, null);
        }
    }

    private function deleteBrandAsset(?string $url): void
    {
        if (! $url) {
            return;
        }

        $relative = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $relative = preg_replace('#^storage/#', '', $relative);

        if ($relative && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
