<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::latest()->paginate(20);

        return view('admin.discounts.index', [
            'coupons'       => $coupons,
            'totalCoupons'  => Coupon::count(),
            'activeCoupons' => Coupon::active()->count(),
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code'              => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type'              => ['required', 'in:percentage,fixed'],
            'value'             => ['required', 'numeric', 'min:0'],
            'usage_limit'       => ['nullable', 'integer', 'min:1'],
            'min_order_amount'  => ['nullable', 'numeric', 'min:0'],
            'expires_at'        => ['nullable', 'date', 'after:today'],
            'is_active'         => ['nullable', 'boolean'],
        ]);

        Coupon::create([
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'usage_limit'      => $request->usage_limit,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'تم إضافة الكوبون بنجاح!');
    }

    public function update(Request $request, Coupon $discount): RedirectResponse
    {
        $request->validate([
            'code'             => ['required', 'string', 'max:50', 'unique:coupons,code,' . $discount->id],
            'type'             => ['required', 'in:percentage,fixed'],
            'value'            => ['required', 'numeric', 'min:0'],
            'usage_limit'      => ['nullable', 'integer', 'min:1'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'expires_at'       => ['nullable', 'date'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        $discount->update([
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'usage_limit'      => $request->usage_limit,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'تم تحديث الكوبون بنجاح!');
    }

    public function destroy(Coupon $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'تم حذف الكوبون بنجاح!');
    }
}
