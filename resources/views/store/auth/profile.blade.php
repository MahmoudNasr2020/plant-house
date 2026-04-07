@extends('store.layouts.app')

@section('title', 'ملفي الشخصي')

@section('content')

<div style="max-width:1100px;margin:0 auto;padding:36px 24px 56px;display:grid;grid-template-columns:280px 1fr;gap:22px;align-items:start">

    {{-- Sidebar --}}
    <aside style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:24px;position:sticky;top:90px">
        <div style="text-align:center;margin-bottom:22px;padding-bottom:20px;border-bottom:2px solid var(--gf)">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,var(--gb),var(--gd));border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:30px;font-weight:900;color:#fff;margin-bottom:10px">
                {{ mb_substr($customer->name, 0, 1) }}
            </div>
            <div style="font-size:15px;font-weight:900;color:var(--gd);margin-bottom:2px">{{ $customer->name }}</div>
            <div style="font-size:12px;color:#9aa89e">{{ $customer->email }}</div>
        </div>

        <nav style="display:flex;flex-direction:column;gap:4px">
            <a href="{{ route('store.profile') }}" class="pnav on">
                <i class="fa fa-user"></i> معلومات حسابي
            </a>
            <a href="{{ route('store.orders') }}" class="pnav">
                <i class="fa fa-box"></i> طلباتي
            </a>
            <a href="{{ route('wishlist.index') }}" class="pnav">
                <i class="fa fa-heart"></i> المفضلة
            </a>
            <form method="POST" action="{{ route('store.logout') }}" style="margin-top:12px">
                @csrf
                <button type="submit" class="pnav" style="width:100%;background:none;border:none;font-family:inherit;cursor:pointer;text-align:right;color:var(--red)">
                    <i class="fa fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </form>
        </nav>

        <style>
            .pnav {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 11px 14px;
                border-radius: 10px;
                font-size: 13.5px;
                font-weight: 700;
                color: #2d4a3a;
                text-decoration: none;
                transition: .2s;
            }
            .pnav:hover { background: var(--gf); color: var(--gd); }
            .pnav.on { background: var(--gd); color: #fff; }
            .pnav i { width: 17px; font-size: 14px; }
        </style>
    </aside>

    {{-- Main --}}
    <main>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:10px">
            <h1 style="font-size:24px;font-weight:900;color:var(--gd)">
                <i class="fa fa-user" style="color:var(--gb);margin-left:8px"></i>
                معلومات الحساب
            </h1>
            <a href="{{ route('store.profile.edit') }}" style="background:var(--gd);color:#fff;padding:10px 20px;border-radius:50px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
                <i class="fa fa-edit"></i> تعديل البيانات
            </a>
        </div>

        @if(session('success'))
            <div style="background:#e8f7ef;border:2px solid var(--gp);color:var(--gd);padding:12px 16px;border-radius:12px;margin-bottom:16px;font-weight:700">
                <i class="fa fa-check-circle" style="color:var(--gb)"></i> {{ session('success') }}
            </div>
        @endif

        <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:26px;margin-bottom:22px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">الاسم</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->name }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">البريد الإلكتروني</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->email }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">الهاتف</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->phone ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">المدينة</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->city ?? '—' }}</div>
                </div>
                <div style="grid-column:1/-1">
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">العنوان</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->address ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:#9aa89e;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">عضو منذ</div>
                    <div style="font-size:15px;font-weight:800;color:var(--gd)">{{ $customer->created_at->format('M Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <h2 style="font-size:18px;font-weight:900;color:var(--gd);margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <i class="fa fa-box" style="color:var(--gb)"></i>
            آخر الطلبات
        </h2>

        @if($orders->count() > 0)
            <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);overflow:hidden">
                @foreach($orders as $order)
                    <div style="padding:16px 22px;border-bottom:1px solid var(--gf);display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap">
                        <div>
                            <div style="font-size:14px;font-weight:800;color:var(--gd);margin-bottom:3px">
                                طلب {{ $order->reference }}
                            </div>
                            <div style="font-size:12px;color:#9aa89e">
                                {{ $order->created_at->format('d M Y') }} • {{ $order->items->sum('quantity') }} منتج
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px">
                            @php
                                $statusColors = [
                                    'pending'    => ['bg'=>'#fff7e0','color'=>'#996600','label'=>'قيد الانتظار'],
                                    'processing' => ['bg'=>'#e0f0ff','color'=>'#004aa8','label'=>'قيد المعالجة'],
                                    'shipped'    => ['bg'=>'#f3f0ff','color'=>'#5e3fb5','label'=>'تم الشحن'],
                                    'delivered'  => ['bg'=>'#e6f9ee','color'=>'#1a7a45','label'=>'تم التسليم'],
                                    'cancelled'  => ['bg'=>'#fff0f0','color'=>'#c0392b','label'=>'ملغي'],
                                ];
                                $s = $statusColors[$order->status] ?? $statusColors['pending'];
                            @endphp
                            <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:11.5px;font-weight:700;padding:5px 11px;border-radius:50px">{{ $s['label'] }}</span>
                            <span style="font-size:15px;font-weight:900;color:var(--gd)">{{ number_format($order->total, 2) }} ر.ق</span>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($orders->hasPages())
                <div style="display:flex;justify-content:center;margin-top:22px">
                    {{ $orders->links('store.partials.pagination') }}
                </div>
            @endif
        @else
            <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:48px 22px;text-align:center;color:#9aa89e">
                <div style="font-size:50px;margin-bottom:12px">📦</div>
                <p style="font-size:14.5px;margin-bottom:18px">لا توجد طلبات بعد</p>
                <a href="{{ route('store.home') }}" style="background:var(--gd);color:#fff;padding:11px 22px;border-radius:50px;font-size:13.5px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
                    <i class="fa fa-shopping-bag"></i> ابدأ التسوق
                </a>
            </div>
        @endif
    </main>
</div>

<style>
    @media (max-width: 800px) {
        main.site-main > div { grid-template-columns: 1fr !important; }
    }
</style>

@endsection
