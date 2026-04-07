<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::withCount('orders')->latest()->paginate(20);

        return view('admin.customers.index', [
            'customers'      => $customers,
            'totalCustomers' => Customer::count(),
            'pendingOrders'  => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'     => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function show(Customer $customer): View
    {
        $orders = $customer->orders()->latest()->paginate(10);

        return view('admin.customers.show', [
            'customer'      => $customer,
            'orders'        => $orders,
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        Customer::create($request->only('name', 'email', 'phone'));

        return redirect()->route('admin.customers.index')
            ->with('success', 'تم إضافة العميل بنجاح!');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'تم حذف العميل بنجاح!');
    }
}
