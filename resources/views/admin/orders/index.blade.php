@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')
@section('page_title', '🛍️ إدارة الطلبات')

@section('topbar_actions')
    <button class="btn-s" onclick="window.print()">
        <i class="fa fa-print"></i> طباعة
    </button>
    <button class="btn-s" id="exportBtn">
        <i class="fa fa-download"></i> تصدير CSV
    </button>
@endsection

@section('content')

    {{-- ── STATUS KPI CARDS ── --}}
    <div class="kpis" style="margin-bottom:18px">

        <div class="kcard">
            <div class="kcdr"><div class="kico kb">⏳</div></div>
            <div class="kval">{{ $processingCount ?? 8 }}</div>
            <div class="klbl">قيد المعالجة</div>
        </div>

        <div class="kcard">
            <div class="kcdr"><div class="kico ko">🚚</div></div>
            <div class="kval">{{ $shippingCount ?? 23 }}</div>
            <div class="klbl">في الشحن</div>
        </div>

        <div class="kcard">
            <div class="kcdr"><div class="kico kg">✅</div></div>
            <div class="kval">{{ $deliveredCount ?? 356 }}</div>
            <div class="klbl">مكتملة</div>
        </div>

        <div class="kcard">
            <div class="kcdr"><div class="kico" style="background:#fff0f0">❌</div></div>
            <div class="kval">{{ $cancelledCount ?? 12 }}</div>
            <div class="klbl">ملغاة</div>
        </div>

    </div>

    {{-- ── ORDERS TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>جميع الطلبات ({{ $totalOrders ?? 399 }})</h4>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="thdr-acts" style="display:flex;gap:8px;align-items:center">
                <input class="tsrch" type="text" name="q" placeholder="بحث برقم الطلب أو اسم العميل..." value="{{ $searchQuery ?? '' }}" id="orderSearch">
                <select class="tfltr" name="status" id="orderStatusFilter" onchange="this.form.submit()">
                    <option value="">كل الحالات</option>
                    <option value="delivered"  {{ ($statusFilter ?? '') === 'delivered'  ? 'selected' : '' }}>مكتمل</option>
                    <option value="shipped"    {{ ($statusFilter ?? '') === 'shipped'    ? 'selected' : '' }}>في الشحن</option>
                    <option value="processing" {{ ($statusFilter ?? '') === 'processing' ? 'selected' : '' }}>معالجة</option>
                    <option value="pending"    {{ ($statusFilter ?? '') === 'pending'    ? 'selected' : '' }}>معلق</option>
                    <option value="cancelled"  {{ ($statusFilter ?? '') === 'cancelled'  ? 'selected' : '' }}>ملغي</option>
                </select>
                <button type="submit" class="btn-s" style="padding:8px 14px"><i class="fa fa-search"></i></button>
                @if(($searchQuery ?? '') || ($statusFilter ?? ''))
                    <a href="{{ route('admin.orders.index') }}" class="btn-s" style="padding:8px 12px" title="إعادة ضبط"><i class="fa fa-times"></i></a>
                @endif
            </form>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>الشحن</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="ordersTbody">
            @forelse($orders ?? [] as $order)
                <tr data-status="{{ $order->status }}">
                    <td><b>{{ $order->reference }}</b></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td><b>{{ number_format($order->total, 2) }} ر.ق</b></td>
                    <td>{{ $order->city ?? 'الدوحة' }}</td>
                    <td>
                        @switch($order->status)
                            @case('delivered')  <span class="sbadge sd">✅ مكتمل</span>   @break
                            @case('pending')    <span class="sbadge sp">⏳ معلق</span>    @break
                            @case('processing') <span class="sbadge spr2">🔄 معالجة</span> @break
                            @case('shipped')    <span class="sbadge" style="background:#fff0eb;color:#c9580e">🚚 في الشحن</span> @break
                            @case('cancelled')  <span class="sbadge sc">❌ ملغي</span>    @break
                            @default            <span class="sbadge sp">{{ $order->status }}</span>
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="aico" title="عرض"><i class="fa fa-eye"></i></a>
                        <button class="aico" title="حذف"
                                style="color:var(--red)"
                                onclick="confirmDelete('{{ route('admin.orders.destroy', $order) }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا توجد طلبات</td></tr>
            @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if(isset($orders) && $orders->hasPages())
            <div class="pgn">
                <span class="pgn-info">
                    {{ $orders->firstItem() }}-{{ $orders->lastItem() }} من {{ $orders->total() }} طلب
                </span>
                <div class="pgn-btns">
                    {{ $orders->links('admin.partials.pagination') }}
                </div>
            </div>
        @endif

    </div>

@endsection

@push('modals')
    {{-- Delete confirmation modal --}}
    <div class="mov" id="deleteModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="closeDeleteModal()">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">هل أنت متأكد من حذف هذا الطلب؟ لا يمكن التراجع.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px" onclick="closeDeleteModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        // ── Delete modal ─────────────────────────────────────────────
        function confirmDelete(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').classList.add('on');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('on');
        }

        // ── CSV Export ───────────────────────────────────────────────
        document.getElementById('exportBtn')?.addEventListener('click', function () {
            const rows   = document.querySelectorAll('#ordersTbody tr');
            const header = ['#', 'العميل', 'التاريخ', 'المبلغ', 'الشحن', 'الحالة'];
            const lines  = [header.join(',')];
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                const cells = Array.from(row.querySelectorAll('td')).slice(0, 6);
                lines.push(cells.map(c => `"${c.textContent.trim()}"`).join(','));
            });
            const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = 'orders.csv';
            a.click();
            URL.revokeObjectURL(url);
        });
    </script>
@endpush
