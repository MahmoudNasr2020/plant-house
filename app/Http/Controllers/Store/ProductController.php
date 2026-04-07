<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_if(!$product->is_active, 404);

        $product->load('category');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();

        $isWishlisted = false;
        if (auth('customer')->check()) {
            $isWishlisted = Wishlist::where('customer_id', auth('customer')->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('store.product', compact('product', 'related', 'isWishlisted'));
    }
}
