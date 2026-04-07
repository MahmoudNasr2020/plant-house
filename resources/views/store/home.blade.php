@extends('store.layouts.app')

@section('title', 'الرئيسية')

@section('content')

<div style="max-width:1300px;margin:0 auto;padding:0 24px">

{{-- ══ HERO BANNER ══ --}}
@php
    $heroMain  = $banners->first();
    $heroSides = $banners->slice(1, 2)->values();
    $brandDots = ['#e63946','#f4a261','#2a9d8f','#264653','#6a4c93','#1d3557','#e76f51','#43aa8b','#577590','#f72585'];
@endphp

<div style="display:grid;grid-template-columns:1fr 300px;gap:14px;margin:22px 0 32px" class="ph-hero-split">
    {{-- Main big hero --}}
    @if($heroMain)
        <div style="border-radius:22px;padding:46px;position:relative;overflow:hidden;min-height:300px;display:flex;align-items:center;background:linear-gradient(135deg,{{ $heroMain->bg_from ?? '#1a3a2a' }},{{ $heroMain->bg_to ?? '#40916c' }})">
            <div style="position:relative;z-index:2;max-width:440px">
                @if($heroMain->badge)
                    <span style="display:inline-block;background:#f4a261;color:#1a3a2a;font-size:11.5px;font-weight:800;padding:5px 13px;border-radius:50px;margin-bottom:12px">⚡ {{ $heroMain->badge }}</span>
                @endif
                <h1 style="font-size:33px;font-weight:900;color:#fff;line-height:1.2;margin-bottom:9px">{{ $heroMain->title }}</h1>
                @if($heroMain->subtitle)
                    <p style="color:#b7e4c7;font-size:14px;margin-bottom:22px;line-height:1.7">{{ $heroMain->subtitle }}</p>
                @endif
                @if($heroMain->button_text)
                    <a href="{{ $heroMain->button_link ?: route('store.search', ['sale'=>1]) }}" style="display:inline-flex;align-items:center;gap:7px;background:#f4a261;color:#1a3a2a;padding:12px 26px;border-radius:50px;font-weight:800;font-size:14px;text-decoration:none">
                        <i class="fa fa-shopping-bag"></i> {{ $heroMain->button_text }}
                    </a>
                @endif
            </div>
            <div style="position:absolute;left:30px;top:50%;transform:translateY(-50%);font-size:130px;opacity:.3;z-index:1">{{ $heroMain->emoji ?? '🌿' }}</div>
        </div>
    @else
        <div style="border-radius:22px;padding:46px;position:relative;overflow:hidden;min-height:300px;display:flex;align-items:center;background:linear-gradient(135deg,#1a3a2a,#40916c)">
            <div style="position:relative;z-index:2;max-width:440px">
                <span style="display:inline-block;background:#f4a261;color:#1a3a2a;font-size:11.5px;font-weight:800;padding:5px 13px;border-radius:50px;margin-bottom:12px">⚡ الأفضل في قطر</span>
                <h1 style="font-size:33px;font-weight:900;color:#fff;line-height:1.2;margin-bottom:9px">غذِّ جسمك بأفضل المكملات الطبيعية</h1>
                <p style="color:#b7e4c7;font-size:14px;margin-bottom:22px;line-height:1.7">أكثر من 500 منتج أصلي · شحن سريع</p>
                <a href="{{ route('store.search', ['q'=>'']) }}" style="display:inline-flex;align-items:center;gap:7px;background:#f4a261;color:#1a3a2a;padding:12px 26px;border-radius:50px;font-weight:800;font-size:14px;text-decoration:none">
                    <i class="fa fa-shopping-bag"></i> تسوق الآن
                </a>
            </div>
            <div style="position:absolute;left:30px;top:50%;transform:translateY(-50%);font-size:130px;opacity:.3">💪</div>
        </div>
    @endif

    {{-- Side banners --}}
    <div style="display:flex;flex-direction:column;gap:14px" class="ph-hero-side">
        @foreach($heroSides as $sb)
            <a href="{{ $sb->button_link ?: route('store.search', ['q'=>'']) }}"
               style="border-radius:16px;padding:20px;flex:1;position:relative;overflow:hidden;text-decoration:none;display:block;min-height:140px;background:linear-gradient(135deg,{{ $sb->bg_from ?? '#1d3557' }},{{ $sb->bg_to ?? '#457b9d' }})">
                @if($sb->badge)
                    <span style="font-size:11px;font-weight:700;background:rgba(255,255,255,.22);color:#fff;padding:3px 9px;border-radius:50px;display:inline-block;margin-bottom:7px">{{ $sb->badge }}</span>
                @endif
                <div style="font-size:17px;font-weight:800;color:#fff;line-height:1.3;margin-bottom:3px">{{ $sb->title }}</div>
                @if($sb->subtitle)
                    <div style="font-size:12px;color:rgba(255,255,255,.8);margin-bottom:10px">{{ $sb->subtitle }}</div>
                @endif
                <div style="font-size:12px;color:#fff;font-weight:700">← {{ $sb->button_text ?: 'اطلب الآن' }}</div>
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:48px;opacity:.28">{{ $sb->emoji ?? '🌟' }}</span>
            </a>
        @endforeach

        @for($i = $heroSides->count(); $i < 2; $i++)
            @php
                $fallbacks = [
                    ['bg'=>'linear-gradient(135deg,#6b2d5e,#c9adff)','tag'=>'توفير 40%','title'=>'عروض حصرية','sub'=>'خصومات تصل 50%','emoji'=>'🔥','link'=>route('store.search',['sale'=>1])],
                    ['bg'=>'linear-gradient(135deg,#1d3557,#457b9d)','tag'=>'جديد','title'=>'وصل حديثاً','sub'=>'منتجات جديدة','emoji'=>'✨','link'=>route('store.search',['q'=>''])],
                ];
                $fb = $fallbacks[$i];
            @endphp
            <a href="{{ $fb['link'] }}" style="border-radius:16px;padding:20px;flex:1;position:relative;overflow:hidden;text-decoration:none;display:block;min-height:140px;background:{{ $fb['bg'] }}">
                <span style="font-size:11px;font-weight:700;background:rgba(255,255,255,.22);color:#fff;padding:3px 9px;border-radius:50px;display:inline-block;margin-bottom:7px">{{ $fb['tag'] }}</span>
                <div style="font-size:17px;font-weight:800;color:#fff;line-height:1.3;margin-bottom:3px">{{ $fb['title'] }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,.8);margin-bottom:10px">{{ $fb['sub'] }}</div>
                <div style="font-size:12px;color:#fff;font-weight:700">← اشتري</div>
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:48px;opacity:.28">{{ $fb['emoji'] }}</span>
            </a>
        @endfor
    </div>
</div>

{{-- ══ SHOP BY CATEGORY (card grid) ══ --}}
@if($categories->count() > 0)
<section style="margin-bottom:40px">
    <div class="sec-hdr">
        <h2>🗂️ تسوق حسب الفئة</h2>
    </div>
    <div class="ph-catgrid">
        @foreach($categories as $cat)
            <a href="{{ route('store.category', $cat->slug) }}" class="ph-catcard">
                <span class="ph-cati">{{ $cat->emoji ?? '📦' }}</span>
                <div class="ph-catn">{{ $cat->name }}</div>
                <div class="ph-catc">{{ $cat->products_count ?? 0 }} منتج</div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ══ BRANDS STRIP ══ --}}
@if($brands->count() > 0)
<section style="margin-bottom:40px">
    <div class="sec-hdr">
        <h2>🏷️ ماركات معتمدة</h2>
    </div>
    <div class="ph-brands">
        @foreach($brands as $i => $brand)
            <a href="{{ route('store.search', ['q' => $brand]) }}" class="ph-bpill">
                <span class="ph-bdot" style="background:{{ $brandDots[$i % count($brandDots)] }}"></span>
                {{ $brand }}
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ══ FEATURED PRODUCTS ══ --}}
@if($featured->count() > 0)
<section style="margin-bottom:44px">
    <div class="sec-hdr">
        <h2>⭐ منتجات مميزة</h2>
        <a href="{{ route('store.search', ['q' => '']) }}">عرض الكل <i class="fa fa-arrow-left"></i></a>
    </div>
    <div class="pgrid">
        @foreach($featured as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- ══ PROMO BANNERS ══ --}}
<div class="ph-promo-strip">
    <div class="ph-promo-card" style="background:linear-gradient(135deg,#1a3a2a,#40916c)">
        <div>
            <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px">عرض خاص</div>
            <div style="font-size:22px;font-weight:900;color:#fff;line-height:1.2;margin-bottom:10px">شحن مجاني<br>فوق {{ \App\Models\Setting::get('free_shipping_at', 200) }} ر.ق</div>
            <div style="font-size:13px;color:rgba(255,255,255,.75);margin-bottom:16px">لجميع المناطق في قطر</div>
            <a href="{{ route('store.search', ['q' => '']) }}" class="ph-pc-btn"><i class="fa fa-truck"></i> اطلب الآن</a>
        </div>
        <div style="font-size:80px">🚚</div>
    </div>
    <div class="ph-promo-card" style="background:linear-gradient(135deg,#7b2d8b,#c0392b)">
        <div>
            <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px">تخفيضات</div>
            <div style="font-size:22px;font-weight:900;color:#fff;line-height:1.2;margin-bottom:10px">خصومات<br>حصرية</div>
            <div style="font-size:13px;color:rgba(255,255,255,.75);margin-bottom:16px">على أفضل المنتجات الغذائية</div>
            <a href="{{ route('store.search', ['sale' => '1']) }}" class="ph-pc-btn"><i class="fa fa-tag"></i> تسوق الآن</a>
        </div>
        <div style="font-size:80px">🔥</div>
    </div>
</div>

{{-- ══ COUPONS ══ --}}
@if($coupons->count() > 0)
<section style="margin-bottom:44px">
    <div class="sec-hdr">
        <h2>🎟️ كوبونات خصم نشطة</h2>
    </div>
    <div class="ph-coupons-grid">
        @foreach($coupons as $c)
            <div class="ph-coupon-card">
                <div class="ph-coupon-left">
                    <div class="ph-coupon-value">
                        @if($c->type === 'percentage')
                            {{ (int) $c->value }}%
                        @else
                            {{ (int) $c->value }} <small>ر.ق</small>
                        @endif
                    </div>
                    <div style="font-size:10.5px;font-weight:700;opacity:.8;margin-top:3px">خصم</div>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:4px">
                        <span class="ph-coupon-code">{{ $c->code }}</span>
                        <button class="ph-coupon-copy" onclick="copyCoupon('{{ $c->code }}', this)" title="نسخ"><i class="fa fa-copy"></i></button>
                    </div>
                    <div style="font-size:11.5px;color:#9aa89e">
                        @if($c->min_order_amount > 0)
                            عند الشراء بـ {{ (int) $c->min_order_amount }} ر.ق فأكثر
                        @else
                            صالح لجميع الطلبات
                        @endif
                        @if($c->expires_at) · ينتهي {{ $c->expires_at->format('Y-m-d') }} @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ══ ON SALE ══ --}}
@if($onSale->count() > 0)
<section style="margin-bottom:44px">
    <div class="sec-hdr">
        <h2>🔥 عروض وخصومات</h2>
        <a href="{{ route('store.search', ['sale' => '1']) }}">عرض الكل <i class="fa fa-arrow-left"></i></a>
    </div>
    <div class="pgrid">
        @foreach($onSale as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

</div>

<style>
/* Category grid */
.ph-catgrid { display:grid; grid-template-columns:repeat(8,1fr); gap:12px; }
.ph-catcard { background:#fff; border:2px solid var(--gf); border-radius:14px; padding:18px 10px; text-align:center; text-decoration:none; transition:.2s; cursor:pointer; display:block; box-shadow:0 2px 8px rgba(26,58,42,.04); }
.ph-catcard:hover { border-color:var(--gb); transform:translateY(-3px); box-shadow:0 8px 22px rgba(26,58,42,.1); }
.ph-cati { font-size:32px; display:block; margin-bottom:8px; }
.ph-catn { font-size:13px; font-weight:800; color:var(--gd); margin-bottom:2px; }
.ph-catc { font-size:11px; color:#9aa89e; font-weight:600; }

/* Brands strip */
.ph-brands { display:flex; flex-wrap:wrap; gap:10px; }
.ph-bpill { display:inline-flex; align-items:center; gap:8px; background:#fff; border:2px solid var(--gf); border-radius:50px; padding:9px 16px; font-size:13px; font-weight:700; color:var(--gd); text-decoration:none; transition:.2s; box-shadow:0 2px 6px rgba(26,58,42,.05); }
.ph-bpill:hover { border-color:var(--gb); transform:translateY(-2px); }
.ph-bdot { width:10px; height:10px; border-radius:50%; display:inline-block; }

/* Promo */
.ph-promo-strip { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:44px; }
.ph-promo-card { border-radius:16px; padding:28px 24px; display:flex; align-items:center; justify-content:space-between; overflow:hidden; position:relative; }
.ph-pc-btn { background:rgba(255,255,255,.2); color:#fff; border:2px solid rgba(255,255,255,.4); padding:9px 18px; border-radius:50px; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:7px; text-decoration:none; transition:.2s; }
.ph-pc-btn:hover { background:rgba(255,255,255,.3); }

/* Coupons */
.ph-coupons-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
.ph-coupon-card { background:#fff; border-radius:14px; border:2px dashed var(--gp); padding:16px; display:flex; align-items:center; gap:16px; transition:.2s; }
.ph-coupon-card:hover { border-color:var(--gb); transform:translateY(-2px); box-shadow:0 6px 20px rgba(26,58,42,.08); }
.ph-coupon-left { background:linear-gradient(135deg,var(--gd),var(--gb)); color:#fff; border-radius:10px; padding:12px 14px; text-align:center; min-width:72px; }
.ph-coupon-value { font-size:22px; font-weight:900; line-height:1; }
.ph-coupon-value small { font-size:10px; font-weight:700; }
.ph-coupon-code { font-family:monospace; font-size:15px; font-weight:900; color:var(--gd); letter-spacing:1px; background:var(--gf); padding:4px 10px; border-radius:7px; }
.ph-coupon-copy { background:none; border:none; color:var(--gb); cursor:pointer; padding:4px 7px; border-radius:6px; transition:.2s; font-size:13px; }
.ph-coupon-copy:hover { background:var(--gf); }

@media (max-width:1100px) { .ph-catgrid { grid-template-columns:repeat(6,1fr); } }
@media (max-width:900px) {
    .ph-catgrid { grid-template-columns:repeat(4,1fr); }
    .ph-coupons-grid { grid-template-columns:repeat(2,1fr); }
    .ph-hero-split { grid-template-columns:1fr !important; }
    .ph-hero-side { flex-direction:row !important; }
}
@media (max-width:600px) {
    .ph-catgrid { grid-template-columns:repeat(3,1fr); }
    .ph-promo-strip { grid-template-columns:1fr; }
    .ph-coupons-grid { grid-template-columns:1fr; }
    .ph-hero-side { flex-direction:column !important; }
}
</style>

@endsection

@push('scripts')
<script>
    function copyCoupon(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const old = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-check"></i>';
            btn.style.color = '#1a7a45';
            if (window.phToast) phToast('تم نسخ كود الخصم: ' + code);
            setTimeout(() => { btn.innerHTML = old; btn.style.color = ''; }, 1500);
        });
    }
</script>
@endpush
