<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners    = Banner::active()->get();
        $categories = Category::active()->withCount('products')->get();
        $featured   = Product::active()->with('category')->latest()->take(10)->get();
        $onSale     = Product::active()->onSale()->with('category')->take(10)->get();
        $coupons    = Coupon::active()->latest()->take(6)->get();
        $brands     = Product::active()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('store.home', compact('banners', 'categories', 'featured', 'onSale', 'coupons', 'brands'));
    }

    public function category(Request $request, Category $category): View
    {
        $query = Product::active()
            ->where('category_id', $category->id)
            ->with('category');

        $this->applySort($query, $request->input('sort'));

        $products = $query->paginate(20)->withQueryString();

        return view('store.category', compact('category', 'products'));
    }

    public function search(Request $request): View
    {
        $query = $request->input('q', '');

        $builder = Product::active()->with('category');

        if ($query !== '') {
            $builder->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('brand', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($request->filled('category')) {
            $builder->where('category_id', $request->input('category'));
        }

        if ($request->boolean('sale')) {
            $builder->onSale();
        }

        $this->applySort($builder, $request->input('sort'));

        $products = $builder->paginate(20)->withQueryString();

        return view('store.search', compact('query', 'products'));
    }

    private function applySort(Builder $query, ?string $sort): void
    {
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            'sale'       => $query->orderBy('discount', 'desc'),
            default      => $query->latest(),
        };
    }
}
