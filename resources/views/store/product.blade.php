@extends('store.layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .prod-wrap {
        max-width: 1300px;
        margin: 0 auto;
        padding: 32px 24px 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #9aa89e;
        margin-top: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .breadcrumb a { color: #9aa89e; text-decoration: none; }
    .breadcrumb a:hover { color: var(--gd); }
    .breadcrumb i { font-size: 10px; }

    .prod-grid {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 40px;
        margin-bottom: 48px;
    }

    /* Image section */
    .prod-img-wrap {
        background: var(--gf);
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
    }

    .prod-img { width: 100%; height: 100%; object-fit: contain; }

    .prod-badge-big {
        position: absolute;
        top: 16px;
        right: 16px;
        background: var(--red);
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 50px;
    }

    /* Info section */
    .prod-info { display: flex; flex-direction: column; }

    .prod-cat-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gf);
        color: var(--gb);
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        margin-bottom: 12px;
        width: fit-content;
        text-decoration: none;
    }

    .prod-name {
        font-size: 28px;
        font-weight: 900;
        color: var(--gd);
        line-height: 1.25;
        margin-bottom: 6px;
    }

    .prod-brand {
        font-size: 14px;
        color: #9aa89e;
        margin-bottom: 18px;
        font-weight: 600;
    }

    .prod-price-wrap {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 22px;
    }

    .prod-price {
        font-size: 38px;
        font-weight: 900;
        color: var(--gd);
    }

    .prod-price span { font-size: 16px; color: #9aa89e; font-weight: 600; }
    .prod-price-old { font-size: 18px; color: #9aa89e; text-decoration: line-through; }

    .stock-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        margin-bottom: 24px;
        align-self: flex-start;
        width: fit-content;
    }

    .in-stock  { background: #e6f9ee; color: #1a7a45; }
    .out-stock { background: #fff0f0; color: #c0392b; }
    .low-stock { background: #fff7e0; color: #996600; }

    .qty-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
        align-self: flex-start;
        width: 100%;
        max-width: 420px;
    }

    .qty-ctrl {
        display: flex;
        align-items: center;
        border: 2px solid var(--gp);
        border-radius: 12px;
        overflow: hidden;
    }

    .qty-ctrl button {
        width: 40px;
        height: 44px;
        background: #f8fdf9;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
        color: var(--gd);
        transition: .2s;
    }

    .qty-ctrl button:hover { background: var(--gf); }

    .qty-ctrl input {
        width: 52px;
        text-align: center;
        border: none;
        font-family: inherit;
        font-size: 16px;
        font-weight: 800;
        color: var(--gd);
        outline: none;
        background: #fff;
        padding: 0;
    }

    .btn-buy {
        flex: 0 1 auto;
        min-width: 200px;
        background: var(--gd);
        color: #fff;
        border: none;
        padding: 13px 28px;
        border-radius: 50px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: .2s;
    }

    .btn-buy:hover { background: var(--gb); }
    .btn-buy:disabled { background: #ccc; cursor: not-allowed; }

    .btn-wish-lg {
        width: 50px;
        height: 50px;
        border: 2px solid var(--gp);
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #9aa89e;
        cursor: pointer;
        transition: .2s;
        flex-shrink: 0;
    }

    .btn-wish-lg:hover, .btn-wish-lg.on { color: var(--red); border-color: var(--red); }

    .divider { border: none; border-top: 2px solid var(--gf); margin: 20px 0; }

    .prod-meta { display: flex; flex-direction: column; gap: 9px; }

    .meta-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
    }

    .meta-row .mk { color: #9aa89e; font-weight: 600; width: 100px; flex-shrink: 0; }
    .meta-row .mv { color: var(--gd); font-weight: 700; }

    /* Description */
    .prod-desc-section {
        max-width: 1300px;
        margin: 0 auto 44px;
        padding: 0 24px;
    }

    .tabs {
        display: flex;
        border-bottom: 2px solid var(--gf);
        margin-bottom: 24px;
        gap: 0;
    }

    .tab-btn {
        padding: 12px 22px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        color: #9aa89e;
        background: none;
        border: none;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: .2s;
    }

    .tab-btn:hover { color: var(--gd); }
    .tab-btn.on { color: var(--gd); border-bottom-color: var(--gold); }

    .tab-content { display: none; }
    .tab-content.on { display: block; }

    .desc-text {
        font-size: 14.5px;
        line-height: 1.85;
        color: #2d4a3a;
        white-space: pre-line;
    }

    /* Related */
    .related-section {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 24px 52px;
    }

    @media (max-width: 850px) {
        .prod-grid { grid-template-columns: 1fr; gap: 24px; }
        .prod-name { font-size: 22px; }
        .prod-price { font-size: 30px; }
    }
</style>
@endpush

@section('content')

<div class="prod-wrap">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('store.home') }}">الرئيسية</a>
        <i class="fa fa-chevron-left"></i>
        @if($product->category)
            <a href="{{ route('store.category', $product->category->slug) }}">{{ $product->category->name }}</a>
            <i class="fa fa-chevron-left"></i>
        @endif
        <span style="color:var(--gd)">{{ $product->name }}</span>
    </nav>

    {{-- Product Main --}}
    <div class="prod-grid">

        {{-- Image --}}
        <div class="prod-img-wrap">
            @if($product->discount > 0)
                <div class="prod-badge-big">-{{ $product->discount }}%</div>
            @elseif($product->badge)
                <div class="prod-badge-big" style="background:var(--gold);color:var(--gd)">{{ $product->badge }}</div>
            @endif
            <img class="prod-img"
                 src="{{ $product->image_url ?: 'https://placehold.co/500x500/d8f3dc/1a3a2a?text=🌿' }}"
                 alt="{{ $product->name }}">
        </div>

        {{-- Info --}}
        <div class="prod-info">
            @if($product->category)
                <a href="{{ route('store.category', $product->category->slug) }}" class="prod-cat-tag">
                    {{ $product->category->emoji }} {{ $product->category->name }}
                </a>
            @endif

            <h1 class="prod-name">{{ $product->name }}</h1>
            <p class="prod-brand">{{ $product->brand }}</p>

            <div class="prod-price-wrap">
                <div class="prod-price">
                    {{ number_format($product->price, 2) }}
                    <span>ر.ق</span>
                </div>
                @if($product->old_price)
                    <div class="prod-price-old">{{ number_format($product->old_price, 2) }} ر.ق</div>
                @endif
            </div>

            @if($product->stock <= 0)
                <div class="stock-tag out-stock"><i class="fa fa-times-circle"></i> نفذ من المخزون</div>
            @elseif($product->stock <= 5)
                <div class="stock-tag low-stock"><i class="fa fa-exclamation-triangle"></i> كمية محدودة ({{ $product->stock }} متبقي)</div>
            @else
                <div class="stock-tag in-stock"><i class="fa fa-check-circle"></i> متوفر في المخزون</div>
            @endif

            {{-- Qty + Add to cart --}}
            @if($product->stock > 0)
                <div class="qty-row">
                    <div class="qty-ctrl">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" id="qtyInput" value="1" min="1" max="{{ $product->stock }}" readonly>
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                    <button class="btn-buy" onclick="addToCartQty({{ $product->id }})">
                        <i class="fa fa-shopping-cart"></i> أضف للسلة
                    </button>
                    @auth('customer')
                        <button class="btn-wish-lg {{ $isWishlisted ? 'on' : '' }}"
                                onclick="toggleWishlist(this, {{ $product->id }})">
                            <i class="fa fa-heart"></i>
                        </button>
                    @endauth
                </div>
            @else
                <button class="btn-buy" disabled>
                    <i class="fa fa-times"></i> نفذ من المخزون
                </button>
            @endif

            <hr class="divider">

            <div class="prod-meta">
                @if($product->rating > 0)
                    <div class="meta-row">
                        <span class="mk">التقييم:</span>
                        <span class="mv">⭐ {{ $product->rating }}/5 ({{ $product->reviews_count ?? 0 }} تقييم)</span>
                    </div>
                @endif
                <div class="meta-row">
                    <span class="mk">الماركة:</span>
                    <span class="mv">{{ $product->brand }}</span>
                </div>
                @if($product->category)
                    <div class="meta-row">
                        <span class="mk">القسم:</span>
                        <span class="mv">{{ $product->category->emoji }} {{ $product->category->name }}</span>
                    </div>
                @endif
                <div class="meta-row">
                    <span class="mk">الشحن:</span>
                    <span class="mv" style="color:var(--gb)">
                        <i class="fa fa-truck"></i>
                        شحن مجاني فوق {{ \App\Models\Setting::get('free_shipping_at', 200) }} ر.ق
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Description Tabs --}}
@if($product->description)
<div class="prod-desc-section">
    <div class="tabs">
        <button class="tab-btn on" onclick="switchTab(this, 'desc')">وصف المنتج</button>
        <button class="tab-btn" onclick="switchTab(this, 'ship')">الشحن والإرجاع</button>
    </div>

    <div id="desc" class="tab-content on">
        <p class="desc-text">{{ $product->description }}</p>
    </div>

    <div id="ship" class="tab-content">
        <div class="desc-text">
            🚚 <strong>الشحن:</strong> يتم التوصيل خلال 1-3 أيام عمل داخل قطر.
            💰 <strong>الشحن المجاني:</strong> عند الطلب بقيمة {{ \App\Models\Setting::get('free_shipping_at', 200) }} ر.ق وأكثر.
            🔄 <strong>الإرجاع:</strong> يمكن إرجاع المنتجات خلال 7 أيام من تاريخ الاستلام بشرط أن تكون بحالتها الأصلية.
        </div>
    </div>
</div>
@endif

{{-- Related Products --}}
@if($related->count() > 0)
<div class="related-section">
    <div class="sec-hdr">
        <h2>📦 منتجات مشابهة</h2>
        @if($product->category)
            <a href="{{ route('store.category', $product->category->slug) }}">
                عرض الكل <i class="fa fa-arrow-left"></i>
            </a>
        @endif
    </div>
    <div class="pgrid">
        @foreach($related as $rel)
            @include('store.partials.product-card', ['product' => $rel])
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function changeQty(delta) {
        const inp = document.getElementById('qtyInput');
        const max = parseInt(inp.getAttribute('max'));
        const val = Math.min(max, Math.max(1, parseInt(inp.value) + delta));
        inp.value = val;
    }

    function addToCartQty(productId) {
        const qty = parseInt(document.getElementById('qtyInput').value);
        addToCart(productId, qty);
    }

    function switchTab(btn, id) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('on'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('on'));
        btn.classList.add('on');
        document.getElementById(id).classList.add('on');
    }
</script>
@endpush
