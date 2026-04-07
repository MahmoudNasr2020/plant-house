@extends('store.layouts.app')

@section('title', 'إتمام الطلب')

@push('styles')
<style>
    .checkout-wrap {
        width: 100%;
        margin: 0 auto;
        padding: 24px 0 44px;
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 18px;
        align-items: start;
    }

    .checkout-title {
        font-size: 22px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 18px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        line-height: 1;
    }

    .checkout-title i { font-size: 22px; }

    .fsec {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 24px rgba(26,58,42,.08);
        margin-bottom: 18px;
    }

    .fsec h3 {
        font-size: 16px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .fsec h3 i { color: var(--gb); }

    .frow { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
    .fg { display: flex; flex-direction: column; gap: 5px; margin-bottom: 12px; }
    .fg label { font-size: 12.5px; font-weight: 700; color: #2d4a3a; }
    .fg input, .fg select, .fg textarea {
        border: 2px solid var(--gp);
        border-radius: 10px;
        padding: 11px 14px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: .2s;
        background: #fff;
    }
    .fg input:focus, .fg select:focus, .fg textarea:focus { border-color: var(--gb); }
    .fg textarea { resize: vertical; min-height: 80px; }

    /* Payment options */
    .pay-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .pay-opt {
        border: 2px solid var(--gp);
        border-radius: 12px;
        padding: 14px 16px;
        cursor: pointer;
        transition: .2s;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }

    .pay-opt input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
    }

    .pay-opt:hover { border-color: var(--gb); }
    .pay-opt.on { border-color: var(--gb); background: #f0faf4; }
    .pay-opt.pay-disabled { opacity: .55; cursor: not-allowed; background: #f8fdf9; }
    .pay-opt.pay-disabled:hover { border-color: var(--gp); }

    .pay-ico { font-size: 24px; }
    .pay-label { font-size: 13.5px; font-weight: 800; color: var(--gd); }
    .pay-sub { font-size: 11px; color: #9aa89e; }

    /* Shipping type */
    .ship-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .ship-opt {
        border: 2px solid var(--gp);
        border-radius: 12px;
        padding: 13px 14px;
        cursor: pointer;
        transition: .2s;
        text-align: center;
        position: relative;
    }

    .ship-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; }
    .ship-opt:hover { border-color: var(--gb); }
    .ship-opt.on { border-color: var(--gb); background: #f0faf4; }
    .ship-opt .so-ico { font-size: 26px; margin-bottom: 5px; }
    .ship-opt .so-name { font-size: 13px; font-weight: 800; color: var(--gd); }
    .ship-opt .so-price { font-size: 11.5px; color: var(--gb); font-weight: 700; }
    .ship-opt .so-time { font-size: 10.5px; color: #9aa89e; }

    /* Order Summary */
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
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 2px solid var(--gf);
    }

    .order-items { margin-bottom: 18px; }

    .oi-row {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 0;
        border-bottom: 1px solid var(--gf);
    }

    .oi-row:last-child { border-bottom: none; }

    .oi-img {
        width: 48px;
        height: 48px;
        border-radius: 9px;
        object-fit: contain;
        background: var(--gf);
        padding: 4px;
        flex-shrink: 0;
    }

    .oi-name { font-size: 13px; font-weight: 700; color: var(--gd); flex: 1; }
    .oi-qty { font-size: 11.5px; color: #9aa89e; }
    .oi-price { font-size: 13.5px; font-weight: 800; color: var(--gd); white-space: nowrap; }

    .sum-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .sum-row .sk { color: #9aa89e; }
    .sum-row .sv { color: var(--gd); font-weight: 800; }

    .sum-divider { border: none; border-top: 2px solid var(--gf); margin: 12px 0; }

    .sum-total {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 900;
        color: var(--gd);
        margin-bottom: 18px;
    }

    .coupon-wrap {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }

    .coupon-wrap input {
        flex: 1;
        border: 2px solid var(--gp);
        border-radius: 9px;
        padding: 10px 12px;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: .2s;
        text-transform: uppercase;
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
        white-space: nowrap;
    }

    .btn-place {
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
    }

    .btn-place:hover { background: var(--gb); }

    .secure-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 12px;
        color: #9aa89e;
        margin-top: 12px;
    }

    @media (max-width: 850px) {
        .checkout-wrap { grid-template-columns: 1fr; }
        .summary-card { position: static; }
        .pay-options, .ship-options { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')

<div style="max-width:940px;margin:0 auto;padding:0 16px">
<form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm">
@csrf

<div class="checkout-wrap">

    {{-- Left: Form --}}
    <div style="margin-top:15px">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;margin-top:25px">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--gf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fa fa-credit-card" style="color:var(--gb);font-size:17px"></i>
            </div>
            <h1 class="checkout-title" style="margin:0">إتمام الطلب</h1>
        </div>

        {{-- Contact Info --}}
        <div class="fsec">
            <h3><i class="fa fa-user"></i> معلومات التواصل</h3>
            <div class="frow">
                <div class="fg">
                    <label>الاسم الأول *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer?->name ? explode(' ', $customer->name)[0] : '') }}" required>
                    @error('first_name')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="fg">
                    <label>الاسم الأخير *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer?->name ? (explode(' ', $customer->name)[1] ?? '') : '') }}" required>
                    @error('last_name')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="frow">
                <div class="fg">
                    <label>البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ old('email', $customer?->email) }}" required>
                    @error('email')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="fg">
                    <label>رقم الهاتف *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $customer?->phone) }}" placeholder="+974 XXXX XXXX" required>
                    @error('phone')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="fsec">
            <h3><i class="fa fa-map-marker-alt"></i> عنوان التوصيل</h3>
            <div class="frow">
                <div class="fg">
                    <label>المدينة *</label>
                    <select name="city" required>
                        <option value="">اختر المدينة</option>
                        @foreach(['الدوحة','لوسيل','الوكرة','الخور','الريان','الشمال','الشحانية','أم صلال'] as $city)
                            <option value="{{ $city }}" {{ old('city', $customer?->city) === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                    @error('city')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="fg">
                    <label>العنوان التفصيلي *</label>
                    <input type="text" name="address" value="{{ old('address', $customer?->address) }}" placeholder="الشارع، المبنى، الطابق" required>
                    @error('address')<span style="color:var(--red);font-size:12px">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="fg">
                <label>ملاحظات إضافية</label>
                <textarea name="notes" placeholder="أي تعليمات خاصة للتوصيل...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Shipping Type --}}
        <div class="fsec">
            <h3><i class="fa fa-truck"></i> طريقة الشحن</h3>
            <div class="ship-options">
                <label class="ship-opt on" id="ship-standard" onclick="selectShip(this, 'standard')">
                    <input type="radio" name="shipping_type" value="standard" checked>
                    <div class="so-ico">📦</div>
                    <div class="so-name">عادي</div>
                    <div class="so-price">
                        {{ $subtotal >= $freeShipping ? 'مجاني 🎉' : number_format($shippingFee, 0) . ' ر.ق' }}
                    </div>
                    <div class="so-time">2-3 أيام</div>
                </label>
                <label class="ship-opt" id="ship-fast" onclick="selectShip(this, 'fast')">
                    <input type="radio" name="shipping_type" value="fast">
                    <div class="so-ico">⚡</div>
                    <div class="so-name">سريع</div>
                    <div class="so-price">{{ $subtotal >= $freeShipping ? 'مجاني 🎉' : 'مجاني' }}</div>
                    <div class="so-time">نفس اليوم</div>
                </label>
                <label class="ship-opt" id="ship-intl" onclick="selectShip(this, 'international')">
                    <input type="radio" name="shipping_type" value="international">
                    <div class="so-ico">✈️</div>
                    <div class="so-name">دولي</div>
                    <div class="so-price">35 ر.ق</div>
                    <div class="so-time">7-14 يوم</div>
                </label>
            </div>
        </div>

        {{-- Payment --}}
        <div class="fsec">
            <h3><i class="fa fa-credit-card"></i> طريقة الدفع</h3>
            <div class="pay-options">
                <label class="pay-opt on" onclick="selectPay(this, 'cash')">
                    <input type="radio" name="payment_method" value="cash" checked>
                    <div class="pay-ico">💵</div>
                    <div>
                        <div class="pay-label">الدفع عند الاستلام</div>
                        <div class="pay-sub">نقداً عند استلام الطلب</div>
                    </div>
                </label>
                <label class="pay-opt pay-disabled" title="قريباً">
                    <div class="pay-ico">💳</div>
                    <div>
                        <div class="pay-label">بطاقة ائتمان <span style="font-size:10px;color:#9aa89e">(قريباً)</span></div>
                        <div class="pay-sub">غير متاح حالياً</div>
                    </div>
                </label>
                <label class="pay-opt pay-disabled" title="قريباً">
                    <div class="pay-ico">🍎</div>
                    <div>
                        <div class="pay-label">Apple Pay <span style="font-size:10px;color:#9aa89e">(قريباً)</span></div>
                        <div class="pay-sub">غير متاح حالياً</div>
                    </div>
                </label>
                <label class="pay-opt pay-disabled" title="قريباً">
                    <div class="pay-ico">🏦</div>
                    <div>
                        <div class="pay-label">KNET <span style="font-size:10px;color:#9aa89e">(قريباً)</span></div>
                        <div class="pay-sub">غير متاح حالياً</div>
                    </div>
                </label>
            </div>

            <div style="margin-top:14px;background:var(--gf);border-radius:9px;padding:10px 14px;font-size:12.5px;color:#5a7a65;display:flex;align-items:center;gap:7px">
                <i class="fa fa-info-circle" style="color:var(--gb)"></i>
                الدفع عند الاستلام متاح فقط حالياً · باقي طرق الدفع ستكون متاحة قريباً
            </div>
        </div>

    </div>

    {{-- Right: Summary --}}
    <div style="margin-top:15px">
        <div class="summary-card">
            <h3>ملخص الطلب</h3>

            <div class="order-items">
                @foreach($cart as $item)
                    <div class="oi-row">
                        <img class="oi-img"
                             src="{{ $item['image_url'] ?: 'https://placehold.co/100x100/d8f3dc/1a3a2a?text=🌿' }}"
                             alt="{{ $item['name'] }}">
                        <div class="oi-name">
                            {{ $item['name'] }}
                            <div class="oi-qty">× {{ $item['qty'] }}</div>
                        </div>
                        <div class="oi-price">{{ number_format($item['price'] * $item['qty'], 2) }} ر.ق</div>
                    </div>
                @endforeach
            </div>

            <div class="sum-row">
                <span class="sk">المجموع الفرعي</span>
                <span class="sv">{{ number_format($subtotal, 2) }} ر.ق</span>
            </div>
            <div class="sum-row">
                <span class="sk">الشحن</span>
                <span class="sv" id="shippingDisplay">
                    {{ $shippingFee === 0 || $subtotal >= $freeShipping ? 'مجاني 🎉' : number_format($shippingFee, 2) . ' ر.ق' }}
                </span>
            </div>

            <div class="coupon-wrap">
                <input type="text" name="coupon_code" id="couponCode" placeholder="كود خصم؟">
                <button type="button" onclick="verifyCoupon()">تحقق</button>
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
            <div id="couponStatus" style="font-size:12.5px;margin-bottom:10px;display:none"></div>
            <div class="sum-row" id="discountRow" style="display:none">
                <span class="sk">الخصم</span>
                <span class="sv" id="discountDisplay" style="color:var(--gb)"></span>
            </div>

            <hr class="sum-divider">

            <div class="sum-total">
                <span>الإجمالي</span>
                <span id="grandTotal">{{ number_format($subtotal + $shippingFee, 2) }} ر.ق</span>
            </div>

            <button type="submit" class="btn-place">
                <i class="fa fa-lock"></i> تأكيد الطلب
            </button>

            <div class="secure-note">
                <i class="fa fa-shield-alt"></i>
                دفع آمن ومشفر
            </div>
        </div>
    </div>

</div>
</form>
</div>

@endsection

@push('scripts')
<script>
    const subtotal     = {{ $subtotal }};
    const shippingFee  = {{ $shippingFee }};
    const freeAt       = {{ $freeShipping }};
    let   discount     = 0;
    let   shippingCost = subtotal >= freeAt ? 0 : shippingFee;

    function selectPay(el, val) {
        if (el.classList.contains('pay-disabled')) return;
        document.querySelectorAll('.pay-opt').forEach(e => e.classList.remove('on'));
        el.classList.add('on');
        const inp = el.querySelector('input');
        if (inp) inp.checked = true;
    }

    function selectShip(el, val) {
        document.querySelectorAll('.ship-opt').forEach(e => e.classList.remove('on'));
        el.classList.add('on');
        el.querySelector('input').checked = true;

        if (val === 'international') {
            shippingCost = 35;
        } else if (val === 'fast') {
            shippingCost = 0;
        } else {
            shippingCost = subtotal >= freeAt ? 0 : shippingFee;
        }

        updateTotal();
    }

    function updateTotal() {
        const total = Math.max(0, subtotal + shippingCost - discount);
        document.getElementById('grandTotal').textContent = total.toFixed(2) + ' ر.ق';
        document.getElementById('shippingDisplay').textContent =
            shippingCost === 0 ? 'مجاني 🎉' : shippingCost.toFixed(2) + ' ر.ق';
    }

    function verifyCoupon() {
        const code = document.getElementById('couponCode').value.trim().toUpperCase();
        if (!code) return;
        const statusEl = document.getElementById('couponStatus');
        statusEl.style.display = 'block';
        statusEl.style.color = '#9aa89e';
        statusEl.textContent = 'جاري التحقق...';

        // Simple client-side check by sending to a check endpoint
        fetch('/api/check-coupon?code=' + encodeURIComponent(code) + '&subtotal=' + subtotal)
            .then(r => r.json())
            .then(data => {
                if (data.valid) {
                    discount = data.discount;
                    statusEl.style.color = '#1a7a45';
                    statusEl.textContent = '✅ ' + data.message;
                    const dr = document.getElementById('discountRow');
                    dr.style.display = 'flex';
                    document.getElementById('discountDisplay').textContent = '-' + discount.toFixed(2) + ' ر.ق';
                    document.getElementById('appliedCode').textContent = code;
                    document.getElementById('couponApplied').style.display = 'flex';
                    sessionStorage.setItem('ph_coupon', code);
                } else {
                    discount = 0;
                    statusEl.style.color = '#c0392b';
                    statusEl.textContent = '❌ ' + (data.message || 'كود غير صالح');
                    document.getElementById('discountRow').style.display = 'none';
                    document.getElementById('couponApplied').style.display = 'none';
                    sessionStorage.removeItem('ph_coupon');
                }
                updateTotal();
            })
            .catch(() => {
                statusEl.style.color = '#9aa89e';
                statusEl.textContent = 'تعذر التحقق، سيتم الحساب عند الطلب';
            });
    }

    function removeCoupon() {
        sessionStorage.removeItem('ph_coupon');
        discount = 0;
        document.getElementById('couponCode').value = '';
        document.getElementById('couponApplied').style.display = 'none';
        document.getElementById('couponStatus').style.display = 'none';
        document.getElementById('discountRow').style.display = 'none';
        updateTotal();
        if (window.phToast) phToast('تم إزالة كوبون الخصم');
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateTotal();
        // Prefill coupon from cart
        const saved = sessionStorage.getItem('ph_coupon');
        if (saved) {
            document.getElementById('couponCode').value = saved;
            verifyCoupon();
        }
    });
</script>
@endpush
