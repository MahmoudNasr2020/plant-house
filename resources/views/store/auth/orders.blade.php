@extends('store.layouts.app')

@section('title', 'طلباتي')

@section('content')

<div style="max-width:1100px;margin:0 auto;padding:36px 24px 56px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
        <h1 style="font-size:26px;font-weight:900;color:var(--gd);display:flex;align-items:center;gap:10px">
            <i class="fa fa-box" style="color:var(--gb)"></i>
            طلباتي
            <span style="font-size:16px;color:#9aa89e;font-weight:600">({{ $orders->total() }})</span>
        </h1>
        <a href="{{ route('store.profile') }}" style="background:#fff;color:var(--gd);border:2px solid var(--gp);padding:9px 18px;border-radius:50px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
            <i class="fa fa-user"></i> حسابي
        </a>
    </div>

    @if($orders->count() > 0)
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach($orders as $order)
                @php
                    $statusColors = [
                        'pending'    => ['bg'=>'#fff7e0','color'=>'#996600','label'=>'قيد الانتظار','ico'=>'clock'],
                        'processing' => ['bg'=>'#e0f0ff','color'=>'#004aa8','label'=>'قيد المعالجة','ico'=>'cog'],
                        'shipped'    => ['bg'=>'#f3f0ff','color'=>'#5e3fb5','label'=>'تم الشحن','ico'=>'truck'],
                        'delivered'  => ['bg'=>'#e6f9ee','color'=>'#1a7a45','label'=>'تم التسليم','ico'=>'check-circle'],
                        'cancelled'  => ['bg'=>'#fff0f0','color'=>'#c0392b','label'=>'ملغي','ico'=>'times-circle'],
                    ];
                    $s = $statusColors[$order->status] ?? $statusColors['pending'];
                @endphp

                <div style="background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(26,58,42,.08);overflow:hidden">
                    <div style="padding:16px 22px;border-bottom:2px solid var(--gf);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:11px">
                        <div>
                            <div style="font-size:15px;font-weight:900;color:var(--gd);margin-bottom:3px">
                                طلب {{ $order->reference }}
                            </div>
                            <div style="font-size:12px;color:#9aa89e">{{ $order->created_at->format('d M Y - h:i A') }}</div>
                        </div>
                        <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:12px;font-weight:700;padding:6px 14px;border-radius:50px;display:inline-flex;align-items:center;gap:6px">
                            <i class="fa fa-{{ $s['ico'] }}"></i> {{ $s['label'] }}
                        </span>
                    </div>

                    <div style="padding:14px 22px">
                        @foreach($order->items as $item)
                            <div style="display:flex;align-items:center;gap:12px;padding:8px 0">
                                <div style="width:42px;height:42px;background:var(--gf);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px">🌿</div>
                                <div style="flex:1">
                                    <div style="font-size:13.5px;font-weight:700;color:var(--gd)">{{ $item->product_name }}</div>
                                    <div style="font-size:11.5px;color:#9aa89e">× {{ $item->quantity }}</div>
                                </div>
                                <div style="font-size:13.5px;font-weight:800;color:var(--gd)">{{ number_format($item->subtotal, 2) }} ر.ق</div>
                            </div>
                        @endforeach
                    </div>

                    <div style="padding:14px 22px;border-top:1px solid var(--gf);background:#f8fdf9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                        <div style="font-size:12px;color:#9aa89e">
                            <i class="fa fa-map-marker-alt"></i> {{ $order->city }} — {{ Str::limit($order->address, 40) }}
                        </div>
                        <div style="font-size:17px;font-weight:900;color:var(--gd)">
                            المجموع: {{ number_format($order->total, 2) }} ر.ق
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($orders->hasPages())
            <div style="display:flex;justify-content:center;margin-top:28px">
                {{ $orders->links('store.partials.pagination') }}
            </div>
        @endif
    @else
        <div style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:72px 22px;text-align:center;color:#9aa89e">
            <div style="font-size:60px;margin-bottom:16px">📦</div>
            <h3 style="font-size:20px;font-weight:900;color:var(--gd);margin-bottom:8px">لا توجد طلبات بعد</h3>
            <p style="font-size:14px;margin-bottom:24px">ابدأ التسوق واستمتع بأفضل المنتجات!</p>
            <a href="{{ route('store.home') }}" style="background:var(--gd);color:#fff;padding:13px 28px;border-radius:50px;font-size:14px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px">
                <i class="fa fa-shopping-bag"></i> تصفح المنتجات
            </a>
        </div>
    @endif
</div>

@endsection
