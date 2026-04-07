@extends('store.layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')

<div style="max-width:480px;margin:52px auto;padding:0 24px 56px">
    <div style="background:#fff;border-radius:22px;padding:36px 30px;box-shadow:0 10px 40px rgba(26,58,42,.10)">

        <div style="text-align:center;margin-bottom:26px">
            <div style="width:58px;height:58px;background:linear-gradient(135deg,var(--gb),var(--gd));border-radius:15px;display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:14px">🌿</div>
            <h1 style="font-size:24px;font-weight:900;color:var(--gd);margin-bottom:5px">انضم إلينا</h1>
            <p style="font-size:13.5px;color:#9aa89e">أنشئ حسابك في دقيقة واحدة</p>
        </div>

        @if($errors->any())
            <div style="background:#fff0f0;color:#c0392b;border:1.5px solid #f5c6c6;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:16px">
                @foreach($errors->all() as $e)
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
                        <i class="fa fa-exclamation-circle"></i> {{ $e }}
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('store.register.post') }}">
            @csrf

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:13px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">الاسم الكامل</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       style="border:2px solid var(--gp);border-radius:11px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none"
                       onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:13px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="border:2px solid var(--gp);border-radius:11px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none"
                       onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:13px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">رقم الهاتف</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+974 XXXX XXXX"
                       style="border:2px solid var(--gp);border-radius:11px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none"
                       onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:13px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">كلمة المرور</label>
                <input type="password" name="password" required minlength="8" placeholder="8 أحرف على الأقل"
                       style="border:2px solid var(--gp);border-radius:11px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none"
                       onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:18px">
                <label style="font-size:12.5px;font-weight:700;color:#2d4a3a">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       style="border:2px solid var(--gp);border-radius:11px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none"
                       onfocus="this.style.borderColor='var(--gb)'" onblur="this.style.borderColor='var(--gp)'">
            </div>

            <button type="submit" style="width:100%;background:var(--gd);color:#fff;border:none;padding:13px;border-radius:50px;font-family:inherit;font-size:14.5px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s"
                    onmouseover="this.style.background='var(--gb)'" onmouseout="this.style.background='var(--gd)'">
                <i class="fa fa-user-plus"></i> إنشاء الحساب
            </button>
        </form>

        <div style="text-align:center;margin-top:22px;font-size:13.5px;color:#9aa89e">
            لديك حساب بالفعل؟
            <a href="{{ route('store.login') }}" style="color:var(--gb);font-weight:800;text-decoration:none">سجل الدخول</a>
        </div>
    </div>
</div>

@endsection
