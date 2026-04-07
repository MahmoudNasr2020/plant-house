<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public static function allPermissions(): array
    {
        return [
            'orders.view'        => '👁️ عرض الطلبات',
            'orders.edit'        => '✏️ تعديل حالة الطلبات',
            'orders.delete'      => '🗑️ حذف الطلبات',
            'products.manage'    => '📦 إدارة المنتجات (إضافة/تعديل)',
            'products.delete'    => '🗑️ حذف المنتجات',
            'categories.manage'  => '📁 إدارة الأقسام',
            'customers.view'     => '👥 عرض العملاء',
            'discounts.manage'   => '🏷️ إدارة الخصومات',
            'banners.manage'     => '🖼️ إدارة البانرات',
            'admins.manage'      => '👤 إدارة المديرين',
            'settings.manage'    => '⚙️ إدارة الإعدادات',
            'reports.view'       => '📊 عرض التقارير',
        ];
    }

    public function index(): View
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $roles = Role::ordered()->get();
        $permissions = self::allPermissions();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $data = $request->validate([
            'key'         => ['required', 'string', 'max:60', 'unique:roles,key', 'regex:/^[a-z_]+$/'],
            'label'       => ['required', 'string', 'max:100'],
            'emoji'       => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(self::allPermissions()))],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $data['permissions'] = $data['permissions'] ?? [];
        $data['is_system']   = false;

        Role::create($data);

        return back()->with('success', 'تم إضافة الصلاحية بنجاح');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $data = $request->validate([
            'label'       => ['required', 'string', 'max:100'],
            'emoji'       => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(self::allPermissions()))],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        // super_admin always has all permissions
        if ($role->key === 'super_admin') {
            $data['permissions'] = array_keys(self::allPermissions());
        } else {
            $data['permissions'] = $data['permissions'] ?? [];
        }

        $role->update($data);

        return back()->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        if ($role->is_system) {
            return back()->with('error', 'لا يمكن حذف صلاحية أساسية من النظام');
        }

        if (User::where('role', $role->key)->exists()) {
            return back()->with('error', 'لا يمكن حذف صلاحية مستخدمة من قبل مديرين');
        }

        $role->delete();

        return back()->with('success', 'تم حذف الصلاحية بنجاح');
    }
}
