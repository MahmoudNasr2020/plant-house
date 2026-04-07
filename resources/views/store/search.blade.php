@extends('store.layouts.app')

@section('title', 'نتائج البحث' . ($query ? ' — ' . $query : ''))

@push('styles')
<style>
    .search-header {
        background: #fff;
        border: 2px solid var(--gf);
        border-radius: 16px;
        padding: 28px 0;
        margin: 24px auto 32px;
        max-width: 1300px;
    }

    .search-header-inner {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .search-header h1 {
        font-size: 26px;
        font-weight: 900;
        color: #0d2318;
        margin-top: 6px;
        margin-bottom: 8px;
    }

    .search-header p { font-size: 14px; color: #6b7a70; }

    .search-box-lg {
        display: flex;
        gap: 10px;
        max-width: 600px;
        margin-top: 20px;
    }

    .search-box-lg input {
        flex: 1;
        border: 2px solid var(--gp);
        border-radius: 12px;
        padding: 13px 18px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: .2s;
        background: #fff;
    }

    .search-box-lg input:focus { border-color: var(--gb); box-shadow: 0 0 0 3px rgba(64,145,108,.12); }

    .search-box-lg button {
        background: var(--gold);
        color: var(--gd);
        border: none;
        padding: 13px 22px;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: .2s;
    }

    .search-box-lg button:hover { background: #e8904f; }

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

    .results-count { font-size: 13px; color: #9aa89e; font-weight: 600; margin-right: auto; }

    .store-section {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 24px 44px;
    }
</style>
@endpush

@section('content')

<div class="search-header">
    <div class="search-header-inner">
        <h1>
            @if($query)
                نتائج البحث عن "{{ $query }}"
            @else
                جميع المنتجات
            @endif
        </h1>
        <p>{{ $products->total() }} نتيجة</p>

        <form class="search-box-lg" method="GET" action="{{ route('store.search') }}">
            @if(request('sale')) <input type="hidden" name="sale" value="1"> @endif
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            <input type="text" name="q" value="{{ $query }}" placeholder="ابحث عن منتج أو ماركة...">
            <button type="submit"><i class="fa fa-search"></i> بحث</button>
        </form>
    </div>
</div>

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('store.search') }}" style="display:flex;gap:11px;align-items:center;flex-wrap:wrap">
        @if($query) <input type="hidden" name="q" value="{{ $query }}"> @endif

        <select name="sort" onchange="this.form.submit()">
            <option value="" {{ !request('sort') ? 'selected' : '' }}>الترتيب الافتراضي</option>
            <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>السعر: الأقل أولاً</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>السعر: الأعلى أولاً</option>
            <option value="newest"     {{ request('sort') === 'newest'     ? 'selected' : '' }}>الأحدث</option>
            <option value="sale"       {{ request('sort') === 'sale'       ? 'selected' : '' }}>العروض أولاً</option>
        </select>

        <select name="category" onchange="this.form.submit()">
            <option value="">كل الأقسام</option>
            @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->emoji }} {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <label style="display:flex;align-items:center;gap:7px;font-size:13.5px;font-weight:700;color:var(--gd);cursor:pointer">
            <input type="checkbox" name="sale" value="1" {{ request('sale') ? 'checked' : '' }} onchange="this.form.submit()"
                   style="width:16px;height:16px;accent-color:var(--gb)">
            العروض فقط
        </label>

        <div class="results-count">{{ $products->total() }} منتج</div>
    </form>
</div>

{{-- Products --}}
<section class="store-section">
    @if($products->count() > 0)
        <div class="pgrid">
            @foreach($products as $product)
                @include('store.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        @if($products->hasPages())
            <div style="display:flex;justify-content:center;margin-top:32px">
                {{ $products->appends(request()->query())->links('store.partials.pagination') }}
            </div>
        @endif
    @else
        <div style="text-align:center;padding:80px 24px;color:#9aa89e">
            <div style="font-size:60px;margin-bottom:16px">🔍</div>
            <h3 style="font-size:20px;font-weight:900;color:var(--gd);margin-bottom:8px">لا توجد نتائج</h3>
            <p style="margin-bottom:20px">جرب البحث بكلمات مختلفة أو تصفح الأقسام</p>
            <a href="{{ route('store.home') }}" style="background:var(--gd);color:#fff;padding:12px 24px;border-radius:50px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px">
                <i class="fa fa-home"></i> العودة للرئيسية
            </a>
        </div>
    @endif
</section>

@endsection
