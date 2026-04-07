<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Plant House') — متجر المكملات الغذائية</title>

    @php
        $siteFavicon = \App\Models\Setting::get('store_favicon');
        $siteLogo    = \App\Models\Setting::get('store_logo');
    @endphp
    @if($siteFavicon)
        <link rel="icon" type="image/png" href="{{ $siteFavicon }}">
        <link rel="shortcut icon" href="{{ $siteFavicon }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --gd: #1a3a2a;
            --gm: #2d6a4f;
            --gb: #40916c;
            --gl: #74c69d;
            --gp: #b7e4c7;
            --gf: #d8f3dc;
            --gold: #f4a261;
            --red: #e63946;
            --sh: 0 4px 24px rgba(26,58,42,.10);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #f8fdf9;
            color: #0d2318;
            direction: rtl;
        }

        a { text-decoration: none; color: inherit; }

        /* ─── TOP STRIP ─── */
        .top-strip {
            background: var(--gd);
            color: var(--gp);
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            padding: 7px 16px;
            letter-spacing: .3px;
        }

        /* ─── HEADER ─── */
        .site-header {
            background: #fff;
            border-bottom: 2px solid var(--gf);
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: 0 2px 16px rgba(26,58,42,.07);
        }

        .header-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
            height: 70px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Logo */
        .site-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            text-decoration: none;
        }

        .logo-ico {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--gb), var(--gd));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .logo-text .lt { font-size: 18px; font-weight: 900; color: var(--gd); line-height: 1; }
        .logo-text .ls { font-size: 11px; color: var(--gb); font-weight: 600; }

        /* Search */
        .header-search {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .header-search input {
            width: 100%;
            border: 2px solid var(--gp);
            border-radius: 50px;
            padding: 10px 20px 10px 46px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: .2s;
            background: #f8fdf9;
        }

        .header-search input:focus { border-color: var(--gb); background: #fff; }

        .header-search button {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9aa89e;
            cursor: pointer;
            font-size: 15px;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-right: auto;
        }

        .hbtn {
            position: relative;
            background: none;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--gd);
            cursor: pointer;
            transition: .2s;
            text-decoration: none;
        }

        .hbtn:hover { background: var(--gf); }

        .hbtn.user-pill {
            width: auto;
            padding: 0 14px 0 12px;
            gap: 7px;
            font-size: 13px;
            font-weight: 700;
        }
        .hbtn.user-pill .user-name { font-size: 13px; font-weight: 700; }
        .hbtn.user-pill i { font-size: 17px; }
        @media (max-width: 700px) { .hbtn.user-pill .user-name { display: none; } .hbtn.user-pill { width: 44px; padding: 0; } }

        .hbtn-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: var(--red);
            color: #fff;
            font-size: 9.5px;
            font-weight: 800;
            min-width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
        }

        .btn-cart {
            background: var(--gd);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 50px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: .2s;
            text-decoration: none;
            position: relative;
        }

        .btn-cart:hover { background: var(--gb); }

        /* ─── NAV BAR ─── */
        .site-nav {
            background: var(--gd);
        }

        .nav-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 2px;
            overflow-x: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 11px 14px;
            color: rgba(255,255,255,.75);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: .2s;
            border-bottom: 2px solid transparent;
            text-decoration: none;
        }

        .nav-item:hover { color: #fff; background: rgba(255,255,255,.06); }
        .nav-item.on    { color: #fff; border-bottom-color: var(--gold); }

        /* ─── MAIN ─── */
        .site-main {
            min-height: 60vh;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ─── PRODUCT CARD ─── */
        .pcard {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--sh);
            transition: .22s;
            position: relative;
        }

        .pcard:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(26,58,42,.14); }

        .pcard-img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: contain;
            background: var(--gf);
            padding: 16px;
        }

        .pcard-body { padding: 13px 14px 14px; }

        .pcard-cat {
            font-size: 10.5px;
            color: var(--gb);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .pcard-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--gd);
            margin-bottom: 3px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pcard-brand { font-size: 11.5px; color: #9aa89e; margin-bottom: 8px; }

        .pcard-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .pcard-price { font-size: 17px; font-weight: 900; color: var(--gd); }
        .pcard-price span { font-size: 11px; color: #9aa89e; }
        .pcard-old { font-size: 12px; color: #9aa89e; text-decoration: line-through; }

        .btn-add {
            background: var(--gd);
            color: #fff;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
            transition: .2s;
            flex-shrink: 0;
        }

        .btn-add:hover { background: var(--gb); }

        .pcard-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--red);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 50px;
        }

        .pcard-wish {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #fff;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: #9aa89e;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            transition: .2s;
        }

        .pcard-wish:hover, .pcard-wish.on { color: var(--red); }

        /* Grid */
        .pgrid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        @media (max-width: 1200px) { .pgrid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 1000px) { .pgrid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 680px)  { .pgrid { grid-template-columns: repeat(2, 1fr); } }

        /* ─── SECTION HEADER ─── */
        .sec-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .sec-hdr h2 {
            font-size: 20px;
            font-weight: 900;
            color: var(--gd);
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .sec-hdr a {
            font-size: 13px;
            font-weight: 700;
            color: var(--gb);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ─── FOOTER ─── */
        .site-footer {
            background: var(--gd);
            color: rgba(255,255,255,.75);
            margin-top: 60px;
        }

        .footer-top {
            max-width: 1300px;
            margin: 0 auto;
            padding: 44px 24px 32px;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 32px;
        }

        .footer-brand .fb-logo {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
        }

        .fb-ico { width: 38px; height: 38px; background: rgba(255,255,255,.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .fb-name { font-size: 17px; font-weight: 900; color: #fff; }

        .footer-brand p { font-size: 13px; line-height: 1.7; margin-bottom: 16px; }

        .social-links { display: flex; gap: 8px; }
        .sl { width: 36px; height: 36px; background: rgba(255,255,255,.1); border-radius: 9px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.7); font-size: 15px; transition: .2s; text-decoration: none; }
        .sl:hover { background: var(--gb); color: #fff; }

        .footer-col h5 { font-size: 13.5px; font-weight: 800; color: #fff; margin-bottom: 14px; }
        .footer-col a { display: block; font-size: 12.5px; color: rgba(255,255,255,.6); margin-bottom: 9px; transition: .2s; }
        .footer-col a:hover { color: #fff; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,.35);
        }

        @media (max-width: 900px) {
            .footer-top { grid-template-columns: 1fr 1fr; }
            .header-inner { gap: 12px; }
            .top-strip { display: none; }
        }

        @media (max-width: 600px) {
            .footer-top { grid-template-columns: 1fr; }
            .header-search { display: none; }
        }

        /* ─── TOAST ─── */
        #ph-toast {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--gd);
            color: #fff;
            padding: 12px 22px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            box-shadow: 0 8px 28px rgba(0,0,0,.2);
            z-index: 9999;
            opacity: 0;
            transition: .3s;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 9px;
            white-space: nowrap;
        }

        #ph-toast.on { opacity: 1; transform: translateX(-50%) translateY(0); }

        @stack('styles')
    </style>
</head>
<body>

    {{-- TOP STRIP --}}
    <div class="top-strip">
        🌿 شحن مجاني للطلبات فوق {{ \App\Models\Setting::get('free_shipping_at', 200) }} ر.ق
        &nbsp;|&nbsp;
        <i class="fa fa-phone"></i> {{ \App\Models\Setting::get('store_phone', '+974 5555 1234') }}
    </div>

    {{-- HEADER --}}
    <header class="site-header">
        <div class="header-inner">

            <a href="{{ route('store.home') }}" class="site-logo">
                @if($siteLogo)
                    <div class="logo-ico" style="background:#fff;border:2px solid var(--gf);overflow:hidden">
                        <img src="{{ $siteLogo }}" alt="logo" style="max-width:100%;max-height:100%;object-fit:contain">
                    </div>
                @else
                    <div class="logo-ico">🌿</div>
                @endif
                <div class="logo-text">
                    <div class="lt">{{ \App\Models\Setting::get('store_name', 'Plant House') }}</div>
                    <div class="ls">مكملات غذائية</div>
                </div>
            </a>

            <form class="header-search" action="{{ route('store.search') }}" method="GET">
                <input type="text" name="q" placeholder="ابحث عن منتج أو ماركة..." value="{{ request('q') }}" autocomplete="off">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <div class="header-actions">

                @auth('customer')
                    @php $customerName = auth('customer')->user()->name; @endphp
                    <a href="{{ route('store.profile') }}" class="hbtn user-pill" title="حسابي">
                        <i class="fa fa-user-circle"></i>
                        <span class="user-name">{{ explode(' ', $customerName)[0] }}</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="hbtn" title="المفضلة" id="wishlistBtn">
                        <i class="fa fa-heart"></i>
                        @php $wCount = auth('customer')->user()->wishlist()->count(); @endphp
                        <span class="hbtn-badge" id="wishlistBadge" style="{{ $wCount > 0 ? '' : 'display:none' }}">{{ $wCount }}</span>
                    </a>
                @else
                    <a href="{{ route('store.login') }}" class="hbtn user-pill" title="تسجيل الدخول">
                        <i class="fa fa-user"></i>
                        <span class="user-name">دخول</span>
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="btn-cart" id="cartBtn">
                    <i class="fa fa-shopping-cart"></i>
                    السلة
                    @php $cartCount = collect(session('ph_cart', []))->sum('qty'); @endphp
                    @if($cartCount > 0)
                        <span class="hbtn-badge" style="position:static;background:var(--gold);color:var(--gd);min-width:auto;height:auto;padding:1px 6px;border-radius:50px;font-size:11px">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    {{-- CATEGORY NAV --}}
    <nav class="site-nav">
        <div class="nav-inner">
            <a href="{{ route('store.home') }}" class="nav-item {{ request()->routeIs('store.home') ? 'on' : '' }}">
                <i class="fa fa-home"></i> الرئيسية
            </a>
            @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $navCat)
                <a href="{{ route('store.category', $navCat->slug) }}"
                   class="nav-item {{ request()->is('category/'.$navCat->slug) ? 'on' : '' }}">
                    {{ $navCat->emoji }} {{ $navCat->name }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div style="background:#e6f9ee;color:#1a7a45;border-bottom:2px solid #b7e4c7;padding:11px 24px;font-size:13.5px;font-weight:700;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fff0f0;color:#c0392b;border-bottom:2px solid #f5c6c6;padding:11px 24px;font-size:13.5px;font-weight:700;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- MAIN --}}
    <main class="site-main">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="fb-logo">
                    <div class="fb-ico">🌿</div>
                    <div class="fb-name">{{ \App\Models\Setting::get('store_name', 'Plant House') }}</div>
                </div>
                <p>متجرك الأول للمكملات الغذائية والمنتجات الصحية في قطر. جودة عالية وأسعار منافسة مع توصيل سريع.</p>
                <div class="social-links">
                    @if(\App\Models\Setting::get('social_instagram'))
                        <a href="{{ \App\Models\Setting::get('social_instagram') }}" class="sl" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_twitter'))
                        <a href="{{ \App\Models\Setting::get('social_twitter') }}" class="sl" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('social_whatsapp')) }}" class="sl" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    @endif
                </div>
            </div>

            <div class="footer-col">
                <h5>التصنيفات</h5>
                @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(6)->get() as $fc)
                    <a href="{{ route('store.category', $fc->slug) }}">{{ $fc->emoji }} {{ $fc->name }}</a>
                @endforeach
            </div>

            <div class="footer-col">
                <h5>حسابي</h5>
                <a href="{{ route('store.login') }}">تسجيل الدخول</a>
                <a href="{{ route('store.register') }}">إنشاء حساب</a>
                <a href="{{ route('store.profile') }}">ملفي الشخصي</a>
                <a href="{{ route('store.orders') }}">طلباتي</a>
                <a href="{{ route('wishlist.index') }}">المفضلة</a>
            </div>

            <div class="footer-col">
                <h5>تواصل معنا</h5>
                <a href="#"><i class="fa fa-map-marker-alt"></i> {{ \App\Models\Setting::get('store_address', 'الدوحة، قطر') }}</a>
                <a href="tel:{{ \App\Models\Setting::get('store_phone') }}"><i class="fa fa-phone"></i> {{ \App\Models\Setting::get('store_phone') }}</a>
                <a href="mailto:{{ \App\Models\Setting::get('store_email') }}"><i class="fa fa-envelope"></i> {{ \App\Models\Setting::get('store_email') }}</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('store_name', 'Plant House') }} — جميع الحقوق محفوظة
        </div>
    </footer>

    {{-- TOAST --}}
    <div id="ph-toast"><i class="fa fa-check-circle"></i> <span id="ph-toast-msg"></span></div>

    <script>
        function phToast(msg, icon = 'fa-check-circle') {
            const t = document.getElementById('ph-toast');
            document.querySelector('#ph-toast i').className = 'fa ' + icon;
            document.getElementById('ph-toast-msg').textContent = msg;
            t.classList.add('on');
            setTimeout(() => t.classList.remove('on'), 3000);
        }

        // Update cart count badge
        function updateCartBadge(count) {
            const btn = document.getElementById('cartBtn');
            if (!btn) return;
            let badge = btn.querySelector('.hbtn-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'hbtn-badge';
                    badge.style.cssText = 'position:static;background:var(--gold);color:var(--gd);min-width:auto;height:auto;padding:1px 6px;border-radius:50px;font-size:11px';
                    btn.appendChild(badge);
                }
                badge.textContent = count;
            } else if (badge) {
                badge.remove();
            }
        }

        // Wishlist toggle
        function toggleWishlist(btn, productId) {
            fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.in_wishlist) {
                    btn.classList.add('on');
                    phToast('تمت الإضافة للمفضلة ❤️');
                } else {
                    btn.classList.remove('on');
                    phToast('تمت الإزالة من المفضلة');
                }
                const wBadge = document.getElementById('wishlistBadge');
                if (wBadge) {
                    if (data.count > 0) {
                        wBadge.textContent = data.count;
                        wBadge.style.display = '';
                    } else {
                        wBadge.style.display = 'none';
                    }
                }
            })
            .catch(() => {
                window.location = '{{ route("store.login") }}';
            });
        }

        // Add to cart
        function addToCart(productId, qty = 1) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, qty: qty })
            })
            .then(r => r.json())
            .then(data => {
                updateCartBadge(data.count);
                phToast('تمت الإضافة للسلة 🛒');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
