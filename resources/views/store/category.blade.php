@extends('store.layouts.app')

@section('title', $category->name)

@push('styles')
<style>
    .cat-hero {
        padding: 40px 0;
        margin: 24px auto 32px;
        border-radius: 18px;
        max-width: 1300px;
    }

    .cat-hero-inner {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .cat-emoji-big {
        font-size: 72px;
        filter: drop-shadow(0 4px 16px rgba(0,0,0,.12));
    }

    .cat-info h1 {
        font-size: 32px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 6px;
    }

    .cat-info p { font-size: 14px; color: rgba(255,255,255,.8); }

    .filter-bar {
        max-width: 1300px;
        margin: 25px auto 24px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        gap: 11px;
        flex-wrap: wrap;
    }

    .filter-bar select {
        border: 2px solid var(--gp);
        border-radius: 9px;
        padding: 9px 14px;
        font-family: inherit;
        font-size: 13.5px;
        outline: none;
        background: #fff;
        cursor: pointer;
        color: var(--gd);
        font-weight: 600;
    }

    .filter-bar select:focus { border-color: var(--gb); }

    .results-count {
        font-size: 13px;
        color: #9aa89e;
        font-weight: 600;
        margin-right: auto;
    }

    .store-section {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 24px 44px;
    }

    .pgn-wrap {
        display: flex;
        justify-content: center;
        margin-top: 32px;
    }
</style>
@endpush

@section('content')

{{-- Category Hero --}}
<div class="cat-hero" style="background: linear-gradient(135deg, var(--gd), var(--gm))">
    <div class="cat-hero-inner">
        <div class="cat-emoji-big">{{ $category->emoji ?? '📦' }}</div>
        <div class="cat-info">
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @else
                <p>{{ $products->total() }} منتج متاح</p>
            @endif
        </div>
    </div>
</div>

{{-- Filter bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('store.category', $category->slug) }}" style="display:flex;gap:11px;align-items:center;flex-wrap:wrap">
        <select name="sort" onchange="this.form.submit()">
            <option value="" {{ !request('sort') ? 'selected' : '' }}>الترتيب الافتراضي</option>
            <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>السعر: الأقل أولاً</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>السعر: الأعلى أولاً</option>
            <option value="newest"     {{ request('sort') === 'newest'     ? 'selected' : '' }}>الأحدث</option>
            <option value="sale"       {{ request('sort') === 'sale'       ? 'selected' : '' }}>العروض أولاً</option>
        </select>
        <div class="results-count">{{ $products->total() }} منتج</div>
    </form>
</div>

{{-- Products Grid --}}
<section class="store-section">
    @if($products->count() > 0)
        <div class="pgrid">
            @foreach($products as $product)
                @include('store.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        @if($products->hasPages())
            <div class="pgn-wrap">
                {{ $products->appends(request()->query())->links('store.partials.pagination') }}
            </div>
        @endif
    @else
        <div style="text-align:center;padding:80px 24px;color:#9aa89e">
            <div style="font-size:60px;margin-bottom:16px">🔍</div>
            <h3 style="font-size:18px;font-weight:800;color:var(--gd);margin-bottom:8px">لا توجد منتجات</h3>
            <p>لا توجد منتجات في هذا القسم حالياً</p>
        </div>
    @endif
</section>

@endsection
