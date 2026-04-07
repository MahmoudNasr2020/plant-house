@extends('store.layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')

<div style="max-width:440px;margin:52px auto;padding:0 24px 56px">
    <div style="background:#fff;border-radius:22px;padding:36px 30px;box-shadow:0 10px 40px rgba(26,58,42,.10)">

        <div style="text-align:center;margin-bottom:26px">
            <div style="width:58px;height:58px;background:linear-gradient(135deg,var(--gb),var(--gd));border-radius:15px;display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:14px">🌿</div>
            <h1 style="font-size:24px;font-weight:900;color:var(--gd);margin-bottom:5px">مرحباً بعودتك</h1>
            <p style="font-size:13.5px;color:#9aa89e">سجل دخولك لمتابعة التسوق</p>
        </div>

        @if($errors->any())
            <div style="background:#fff0f0;color:#c0392b;border:1.5px solid #f5c6c6;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:8px">
                <i class="fa fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('store.login.post') }}">
            @csrf

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:14px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">البريد الإلكتروني</label>
                <div style="position:relative">
                    <i class="fa fa-envelope" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#9aa89e"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           style="width:100%;border:2px solid var(--gp);border-radius:11px;padding:12px 42px 12px 14px;font-family:inherit;font-size:14px;outline:none;transition:.2s"
                           onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:14px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">كلمة المرور</label>
                <div style="position:relative">
                    <i class="fa fa-lock" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#9aa89e"></i>
                    <input type="password" name="password" required placeholder="••••••••"
                           style="width:100%;border:2px solid var(--gp);border-radius:11px;padding:12px 42px 12px 14px;font-family:inherit;font-size:14px;outline:none;transition:.2s"
                           onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
                </div>
            </div>

            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:#2d4a3a;cursor:pointer;margin-bottom:18px">
                <input type="checkbox" name="remember" style="width:16px;height:16px;accent-color:var(--gb)">
                تذكرني
            </label>

            <button type="submit" style="width:100%;background:var(--gd);color:#fff;border:none;padding:13px;border-radius:50px;font-family:inherit;font-size:14.5px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s"
                    onmouseover="this.style.background='var(--gb)'" onmouseout="this.style.background='var(--gd)'">
                <i class="fa fa-sign-in-alt"></i> تسجيل الدخول
            </button>
        </form>

        <div style="text-align:center;margin-top:22px;font-size:13.5px;color:#9aa89e">
            ليس لديك حساب؟
            <a href="{{ route('store.register') }}" style="color:var(--gb);font-weight:800;text-decoration:none">إنشاء حساب جديد</a>
        </div>
    </div>
</div>

@endsection
