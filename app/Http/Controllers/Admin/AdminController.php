<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.home')
                ->with('error', 'ليس لديك صلاحية الوصول لهذه الصفحة.');
        }

        return view('admin.admins.index', [
            'admins'        => User::latest()->get(),
            'roles'         => Role::ordered()->get(),
            'pendingOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'todaySales'    => Order::whereDate('created_at', today())->sum('total'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role'     => ['required', Rule::in(Role::pluck('key')->toArray())],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إضافة المدير بنجاح!');
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email,' . $admin->id],
            'role'     => ['required', Rule::in(Role::pluck('key')->toArray())],
            'password' => ['nullable', 'min:8'],
        ]);

        $data = $request->only('name', 'email', 'role');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث المدير بنجاح!');
    }

    public function destroy(User $admin): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف المدير بنجاح!');
    }
}
