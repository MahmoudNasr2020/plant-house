<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlist = auth('customer')->user()
            ->wishlist()
            ->with('product.category')
            ->get();

        return view('store.wishlist', compact('wishlist'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate(['product_id' => ['required', 'exists:products,id']]);

        $customerId = auth('customer')->id();
        $productId  = $request->product_id;

        $existing = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
        } else {
            Wishlist::create(['customer_id' => $customerId, 'product_id' => $productId]);
            $inWishlist = true;
        }

        $count = Wishlist::where('customer_id', $customerId)->count();

        return response()->json(['in_wishlist' => $inWishlist, 'count' => $count]);
    }
}
