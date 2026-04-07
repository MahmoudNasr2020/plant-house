<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products   = Product::with('category')->latest()->paginate(20);
        $categories = Category::active()->get();

        return view('admin.products.index', [
            'products'      => $products,
            'categories'    => $categories,
            'totalProducts' => Product::count(),
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('category', 'orderItems');

        return view('admin.products.show', [
            'product'       => $product,
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::disk('public')->url(
                $request->file('image')->store('products', 'public')
            );
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح!');
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($product->image_url);
            $data['image_url'] = Storage::disk('public')->url(
                $request->file('image')->store('products', 'public')
            );
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteStoredImage($product->image_url);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح!');
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (!$imageUrl || str_starts_with($imageUrl, 'http')) {
            return;
        }

        Storage::disk('public')->delete($imageUrl);
    }
}
