@extends('store.layouts.app')

@section('title', 'تعديل البيانات')

@section('content')

<div style="max-width:720px;margin:0 auto;padding:36px 24px 56px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
        <h1 style="font-size:24px;font-weight:900;color:var(--gd)">
            <i class="fa fa-user-edit" style="color:var(--gb);margin-left:8px"></i>
            تعديل البيانات
        </h1>
        <a href="{{ route('store.profile') }}" style="color:#9aa89e;font-size:13px;font-weight:700;text-decoration:none">
            <i class="fa fa-arrow-right"></i> رجوع
        </a>
    </div>

    @if($errors->any())
        <div style="background:#fff0f0;border:2px solid #f8c7c0;color:#c0392b;padding:14px 18px;border-radius:12px;margin-bottom:16px">
            <ul style="margin:0;padding-inline-start:18px;font-size:13px;font-weight:700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('store.profile.update') }}" style="background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(26,58,42,.08);padding:28px">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:var(--gd);margin-bottom:6px">الاسم الكامل *</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                       style="width:100%;border:2px solid var(--gp);border-radius:10px;padding:11px 14px;font-family:inherit;font-size:14px;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:var(--gd);margin-bottom:6px">البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                       style="width:100%;border:2px solid var(--gp);border-radius:10px;padding:11px 14px;font-family:inherit;font-size:14px;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:var(--gd);margin-bottom:6px">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="+974 5555 1234"
                       style="width:100%;border:2px solid var(--gp);border-radius:10px;padding:11px 14px;font-family:inherit;font-size:14px;outline:none">
            </div>
        </div>

        <hr style="border:none;border-top:2px solid var(--gf);margin:22px 0">

        <h3 style="font-size:15px;font-weight:900;color:var(--gd);margin-bottom:12px">
            <i class="fa fa-lock" style="color:var(--gb)"></i> تغيير كلمة المرور
        </h3>
        <p style="font-size:12.5px;color:#9aa89e;margin-bottom:14px">اتركها فارغة إذا كنت لا تريد تغييرها</p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px">
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:var(--gd);margin-bottom:6px">كلمة المرور الجديدة</label>
                <input type="password" name="password" placeholder="حد أدنى 8 أحرف"
                       style="width:100%;border:2px solid var(--gp);border-radius:10px;padding:11px 14px;font-family:inherit;font-size:14px;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:var(--gd);margin-bottom:6px">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation"
                       style="width:100%;border:2px solid var(--gp);border-radius:10px;padding:11px 14px;font-family:inherit;font-size:14px;outline:none">
            </div>
        </div>

        <div style="display:flex;gap:11px;justify-content:flex-end">
            <a href="{{ route('store.profile') }}" style="background:#fff;color:var(--gd);border:2px solid var(--gp);padding:11px 24px;border-radius:50px;font-size:14px;font-weight:700;text-decoration:none">إلغاء</a>
            <button type="submit" style="background:var(--gd);color:#fff;border:none;padding:12px 28px;border-radius:50px;font-size:14px;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;gap:7px">
                <i class="fa fa-save"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>

@endsection
