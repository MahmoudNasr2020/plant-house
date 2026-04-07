<aside class="admin-sidebar" id="adminSidebar">

    {{-- LOGO --}}
    <div class="sb-logo">
        <div style="display:flex;align-items:center;gap:9px;margin-bottom:4px">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--gb),var(--gd));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px">🌿</div>
            <div>
                <div class="sb-brand">Plant House</div>
                <div class="sb-sub">لوحة إدارة المتجر</div>
            </div>
        </div>
    </div>

    {{-- QUICK STAT --}}
    <div class="sb-stat">
        <div class="ss-lbl">مبيعات اليوم</div>
        <div class="ss-val">{{ number_format($todaySales ?? 3840) }} ر.ق</div>
        <div class="ss-sub">↑ 12% من أمس</div>
    </div>

    @php $authUser = auth()->user(); @endphp

    {{-- NAV: MAIN --}}
    <div class="sb-sec">القائمة</div>

    <a href="{{ route('admin.home') }}"
       class="si {{ request()->routeIs('admin.home') ? 'on' : '' }}">
        <i class="fa fa-tachometer-alt"></i> لوحة المراقبة
    </a>

    @if($authUser?->hasPermission('orders.view'))
        <a href="{{ route('admin.orders.index') }}"
           class="si {{ request()->routeIs('admin.orders.*') ? 'on' : '' }}">
            <i class="fa fa-shopping-bag"></i> الطلبات
            @if(($pendingOrders ?? 0) > 0)
                <span class="si-bdg">{{ $pendingOrders ?? 8 }}</span>
            @endif
        </a>
    @endif

    @if($authUser?->hasPermission('categories.manage'))
        <a href="{{ route('admin.categories.index') }}"
           class="si {{ request()->routeIs('admin.categories.*') ? 'on' : '' }}">
            <i class="fa fa-folder"></i> الأقسام
        </a>
    @endif

    @if($authUser?->hasPermission('products.manage'))
        <a href="{{ route('admin.products.index') }}"
           class="si {{ request()->routeIs('admin.products.*') ? 'on' : '' }}">
            <i class="fa fa-box"></i> المنتجات
        </a>
    @endif

    @if($authUser?->hasPermission('customers.view'))
        <a href="{{ route('admin.customers.index') }}"
           class="si {{ request()->routeIs('admin.customers.*') ? 'on' : '' }}">
            <i class="fa fa-users"></i> العملاء
        </a>
    @endif

    @if($authUser?->hasPermission('banners.manage'))
        <a href="{{ route('admin.banners.index') }}"
           class="si {{ request()->routeIs('admin.banners.*') ? 'on' : '' }}">
            <i class="fa fa-image"></i> البانرات
        </a>
    @endif

    {{-- NAV: MANAGEMENT --}}
    <div class="sb-sec">الإدارة</div>

    @if($authUser?->hasPermission('reports.view'))
        <a href="{{ route('admin.reports') }}"
           class="si {{ request()->routeIs('admin.reports') ? 'on' : '' }}">
            <i class="fa fa-chart-line"></i> التقارير
        </a>
    @endif

    @if($authUser?->hasPermission('discounts.manage'))
        <a href="{{ route('admin.discounts.index') }}"
           class="si {{ request()->routeIs('admin.discounts.*') ? 'on' : '' }}">
            <i class="fa fa-percentage"></i> الخصومات
        </a>
    @endif

    @if($authUser?->hasPermission('admins.manage'))
        <a href="{{ route('admin.admins.index') }}"
           class="si {{ request()->routeIs('admin.admins.*') ? 'on' : '' }}">
            <i class="fa fa-user-shield"></i> المديرون
        </a>
        <a href="{{ route('admin.roles.index') }}"
           class="si {{ request()->routeIs('admin.roles.*') ? 'on' : '' }}">
            <i class="fa fa-key"></i> الصلاحيات
        </a>
    @endif

    @if($authUser?->hasPermission('settings.manage'))
        <a href="{{ route('admin.settings') }}"
           class="si {{ request()->routeIs('admin.settings') ? 'on' : '' }}">
            <i class="fa fa-cog"></i> الإعدادات
        </a>
    @endif

    {{-- STORE LINK --}}
    <div class="sb-store">
        <a href="{{ route('store.home') }}" target="_blank" class="store-link">
            <i class="fa fa-store"></i>
            عرض المتجر
            <i class="fa fa-arrow-left" style="margin-right:auto;font-size:11px"></i>
        </a>
    </div>

</aside>

<style>
    /* ───── SIDEBAR ───── */
    .admin-sidebar {
        width: 255px;
        background: var(--gd);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .sb-logo {
        padding: 20px 18px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, .07);
    }

    .sb-brand { font-size: 17px; font-weight: 900; color: #fff; }
    .sb-sub   { font-size: 11px; color: var(--gp); margin-top: 2px; }

    .sb-stat {
        padding: 13px 16px;
        margin: 10px 10px 0;
        background: rgba(255, 255, 255, .05);
        border-radius: 11px;
    }

    .ss-lbl { font-size: 10.5px; color: rgba(255, 255, 255, .45); margin-bottom: 3px; }
    .ss-val { font-size: 19px; font-weight: 900; color: #fff; }
    .ss-sub { font-size: 11px; color: var(--gp); margin-top: 2px; }

    .sb-sec {
        padding: 5px 20px;
        font-size: 10.5px;
        font-weight: 800;
        color: rgba(255, 255, 255, .33);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 12px 0 3px;
    }

    .si {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 18px;
        color: rgba(255, 255, 255, .68);
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 600;
        transition: .2s;
        border-right: 3px solid transparent;
        margin: 1px 0;
        text-decoration: none;
    }

    .si:hover { background: rgba(255, 255, 255, .06); color: #fff; }
    .si.on    { background: rgba(255, 255, 255, .09); color: #fff; border-right-color: var(--gold); }
    .si i     { width: 17px; font-size: 14px; }

    .si-bdg {
        margin-right: auto;
        background: #e76f51;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        width: 19px;
        height: 19px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sb-store {
        margin-top: auto;
        padding: 14px 18px;
        border-top: 1px solid rgba(255, 255, 255, .07);
    }

    .store-link {
        display: flex;
        align-items: center;
        gap: 9px;
        color: var(--gp);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        padding: 9px;
        border-radius: 9px;
        transition: .2s;
        text-decoration: none;
    }

    .store-link:hover { background: rgba(255, 255, 255, .07); color: #fff; }

    @media (max-width: 900px) {
        .admin-sidebar { display: none; }
    }
</style>
