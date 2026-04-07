@extends('store.layouts.app')

@section('title', 'سلة التسوق')

@push('styles')
<style>
    .cart-wrap {
        width: 100%;
        margin: 0 auto;
        padding: 24px 0 44px;
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 18px;
        align-items: start;
    }

    .cart-page-title {
        font-size: 26px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Cart Items Card */
    .cart-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(26,58,42,.08);
        overflow: hidden;
    }

    .cart-card-hdr {
        padding: 16px 22px;
        border-bottom: 2px solid var(--gf);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-card-hdr h3 { font-size: 15px; font-weight: 800; color: var(--gd); }

    .clear-cart {
        font-size: 12.5px;
        color: var(--red);
        font-weight: 700;
        cursor: pointer;
        background: none;
        border: none;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: .2s;
    }

    .clear-cart:hover { opacity: .7; }

    /* Cart Row */
    .cart-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 22px;
        border-bottom: 1px solid var(--gf);
        transition: .2s;
    }

    .cart-row:last-child { border-bottom: none; }
    .cart-row:hover { background: #f8fdf9; }

    .cart-img {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: contain;
        background: var(--gf);
        padding: 6px;
        flex-shrink: 0;
    }

    .cart-info { flex: 1 }

    .cart-name {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--gd);
        margin-bottom: 3px;
        text-decoration: none;
        display: block;
    }

    .cart-name:hover { color: var(--gb); }
    .cart-brand { font-size: 12px; color: #9aa89e; }

    .cart-unit {
        font-size: 13px;
        color: var(--gb);
        font-weight: 700;
        margin-top: 4px;
    }

    .qty-ctrl-sm {
        display: flex;
        align-items: center;
        border: 2px solid var(--gp);
        border-radius: 9px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .qty-ctrl-sm button {
        width: 32px;
        height: 34px;
        background: #f8fdf9;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        color: var(--gd);
        transition: .2s;
    }

    .qty-ctrl-sm button:hover { background: var(--gf); }

    .qty-ctrl-sm span {
        width: 36px;
        text-align: center;
        font-size: 14px;
        font-weight: 800;
        color: var(--gd);
    }

    .cart-subtotal {
        font-size: 16px;
        font-weight: 900;
        color: var(--gd);
        min-width: 80px;
        text-align: left;
    }

    .cart-subtotal span { font-size: 11px; color: #9aa89e; font-weight: 600; }

    .remove-btn {
        background: none;
        border: none;
        color: #9aa89e;
        cursor: pointer;
        font-size: 15px;
        padding: 4px;
        transition: .2s;
    }

    .remove-btn:hover { color: var(--red); }

    /* Summary Card */
    .summary-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(26,58,42,.08);
        padding: 24px;
        position: sticky;
        top: 90px;
    }

    .summary-card h3 {
        font-size: 17px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid var(--gf);
    }

    .sum-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 11px;
    }

    .sum-row .sk { color: #9aa89e; }
    .sum-row .sv { color: var(--gd); font-weight: 800; }

    .sum-divider { border: none; border-top: 2px solid var(--gf); margin: 14px 0; }

    .sum-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 20px;
    }

    .btn-checkout {
        width: 100%;
        background: var(--gd);
        color: #fff;
        border: none;
        padding: 15px;
        border-radius: 50px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: .2s;
        text-decoration: none;
        margin-bottom: 11px;
    }

    .btn-checkout:hover { background: var(--gb); }

    .btn-continue {
        width: 100%;
        background: #f8fdf9;
        color: var(--gd);
        border: 2px solid var(--gp);
        padding: 13px;
        border-radius: 50px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: .2s;
        text-decoration: none;
    }

    .btn-continue:hover { border-color: var(--gb); color: var(--gb); }

    /* Coupon */
    .coupon-wrap {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .coupon-wrap input {
        flex: 1;
        border: 2px solid var(--gp);
        border-radius: 9px;
        padding: 10px 12px;
        font-family: inherit;
        font-size: 13.5px;
        outline: none;
        transition: .2s;
    }

    .coupon-wrap input:focus { border-color: var(--gb); }

    .coupon-wrap button {
        background: var(--gd);
        color: #fff;
        border: none;
        padding: 10px 14px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
        white-space: nowrap;
    }

    .coupon-wrap button:hover { background: var(--gb); }

    .shipping-note {
        background: #e6f9ee;
        color: #1a7a45;
        border-radius: 9px;
        padding: 10px 13px;
        font-size: 12.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 16px;
    }

    @media (max-width: 850px) {
        .cart-wrap { grid-template-columns: 1fr; }
        .summary-card { position: static; }
    }
</style>
@endpush

@section('content')

<div style="max-width:900px;margin:0 auto;padding:0 16px">
@if(empty($cart))
    <div style="max-width:600px;margin:80px auto;text-align:center;padding:0 24px">
        <div style="font-size:80px;margin-bottom:20px">🛒</div>
        <h2 style="font-size:24px;font-weight:900;color:var(--gd);margin-bottom:10px">سلتك فارغة</h2>
        <p style="color:#9aa89e;font-size:14.5px;margin-bottom:28px">أضف بعض المنتجات لتبدأ التسوق!</p>
        <a href="{{ route('store.home') }}" style="background:var(--gd);color:#fff;padding:14px 32px;border-radius:50px;font-size:15px;font-weight:800;display:inline-flex;align-items:center;gap:9px;text-decoration:none">
            <i class="fa fa-home"></i> تصفح المنتجات
        </a>
    </div>
@else

<div class="cart-wrap">

    {{-- Left: Items --}}
    <div style="margin-top:15px">
        <h1 class="cart-page-title" style="margin-top:25px">
            <i class="fa fa-shopping-cart" style="color:var(--gb)"></i>
            سلة التسوق
            <span style="font-size:16px;color:#9aa89e;font-weight:600">({{ collect($cart)->sum('qty') }} منتج)</span>
        </h1>

        <div class="cart-card">
            <div class="cart-card-hdr">
                <h3>منتجاتك</h3>
                <button class="clear-cart" onclick="clearCart()">
                    <i class="fa fa-trash"></i> تفريغ السلة
                </button>
            </div>

            @foreach($cart as $item)
                <div class="cart-row" id="cart-row-{{ $item['id'] }}">
                    <img class="cart-img"
                         src="{{ $item['image_url'] ?: 'https://placehold.co/150x150/d8f3dc/1a3a2a?text=🌿' }}"
                         alt="{{ $item['name'] }}">

                    <div class="cart-info">
                        @if(isset($item['slug']))
                            <a href="{{ route('store.product', $item['slug']) }}" class="cart-name">{{ $item['name'] }}</a>
                        @else
                            <span class="cart-name">{{ $item['name'] }}</span>
                        @endif
                        <div class="cart-brand">{{ $item['brand'] ?? '' }}</div>
                        <div class="cart-unit">{{ number_format($item['price'], 2) }} ر.ق / قطعة</div>
                    </div>

                    <div class="qty-ctrl-sm">
                        <button onclick="updateCart({{ $item['id'] }}, {{ $item['qty'] - 1 }})">−</button>
                        <span>{{ $item['qty'] }}</span>
                        <button onclick="updateCart({{ $item['id'] }}, {{ $item['qty'] + 1 }})">+</button>
                    </div>

                    <div class="cart-subtotal">
                        {{ number_format($item['price'] * $item['qty'], 2) }}
                        <span>ر.ق</span>
                    </div>

                    <button class="remove-btn" onclick="removeFromCart({{ $item['id'] }})" title="حذف">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Summary --}}
    <div style="margin-top:15px">
        <div class="summary-card">
            <h3>ملخص الطلب</h3>

            @php
                $subtotal    = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                $freeAt      = (float) \App\Models\Setting::get('free_shipping_at', 200);
                $shippingFee = (float) \App\Models\Setting::get('shipping_fee', 15);
                $shipping    = $subtotal >= $freeAt ? 0 : $shippingFee;
                $total       = $subtotal + $shipping;
            @endphp

            @if($subtotal < $freeAt)
                <div class="shipping-note">
                    <i class="fa fa-truck"></i>
                    أضف {{ number_format($freeAt - $subtotal, 2) }} ر.ق للحصول على شحن مجاني
                </div>
            @else
                <div class="shipping-note">
                    <i class="fa fa-check-circle"></i>
                    تهانينا! تستحق شحناً مجانياً
                </div>
            @endif

            <div class="sum-row">
                <span class="sk">المجموع الفرعي</span>
                <span class="sv">{{ number_format($subtotal, 2) }} ر.ق</span>
            </div>
            <div class="sum-row">
                <span class="sk">رسوم الشحن</span>
                <span class="sv" style="{{ $shipping === 0 ? 'color:var(--gb)' : '' }}">
                    {{ $shipping === 0 ? 'مجاني 🎉' : number_format($shipping, 2) . ' ر.ق' }}
                </span>
            </div>

            <div class="sum-row" id="discountRow" style="display:none">
                <span class="sk">الخصم</span>
                <span class="sv" id="discountAmount" style="color:var(--gb)"></span>
            </div>

            <hr class="sum-divider">

            <div class="coupon-wrap">
                <input type="text" id="couponInput" placeholder="كود الخصم" style="text-transform:uppercase">
                <button onclick="applyCoupon()" id="couponBtn">تطبيق</button>
            </div>
            <div id="couponApplied" style="display:none;align-items:center;justify-content:space-between;gap:8px;background:#e8f7ef;border:1px dashed var(--gb);border-radius:9px;padding:8px 12px;margin-bottom:8px">
                <span style="font-size:12.5px;color:var(--gd);font-weight:700">
                    <i class="fa fa-check-circle" style="color:var(--gb)"></i>
                    كوبون مطبّق: <span id="appliedCode" style="font-family:monospace"></span>
                </span>
                <button type="button" onclick="removeCoupon()" title="إزالة الكوبون" style="background:none;border:none;color:var(--red);cursor:pointer;font-size:14px;padding:4px 6px;border-radius:6px">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div id="couponMsg" style="font-size:12.5px;margin-bottom:12px;display:none"></div>

            <hr class="sum-divider">

            <div class="sum-total" id="totalRow">
                <span>الإجمالي</span>
                <span id="totalAmount">{{ number_format($total, 2) }} ر.ق</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn-checkout">
                <i class="fa fa-credit-card"></i> إتمام الطلب
            </a>
            <a href="{{ route('store.home') }}" class="btn-continue">
                <i class="fa fa-arrow-right"></i> متابعة التسوق
            </a>
        </div>
    </div>

</div>

@endif
</div>

@endsection

@push('scripts')
@if(!empty($cart))
<script>
    function updateCart(productId, qty) {
        if (qty <= 0) { removeFromCart(productId); return; }
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: productId, qty: qty })
        }).then(() => location.reload());
    }

    function removeFromCart(productId) {
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: productId })
        }).then(() => location.reload());
    }

    function clearCart() {
        if (!confirm('هل تريد تفريغ السلة؟')) return;
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => location.reload());
    }

    const cartSubtotal = {{ $subtotal }};
    const cartShipping = {{ $shipping }};

    function applyCoupon() {
        const code = document.getElementById('couponInput').value.trim().toUpperCase();
        const msg  = document.getElementById('couponMsg');
        if (!code) return;

        fetch('{{ route("api.check-coupon") }}?code=' + encodeURIComponent(code) + '&subtotal=' + cartSubtotal)
            .then(r => r.json())
            .then(data => {
                msg.style.display = 'block';
                if (data.valid) {
                    msg.style.color = '#1a7a45';
                    msg.textContent = '✅ ' + data.message;

                    // Show discount row
                    document.getElementById('discountRow').style.display = 'flex';
                    document.getElementById('discountAmount').textContent = '-' + Number(data.discount).toFixed(2) + ' ر.ق';

                    // Update total
                    const newTotal = Math.max(0, cartSubtotal + cartShipping - data.discount);
                    document.getElementById('totalAmount').textContent = newTotal.toFixed(2) + ' ر.ق';

                    // Show applied pill
                    document.getElementById('appliedCode').textContent = code;
                    document.getElementById('couponApplied').style.display = 'flex';

                    // Save to session storage so checkout picks it up
                    sessionStorage.setItem('ph_coupon', code);
                } else {
                    msg.style.color = '#c0392b';
                    msg.textContent = '❌ ' + (data.message || 'كود غير صحيح');
                    document.getElementById('discountRow').style.display = 'none';
                    document.getElementById('couponApplied').style.display = 'none';
                    document.getElementById('totalAmount').textContent = (cartSubtotal + cartShipping).toFixed(2) + ' ر.ق';
                    sessionStorage.removeItem('ph_coupon');
                }
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.style.color = '#c0392b';
                msg.textContent = '❌ حدث خطأ';
            });
    }

    function removeCoupon() {
        sessionStorage.removeItem('ph_coupon');
        document.getElementById('couponInput').value = '';
        document.getElementById('couponApplied').style.display = 'none';
        document.getElementById('discountRow').style.display = 'none';
        document.getElementById('couponMsg').style.display = 'none';
        document.getElementById('totalAmount').textContent = (cartSubtotal + cartShipping).toFixed(2) + ' ر.ق';
        if (window.phToast) phToast('تم إزالة كوبون الخصم');
    }

    // Auto-restore coupon on page load
    (function() {
        const saved = sessionStorage.getItem('ph_coupon');
        if (saved) {
            document.getElementById('couponInput').value = saved;
            applyCoupon();
        }
    })();
</script>
@endif
@endpush
