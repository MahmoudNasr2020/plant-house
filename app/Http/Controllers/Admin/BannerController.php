<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.banners.index', [
            'banners'       => $banners,
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type'        => ['required', 'in:hero,side'],
            'title'       => ['required', 'string', 'max:200'],
            'badge'       => ['nullable', 'string', 'max:100'],
            'subtitle'    => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'emoji'       => ['nullable', 'string', 'max:10'],
            'bg_from'     => ['nullable', 'string', 'max:20'],
            'bg_to'       => ['nullable', 'string', 'max:20'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        Banner::create([
            ...$request->only('type', 'title', 'badge', 'subtitle', 'description', 'button_text', 'button_link', 'emoji', 'sort_order'),
            'bg_from'   => $request->bg_from   ?? '#1a3a2a',
            'bg_to'     => $request->bg_to     ?? '#2d6a4f',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'تم إضافة البانر بنجاح!');
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $request->validate([
            'type'        => ['required', 'in:hero,side'],
            'title'       => ['required', 'string', 'max:200'],
            'badge'       => ['nullable', 'string', 'max:100'],
            'subtitle'    => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'emoji'       => ['nullable', 'string', 'max:10'],
            'bg_from'     => ['nullable', 'string', 'max:20'],
            'bg_to'       => ['nullable', 'string', 'max:20'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $banner->update([
            ...$request->only('type', 'title', 'badge', 'subtitle', 'description', 'button_text', 'button_link', 'emoji', 'sort_order'),
            'bg_from'   => $request->bg_from   ?? '#1a3a2a',
            'bg_to'     => $request->bg_to     ?? '#2d6a4f',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'تم تحديث البانر بنجاح!');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'تم حذف البانر بنجاح!');
    }
}
