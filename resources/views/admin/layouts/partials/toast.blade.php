<div class="toast" id="adminToast">
    <i class="fa fa-check-circle"></i>
    <span id="adminToastMsg">
        {{-- JS message injected here --}}
    </span>
</div>

{{-- Server-side flash messages --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast('✅ {{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast('❌ {{ session('error') }}');
        });
    </script>
@endif

<style>
    .toast {
        position: fixed;
        bottom: 26px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: var(--gd);
        color: #fff;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 8px 26px rgba(0, 0, 0, .22);
        transition: .3s;
        pointer-events: none;
    }

    .toast.on {
        transform: translateX(-50%) translateY(0);
    }

    .toast i { color: var(--gp); }
</style>

<script>
    /**
     * Show an admin toast notification.
     * @param {string} msg
     * @param {number} duration  ms (default 3000)
     */
    function showToast(msg, duration = 3000) {
        const toast = document.getElementById('adminToast');
        const span  = document.getElementById('adminToastMsg');
        if (!toast || !span) return;
        span.textContent = msg;
        toast.classList.add('on');
        setTimeout(() => toast.classList.remove('on'), duration);
    }
</script>
