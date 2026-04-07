@extends('admin.layouts.app')

@section('title', 'إدارة العملاء')
@section('page_title', '👥 إدارة العملاء')

@section('topbar_actions')
    <button class="btn-s" id="exportCustomersBtn">
        <i class="fa fa-download"></i> تصدير CSV
    </button>
    <button class="btn-p" onclick="openCustomerModal()">
        <i class="fa fa-user-plus"></i> إضافة عميل
    </button>
@endsection

@section('content')

    {{-- ── CUSTOMERS TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>العملاء ({{ $totalCustomers ?? 1842 }})</h4>
            <div class="thdr-acts">
                <input class="tsrch" type="text" placeholder="بحث بالاسم أو البريد..." id="customerSearch">
            </div>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الهاتف</th>
                <th>الطلبات</th>
                <th>إجمالي الإنفاق</th>
                <th>تاريخ التسجيل</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="customersTbody">
            @forelse($customers ?? [] as $customer)
                <tr>
                    <td><b>{{ $customer->name }}</b></td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->orders_count ?? 0 }}</td>
                    <td><b>{{ number_format($customer->total_spent ?? 0) }} ر.ق</b></td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="aico" title="عرض">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="mailto:{{ $customer->email }}" class="aico" title="مراسلة">
                            <i class="fa fa-envelope"></i>
                        </a>
                        <button class="aico" title="حذف"
                                style="color:var(--red)"
                                onclick="confirmDeleteCustomer('{{ route('admin.customers.destroy', $customer) }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا يوجد عملاء</td></tr>
            @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if(isset($customers) && $customers->hasPages())
            <div class="pgn">
                <span class="pgn-info">
                    {{ $customers->firstItem() }}-{{ $customers->lastItem() }} من {{ $customers->total() }} عميل
                </span>
                <div class="pgn-btns">
                    {{ $customers->links('admin.partials.pagination') }}
                </div>
            </div>
        @endif

    </div>

@endsection

@push('modals')

    {{-- ── ADD CUSTOMER MODAL ── --}}
    <div class="mov" id="customerModal">
        <div class="modal" style="width:500px">
            <button class="mcls" onclick="closeCustomerModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-user-plus" style="color:var(--gb)"></i> إضافة عميل جديد
            </div>

            <form id="customerForm" method="POST" action="{{ route('admin.customers.store') }}">
                @csrf

                <div class="frow">
                    <div class="fg">
                        <label>الاسم</label>
                        <input type="text" name="name" placeholder="أحمد الخليفي" required>
                    </div>
                    <div class="fg">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" placeholder="ahmed@example.com" required>
                    </div>
                </div>

                <div class="fg">
                    <label>رقم الهاتف</label>
                    <input type="tel" name="phone" placeholder="+974 5555 1234">
                </div>

                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;font-size:14.5px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ العميل
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeCustomerModal()">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE CONFIRMATION ── --}}
    <div class="mov" id="deleteCustomerModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="closeDeleteCustomerModal()">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">هل أنت متأكد من حذف هذا العميل وجميع بياناته؟</p>
            <form id="deleteCustomerForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px" onclick="closeDeleteCustomerModal()">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
    <script>
        // ── Modal helpers ─────────────────────────────────────────────
        function openCustomerModal() {
            document.getElementById('customerModal').classList.add('on');
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').classList.remove('on');
        }

        function confirmDeleteCustomer(actionUrl) {
            document.getElementById('deleteCustomerForm').action = actionUrl;
            document.getElementById('deleteCustomerModal').classList.add('on');
        }

        function closeDeleteCustomerModal() {
            document.getElementById('deleteCustomerModal').classList.remove('on');
        }

        // ── Live search ───────────────────────────────────────────────
        (function () {
            const searchEl = document.getElementById('customerSearch');
            const tbody    = document.getElementById('customersTbody');
            if (!searchEl || !tbody) return;

            searchEl.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                tbody.querySelectorAll('tr').forEach(row => {
                    row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
                });
            });
        })();

        // ── CSV Export ────────────────────────────────────────────────
        document.getElementById('exportCustomersBtn')?.addEventListener('click', function () {
            const rows   = document.querySelectorAll('#customersTbody tr');
            const header = ['الاسم', 'البريد', 'الهاتف', 'الطلبات', 'الإنفاق', 'التسجيل'];
            const lines  = [header.join(',')];
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                const cells = Array.from(row.querySelectorAll('td')).slice(0, 6);
                lines.push(cells.map(c => `"${c.textContent.trim()}"`).join(','));
            });
            const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8' });
            const a    = document.createElement('a');
            a.href     = URL.createObjectURL(blob);
            a.download = 'customers.csv';
            a.click();
        });
    </script>
@endpush
