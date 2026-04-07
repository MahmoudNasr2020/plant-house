<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('store.cart', ['cart' => $this->getCart()]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty'        => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = max(1, (int) $request->input('qty', 1));

        $cart = $this->getCart();
        $key  = $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] = min($cart[$key]['qty'] + $qty, $product->stock);
        } else {
            $cart[$key] = [
                'id'        => $product->id,
                'name'      => $product->name,
                'brand'     => $product->brand,
                'slug'      => $product->slug,
                'price'     => (float) $product->price,
                'image_url' => $product->image_url,
                'qty'       => min($qty, $product->stock),
                'stock'     => $product->stock,
            ];
        }

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'count'   => $this->cartCount($cart),
            'message' => 'تمت الإضافة للسلة!',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required'],
            'qty'        => ['required', 'integer', 'min:0'],
        ]);

        $cart = $this->getCart();
        $key  = $request->product_id;

        if ($request->qty <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['qty'] = min((int) $request->qty, $cart[$key]['stock']);
        }

        $this->saveCart($cart);

        return response()->json([
            'success'  => true,
            'count'    => $this->cartCount($cart),
            'subtotal' => $this->cartSubtotal($cart),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $cart = $this->getCart();
        unset($cart[$request->product_id]);
        $this->saveCart($cart);

        return response()->json([
            'success'  => true,
            'count'    => $this->cartCount($cart),
        ]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('ph_cart');
        return response()->json(['success' => true, 'count' => 0]);
    }

    public function count(): JsonResponse
    {
        return response()->json(['count' => $this->cartCount($this->getCart())]);
    }

    // ── Helpers ──────────────────────────────────────────────────
    private function getCart(): array
    {
        return session('ph_cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['ph_cart' => $cart]);
    }

    private function cartCount(array $cart): int
    {
        return array_sum(array_column($cart, 'qty'));
    }

    private function cartSubtotal(array $cart): float
    {
        return array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
    }
}
