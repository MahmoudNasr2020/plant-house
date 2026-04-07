@extends('admin.layouts.app')

@section('title', 'الإعدادات')
@section('page_title', '⚙️ إعدادات المتجر')

@section('topbar_actions')
    <button type="submit" form="settingsForm" class="btn-p">
        <i class="fa fa-save"></i> حفظ الإعدادات
    </button>
@endsection

@section('content')

    <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        @php
            $currentLogo    = \App\Models\Setting::get('store_logo');
            $currentFavicon = \App\Models\Setting::get('store_favicon');
        @endphp

        {{-- ── BRAND ASSETS ── --}}
        <div class="fsec" style="margin-bottom:16px">
            <h3><i class="fa fa-image"></i> شعار الموقع والأيقونة</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="fg">
                    <label>شعار المتجر (Logo)</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                        <div style="width:70px;height:70px;border:2px solid var(--gf);border-radius:12px;display:flex;align-items:center;justify-content:center;background:#fafffb;overflow:hidden">
                            @if($currentLogo)
                                <img src="{{ $currentLogo }}" alt="logo" style="max-width:100%;max-height:100%;object-fit:contain">
                            @else
                                <i class="fa fa-image" style="font-size:22px;color:#9aa89e"></i>
                            @endif
                        </div>
                        <input type="file" name="store_logo" accept="image/png,image/jpeg,image/svg+xml,image/webp">
                    </div>
                    <small style="color:#9aa89e;font-size:11.5px">PNG / SVG / WebP — أفضل حجم: 200×60</small>
                    @if($currentLogo)
                        <label style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12px;color:#9aa89e;font-weight:600">
                            <input type="checkbox" name="remove_logo" value="1"> إزالة الشعار الحالي
                        </label>
                    @endif
                </div>
                <div class="fg">
                    <label>الأيقونة (Favicon)</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                        <div style="width:70px;height:70px;border:2px solid var(--gf);border-radius:12px;display:flex;align-items:center;justify-content:center;background:#fafffb;overflow:hidden">
                            @if($currentFavicon)
                                <img src="{{ $currentFavicon }}" alt="favicon" style="max-width:100%;max-height:100%;object-fit:contain">
                            @else
                                <i class="fa fa-star" style="font-size:22px;color:#9aa89e"></i>
                            @endif
                        </div>
                        <input type="file" name="store_favicon" accept="image/png,image/x-icon,image/svg+xml,image/vnd.microsoft.icon">
                    </div>
                    <small style="color:#9aa89e;font-size:11.5px">ICO / PNG / SVG — أفضل حجم: 32×32 أو 64×64</small>
                    @if($currentFavicon)
                        <label style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12px;color:#9aa89e;font-weight:600">
                            <input type="checkbox" name="remove_favicon" value="1"> إزالة الأيقونة الحالية
                        </label>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

            {{-- ── STORE INFO ── --}}
            <div class="fsec">
                <h3><i class="fa fa-store"></i> معلومات المتجر</h3>

                <div class="fg">
                    <label>اسم المتجر</label>
                    <input type="text" name="store_name" value="{{ old('store_name', \App\Models\Setting::get('store_name', config('app.name'))) }}" placeholder="Plant House" required>
                </div>
                <div class="fg">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="store_email" value="{{ old('store_email', \App\Models\Setting::get('store_email', 'info@planthouse.qa')) }}" required>
                </div>
                <div class="fg">
                    <label>رقم الهاتف</label>
                    <input type="text" name="store_phone" value="{{ old('store_phone', \App\Models\Setting::get('store_phone', '+974 5555 1234')) }}" placeholder="+974 5555 1234">
                </div>
                <div class="fg">
                    <label>العنوان</label>
                    <input type="text" name="store_address" value="{{ old('store_address', \App\Models\Setting::get('store_address', 'الدوحة، قطر')) }}" placeholder="الدوحة، قطر">
                </div>
            </div>

            {{-- ── SHIPPING & CURRENCY ── --}}
            <div>
                <div class="fsec" style="margin-bottom:16px">
                    <h3><i class="fa fa-truck"></i> الشحن والعملة</h3>

                    <div class="fg">
                        <label>العملة</label>
                        <select name="currency" class="tfltr" style="width:100%;padding:9px 12px;font-size:13.5px">
                            <option value="QAR" {{ true ? 'selected' : '' }}>QAR — ريال قطري</option>
                            <option value="SAR">SAR — ريال سعودي</option>
                            <option value="AED">AED — درهم إماراتي</option>
                            <option value="USD">USD — دولار أمريكي</option>
                        </select>
                    </div>
                    <div class="frow">
                        <div class="fg">
                            <label>رسوم الشحن (ر.ق)</label>
                            <input type="number" step="0.01" name="shipping_fee" value="{{ old('shipping_fee', \App\Models\Setting::get('shipping_fee', '15')) }}" placeholder="15" min="0">
                        </div>
                        <div class="fg">
                            <label>شحن مجاني فوق (ر.ق)</label>
                            <input type="number" step="0.01" name="free_shipping_at" value="{{ old('free_shipping_at', \App\Models\Setting::get('free_shipping_at', '200')) }}" placeholder="200" min="0">
                        </div>
                    </div>
                </div>

                <div class="fsec">
                    <h3><i class="fa fa-share-alt"></i> التواصل الاجتماعي</h3>

                    <div class="fg">
                        <label><i class="fab fa-instagram" style="color:#e1306c"></i> Instagram</label>
                        <input type="text" name="social_instagram" value="{{ old('social_instagram', \App\Models\Setting::get('social_instagram')) }}" placeholder="https://instagram.com/planthouse">
                    </div>
                    <div class="fg">
                        <label><i class="fab fa-twitter" style="color:#1da1f2"></i> Twitter / X</label>
                        <input type="text" name="social_twitter" value="{{ old('social_twitter', \App\Models\Setting::get('social_twitter')) }}" placeholder="https://twitter.com/planthouse">
                    </div>
                    <div class="fg">
                        <label><i class="fab fa-whatsapp" style="color:#25d366"></i> WhatsApp</label>
                        <input type="text" name="social_whatsapp" value="{{ old('social_whatsapp', \App\Models\Setting::get('social_whatsapp')) }}" placeholder="+974 5555 1234">
                    </div>
                </div>
            </div>

        </div>

        <div style="margin-top:6px;display:flex;gap:11px;justify-content:flex-end">
            <button type="submit" class="btn-p" style="padding:12px 28px;font-size:14px">
                <i class="fa fa-save"></i> حفظ جميع الإعدادات
            </button>
        </div>

    </form>

@endsection
