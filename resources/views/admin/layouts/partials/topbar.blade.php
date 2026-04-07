<div class="ctopbar">
    <h2>@yield('page_title', 'لوحة المراقبة')</h2>

    <div class="ctopbar-actions">
        @yield('topbar_actions')

        {{-- Profile dropdown --}}
        @auth
            <div class="admin-profile-menu" style="position:relative">
                <button type="button" class="btn-s" onclick="this.nextElementSibling.classList.toggle('on')" style="display:inline-flex;align-items:center;gap:8px">
                    <span style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,var(--gb),var(--gd));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:900;font-size:12px">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </span>
                    <span style="font-weight:700">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    <i class="fa fa-chevron-down" style="font-size:10px"></i>
                </button>
                <div class="apm-dropdown" style="display:none;position:absolute;top:calc(100% + 6px);left:0;background:#fff;border:1px solid var(--gf);border-radius:12px;box-shadow:0 8px 24px rgba(26,58,42,.12);min-width:200px;z-index:100;overflow:hidden">
                    <a href="{{ route('admin.profile') }}" style="display:flex;align-items:center;gap:9px;padding:11px 14px;color:#2d4a3a;font-size:13px;font-weight:700;text-decoration:none;border-bottom:1px solid var(--gf)">
                        <i class="fa fa-user-edit" style="color:var(--gb);width:15px"></i> تعديل بياناتي
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
                        @csrf
                        <button type="submit" style="width:100%;display:flex;align-items:center;gap:9px;padding:11px 14px;color:var(--red);font-size:13px;font-weight:700;background:none;border:none;font-family:inherit;cursor:pointer;text-align:right">
                            <i class="fa fa-sign-out-alt" style="width:15px"></i> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</div>

<style>
    .apm-dropdown.on { display: block !important; }
</style>
<script>
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.apm-dropdown.on').forEach(d => {
            if (!d.parentElement.contains(e.target)) d.classList.remove('on');
        });
    });
</script>
