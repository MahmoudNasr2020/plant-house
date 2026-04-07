<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session('ph_cart', []);

        if (empty($cart)) {
            return redirect()->route('store.home')->with('error', 'السلة فارغة!');
        }

        $shippingFee   = (float) Setting::get('shipping_fee', 15);
        $freeShipping  = (float) Setting::get('free_shipping_at', 200);
        $subtotal      = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));

        return view('store.checkout', [
            'cart'         => $cart,
            'subtotal'     => $subtotal,
            'shippingFee'  => $subtotal >= $freeShipping ? 0 : $shippingFee,
            'freeShipping' => $freeShipping,
            'customer'     => auth('customer')->user(),
        ]);
    }

    public function place(Request $request): RedirectResponse
    {
        $cart = session('ph_cart', []);

        if (empty($cart)) {
            return redirect()->route('store.home');
        }

        $request->validate([
            'first_name'     => ['required', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email'],
            'phone'          => ['required', 'string', 'max:30'],
            'city'           => ['required', 'string', 'max:100'],
            'address'        => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cash'],
            'shipping_type'  => ['required', 'in:fast,standard,international'],
            'coupon_code'    => ['nullable', 'string'],
        ]);

        $shippingFee  = (float) Setting::get('shipping_fee', 15);
        $freeAt       = (float) Setting::get('free_shipping_at', 200);
        $subtotal     = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));

        $shippingCost = match ($request->shipping_type) {
            'fast'          => $subtotal >= $freeAt ? 0 : 0,
            'standard'      => $shippingFee,
            'international' => 35,
        };

        $discountAmount = 0;
        $couponCode     = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $couponCode     = $coupon->code;
                $coupon->increment('usage_count');
            }
        }

        $total = max(0, $subtotal + $shippingCost - $discountAmount);

        $order = DB::transaction(function () use ($request, $cart, $subtotal, $shippingCost, $discountAmount, $total, $couponCode) {
            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'name'    => $request->first_name . ' ' . $request->last_name,
                    'phone'   => $request->phone,
                    'city'    => $request->city,
                    'address' => $request->address,
                ]
            );

            // Create order
            $order = Order::create([
                'customer_id'     => $customer->id,
                'subtotal'        => $subtotal,
                'shipping_fee'    => $shippingCost,
                'discount_amount' => $discountAmount,
                'total'           => $total,
                'payment_method'  => $request->payment_method,
                'status'          => 'pending',
                'city'            => $request->city,
                'address'         => $request->address,
                'coupon_code'     => $couponCode,
                'notes'           => $request->input('notes'),
            ]);

            // Create order items
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['id'],
                    'product_name'  => $item['name'],
                    'product_brand' => $item['brand'],
                    'unit_price'    => $item['price'],
                    'quantity'      => $item['qty'],
                    'subtotal'      => $item['price'] * $item['qty'],
                ]);

                if ($product) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            return $order;
        });

        session()->forget('ph_cart');

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order): View
    {
        $order->load('items');

        return view('store.checkout-success', compact('order'));
    }
}
