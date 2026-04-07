@extends('store.layouts.app')

@section('title', 'المفضلة')

@section('content')

<div style="max-width:1300px;margin:0 auto;padding:36px 24px 56px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <h1 style="font-size:26px;font-weight:900;color:var(--gd);display:flex;align-items:center;gap:10px">
            <i class="fa fa-heart" style="color:var(--red)"></i>
            المفضلة
            <span style="font-size:16px;color:#9aa89e;font-weight:600">({{ $wishlist->count() }})</span>
        </h1>
        <a href="{{ route('store.home') }}" style="background:#fff;color:var(--gd);border:2px solid var(--gp);padding:9px 18px;border-radius:50px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
            <i class="fa fa-home"></i> متابعة التسوق
        </a>
    </div>

    @if($wishlist->count() > 0)
        <div class="pgrid">
            @foreach($wishlist as $item)
                @if($item->product)
                    @php $item->product->isWishlisted = true; @endphp
                    @include('store.partials.product-card', ['product' => $item->product])
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:80px 24px;color:#9aa89e;background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08)">
            <div style="font-size:70px;margin-bottom:18px">💔</div>
            <h3 style="font-size:22px;font-weight:900;color:var(--gd);margin-bottom:8px">قائمتك فارغة</h3>
            <p style="margin-bottom:24px;font-size:14px">أضف منتجاتك المفضلة هنا لسهولة الوصول إليها لاحقاً</p>
            <a href="{{ route('store.home') }}" style="background:var(--gd);color:#fff;padding:13px 28px;border-radius:50px;font-size:14px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px">
                <i class="fa fa-shopping-bag"></i> تصفح المنتجات
            </a>
        </div>
    @endif

</div>

@endsection
