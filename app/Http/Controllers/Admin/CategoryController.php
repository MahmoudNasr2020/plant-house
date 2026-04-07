<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->paginate(20);

        return view('admin.categories.index', [
            'categories'    => $categories,
            'totalCategories' => Category::count(),
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إضافة القسم بنجاح!');
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث القسم بنجاح!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف القسم بنجاح!');
    }
}
