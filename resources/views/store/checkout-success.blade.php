@extends('store.layouts.app')

@section('title', 'تم الطلب بنجاح')

@section('content')

<div style="max-width:720px;margin:0 auto;padding:48px 24px">

    {{-- Success Animation --}}
    <div style="text-align:center;margin-bottom:32px">
        <div style="width:100px;height:100px;background:var(--gf);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:18px;animation:popIn .5s ease-out">
            <i class="fa fa-check-circle" style="font-size:56px;color:var(--gb)"></i>
        </div>
        <h1 style="font-size:30px;font-weight:900;color:var(--gd);margin-bottom:8px">تم استلام طلبك بنجاح!</h1>
        <p style="font-size:15px;color:#9aa89e">شكراً لك على الطلب، سنتواصل معك قريباً لتأكيد الشحن</p>
    </div>

    {{-- Order Number Card --}}
    <div style="background:linear-gradient(135deg,var(--gd),var(--gm));border-radius:18px;padding:28px;text-align:center;color:#fff;margin-bottom:24px">
        <div style="font-size:12px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">رقم الطلب</div>
        <div style="font-size:32px;font-weight:900;margin-bottom:4px">{{ $order->reference }}</div>
        <div style="font-size:13px;color:rgba(255,255,255,.75)">{{ $order->created_at->format('d M Y - h:i A') }}</div>
    </div>

    {{-- Order Details --}}
    <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);overflow:hidden;margin-bottom:18px">
        <div style="padding:18px 22px;border-bottom:2px solid var(--gf)">
            <h3 style="font-size:15px;font-weight:900;color:var(--gd);display:flex;align-items:center;gap:8px">
                <i class="fa fa-box" style="color:var(--gb)"></i> تفاصيل الطلب
            </h3>
        </div>

        @foreach($order->items as $item)
            <div style="display:flex;align-items:center;gap:14px;padding:14px 22px;border-bottom:1px solid var(--gf)">
                <div style="width:54px;height:54px;background:var(--gf);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:22px">🌿</div>
                <div style="flex:1">
                    <div style="font-size:14px;font-weight:800;color:var(--gd);margin-bottom:2px">{{ $item->product_name }}</div>
                    <div style="font-size:11.5px;color:#9aa89e">{{ $item->product_brand }} × {{ $item->quantity }}</div>
                </div>
                <div style="font-size:14px;font-weight:800;color:var(--gd)">{{ number_format($item->subtotal, 2) }} ر.ق</div>
            </div>
        @endforeach

        <div style="padding:18px 22px">
            <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#9aa89e;margin-bottom:8px">
                <span>المجموع الفرعي</span>
                <span style="color:var(--gd);font-weight:700">{{ number_format($order->subtotal, 2) }} ر.ق</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#9aa89e;margin-bottom:8px">
                <span>الشحن</span>
                <span style="color:var(--gd);font-weight:700">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 2) . ' ر.ق' : 'مجاني 🎉' }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#9aa89e;margin-bottom:8px">
                    <span>الخصم{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}</span>
                    <span style="color:var(--gb);font-weight:700">-{{ number_format($order->discount_amount, 2) }} ر.ق</span>
                </div>
            @endif
            <hr style="border:none;border-top:2px solid var(--gf);margin:12px 0">
            <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:900;color:var(--gd)">
                <span>الإجمالي</span>
                <span>{{ number_format($order->total, 2) }} ر.ق</span>
            </div>
        </div>
    </div>

    {{-- Shipping Info --}}
    <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:22px;margin-bottom:18px">
        <h3 style="font-size:15px;font-weight:900;color:var(--gd);margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <i class="fa fa-truck" style="color:var(--gb)"></i> معلومات التوصيل
        </h3>
        <div style="display:flex;flex-direction:column;gap:9px;font-size:13.5px;color:#2d4a3a">
            <div><strong style="color:#9aa89e;font-weight:600">المدينة:</strong> {{ $order->city }}</div>
            <div><strong style="color:#9aa89e;font-weight:600">العنوان:</strong> {{ $order->address }}</div>
            <div><strong style="color:#9aa89e;font-weight:600">طريقة الدفع:</strong>
                @switch($order->payment_method)
                    @case('card')      💳 بطاقة ائتمان @break
                    @case('apple_pay') 🍎 Apple Pay @break
                    @case('knet')      🏦 KNET @break
                    @case('cash')      💵 الدفع عند التسليم @break
                @endswitch
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:11px;flex-wrap:wrap">
        <a href="{{ route('store.home') }}" style="flex:1;background:var(--gd);color:#fff;padding:14px 24px;border-radius:50px;font-size:14px;font-weight:800;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;min-width:180px">
            <i class="fa fa-home"></i> العودة للمتجر
        </a>
        @auth('customer')
            <a href="{{ route('store.orders') }}" style="flex:1;background:#fff;color:var(--gd);border:2px solid var(--gp);padding:12px 24px;border-radius:50px;font-size:14px;font-weight:700;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;min-width:180px">
                <i class="fa fa-list"></i> طلباتي
            </a>
        @endauth
    </div>
</div>

<style>
    @keyframes popIn {
        0%   { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

@endsection
