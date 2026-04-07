@extends('admin.layouts.app')

@section('title', 'بياناتي')
@section('page_title', '👤 ملفي الشخصي')

@section('content')

    @if(session('success'))
        <div style="background:#e8f7ef;border:2px solid var(--gp);color:var(--gd);padding:12px 16px;border-radius:12px;margin-bottom:16px;font-weight:700">
            <i class="fa fa-check-circle" style="color:var(--gb)"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fff0f0;border:2px solid #f8c7c0;color:#c0392b;padding:14px 18px;border-radius:12px;margin-bottom:16px">
            <ul style="margin:0;padding-inline-start:18px;font-size:13px;font-weight:700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

            {{-- Personal info --}}
            <div class="fsec">
                <h3><i class="fa fa-user"></i> البيانات الشخصية</h3>

                <div class="fg">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                </div>
                <div class="fg">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                </div>
                <div class="fg">
                    <label>الدور</label>
                    <input type="text" value="{{ $admin->role_label }}" disabled style="background:#f4f8f5;color:#6b7a70">
                </div>
            </div>

            {{-- Password --}}
            <div class="fsec">
                <h3><i class="fa fa-lock"></i> تغيير كلمة المرور</h3>
                <p style="font-size:12.5px;color:#9aa89e;margin-bottom:10px">اتركها فارغة إذا كنت لا تريد تغييرها</p>

                <div class="fg">
                    <label>كلمة المرور الحالية</label>
                    <input type="password" name="current_password" placeholder="••••••••">
                </div>
                <div class="fg">
                    <label>كلمة المرور الجديدة</label>
                    <input type="password" name="password" placeholder="حد أدنى 8 أحرف">
                </div>
                <div class="fg">
                    <label>تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation">
                </div>
            </div>

        </div>

        <div style="margin-top:12px;display:flex;gap:11px;justify-content:flex-end">
            <button type="submit" class="btn-p" style="padding:12px 28px;font-size:14px">
                <i class="fa fa-save"></i> حفظ التعديلات
            </button>
        </div>
    </form>

@endsection
