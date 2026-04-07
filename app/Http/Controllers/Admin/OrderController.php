<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        $orders = Order::with('customer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'          => $orders,
            'searchQuery'     => $search,
            'statusFilter'    => $status,
            'totalOrders'     => Order::count(),
            'processingCount' => Order::where('status', 'processing')->count(),
            'shippingCount'   => Order::where('status', 'shipped')->count(),
            'deliveredCount'  => Order::where('status', 'delivered')->count(),
            'cancelledCount'  => Order::where('status', 'cancelled')->count(),
            'pendingOrders'   => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'      => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('customer', 'items.product');

        return view('admin.orders.show', [
            'order'         => $order,
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'تم حذف الطلب بنجاح!');
    }
}
