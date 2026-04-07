<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول — Plant House Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --gd:#1a3a2a; --gm:#2d6a4f; --gb:#40916c; --gp:#b7e4c7; --gf:#d8f3dc; --gold:#f4a261; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Tajawal',sans-serif; background:linear-gradient(135deg,var(--gd),var(--gm)); min-height:100vh; display:flex; align-items:center; justify-content:center; direction:rtl; }
        .card { background:#fff; border-radius:28px; padding:44px 42px; width:420px; box-shadow:0 24px 80px rgba(0,0,0,.28); }
        .logo { display:flex; align-items:center; gap:12px; margin-bottom:32px; }
        .logo-ico { width:52px; height:52px; background:linear-gradient(135deg,var(--gb),var(--gd)); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; }
        .logo-main { font-size:20px; font-weight:900; color:var(--gd); }
        .logo-sub { font-size:11px; color:var(--gb); letter-spacing:.5px; }
        h2 { font-size:22px; font-weight:900; color:var(--gd); margin-bottom:6px; }
        .sub { font-size:13px; color:#9aa89e; margin-bottom:28px; }
        .fg { display:flex; flex-direction:column; gap:5px; margin-bottom:16px; }
        .fg label { font-size:12.5px; font-weight:700; color:#2d4a3a; }
        .fg input { border:2px solid var(--gp); border-radius:10px; padding:12px 14px; font-family:inherit; font-size:14px; outline:none; transition:.2s; }
        .fg input:focus { border-color:var(--gb); box-shadow:0 0 0 4px rgba(64,145,108,.1); }
        .inp-ico { position:relative; }
        .inp-ico i { position:absolute; right:14px; top:50%; transform:translateY(-50%); color:#9aa89e; }
        .inp-ico input { padding-right:42px; width:100%; }
        .remember { display:flex; align-items:center; gap:8px; font-size:13px; color:#2d4a3a; cursor:pointer; margin-bottom:22px; }
        .remember input { width:16px; height:16px; accent-color:var(--gb); }
        .btn { width:100%; background:var(--gd); color:#fff; border:none; padding:14px; border-radius:12px; font-family:inherit; font-size:15px; font-weight:800; cursor:pointer; transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn:hover { background:var(--gb); }
        .err { background:#fff0f0; color:#c0392b; border:1.5px solid #f5c6c6; border-radius:9px; padding:10px 14px; font-size:13px; margin-bottom:18px; display:flex; align-items:center; gap:8px; }
        .badge { background:var(--gf); border-radius:11px; padding:12px 16px; margin-top:22px; font-size:12.5px; color:#2d4a3a; display:flex; align-items:center; gap:9px; }
        .badge i { color:var(--gb); }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-ico">🌿</div>
        <div>
            <div class="logo-main">Plant House</div>
            <div class="logo-sub">لوحة الإدارة</div>
        </div>
    </div>

    <h2>تسجيل الدخول</h2>
    <p class="sub">أدخل بيانات الدخول للوصول للوحة التحكم</p>

    @if($errors->any())
        <div class="err">
            <i class="fa fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="err" style="background:#e6f9ee;color:#1a7a45;border-color:#b7e4c7">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="fg">
            <label>البريد الإلكتروني</label>
            <div class="inp-ico">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@planthouse.qa" required autofocus>
            </div>
        </div>
        <div class="fg">
            <label>كلمة المرور</label>
            <div class="inp-ico">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
        </div>
        <label class="remember">
            <input type="checkbox" name="remember"> تذكرني
        </label>
        <button type="submit" class="btn">
            <i class="fa fa-sign-in-alt"></i> دخول
        </button>
    </form>

    <div class="badge">
        <i class="fa fa-shield-alt"></i>
        هذه الصفحة محمية ومخصصة للمديرين فقط
    </div>
</div>
</body>
</html>
