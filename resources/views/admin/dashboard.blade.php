@extends('admin.layouts.app')

@section('title', 'لوحة المراقبة')
@section('page_title', '👋 لوحة المراقبة')

@section('topbar_actions')
    <button class="btn-s">
        <i class="fa fa-download"></i> تصدير
    </button>
    <a href="{{ route('admin.products.create') }}" class="btn-p">
        <i class="fa fa-plus"></i> إضافة منتج
    </a>
@endsection

@section('content')

    {{-- ── KPI CARDS ── --}}
    <div class="kpis">

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kg">💰</div>
                <span class="kchg kup">هذا الشهر</span>
            </div>
            <div class="kval">{{ number_format($monthlySales, 2) }} ر.ق</div>
            <div class="klbl">مبيعات هذا الشهر</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kb">📦</div>
                <span class="kchg kup">{{ $pendingOrders }} معلق</span>
            </div>
            <div class="kval">{{ number_format($totalOrders) }}</div>
            <div class="klbl">إجمالي الطلبات</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico ko">👥</div>
                <span class="kchg kup">عميل</span>
            </div>
            <div class="kval">{{ number_format($totalCustomers) }}</div>
            <div class="klbl">إجمالي العملاء</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kv">🔁</div>
                <span class="kchg kup">متوسط</span>
            </div>
            <div class="kval">{{ number_format($avgOrderValue, 2) }} ر.ق</div>
            <div class="klbl">متوسط قيمة الطلب</div>
        </div>

    </div>

    {{-- ── MINI KPIs ── --}}
    <div class="kpis" style="margin-top:18px">
        <div class="kcard">
            <div class="kcdr"><div class="kico kg">🗓️</div><span class="kchg kup">اليوم</span></div>
            <div class="kval">{{ number_format($todaySales, 2) }} ر.ق</div>
            <div class="klbl">مبيعات اليوم</div>
        </div>
        <div class="kcard">
            <div class="kcdr"><div class="kico kb">📦</div></div>
            <div class="kval">{{ number_format($totalProducts) }}</div>
            <div class="klbl">إجمالي المنتجات</div>
        </div>
        <div class="kcard">
            <div class="kcdr"><div class="kico ko">⚠️</div></div>
            <div class="kval">{{ number_format($lowStock) }}</div>
            <div class="klbl">منتجات منخفضة المخزون</div>
        </div>
        <div class="kcard">
            <div class="kcdr"><div class="kico kv">⏳</div></div>
            <div class="kval">{{ number_format($pendingOrders) }}</div>
            <div class="klbl">طلبات قيد المعالجة</div>
        </div>
    </div>

    {{-- ── CHARTS ROW ── --}}
    <div class="charts">

        {{-- Monthly Sales Bar Chart (last 6 months) --}}
        <div class="ccard">
            <h4><i class="fa fa-chart-bar" style="color:var(--gb)"></i> المبيعات خلال آخر 6 شهور (ر.ق)</h4>
            <div class="barchart" id="weekChart" data-revenue='@json($revenueByMonth)'></div>
        </div>

        {{-- Order Status Breakdown --}}
        <div class="ccard">
            <h4><i class="fa fa-chart-pie" style="color:var(--gb)"></i> حسب حالة الطلب</h4>
            @php
                $statusMap = [
                    'delivered'  => ['label' => '✅ مكتمل', 'color' => '#40916c'],
                    'processing' => ['label' => '🔄 معالجة', 'color' => '#f4a261'],
                    'pending'    => ['label' => '⏳ معلق', 'color' => '#e9c46a'],
                    'shipped'    => ['label' => '🚚 في الشحن', 'color' => '#457b9d'],
                    'cancelled'  => ['label' => '❌ ملغي', 'color' => '#e76f51'],
                ];
                $statusCounts = \App\Models\Order::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
                $totalOrdersForChart = max(1, $statusCounts->sum());
            @endphp
            <div class="dleg" style="width:100%;margin-top:10px">
                @foreach($statusMap as $k => $v)
                    @php $count = $statusCounts[$k] ?? 0; $pct = round(($count / $totalOrdersForChart) * 100); @endphp
                    <div class="di" style="justify-content:space-between;display:flex;gap:8px;margin-bottom:8px">
                        <span><span class="dd" style="background:{{ $v['color'] }}"></span> {{ $v['label'] }}</span>
                        <b style="color:var(--gd)">{{ $count }} ({{ $pct }}%)</b>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── RECENT ORDERS TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4><i class="fa fa-shopping-bag" style="color:var(--gb)"></i> آخر الطلبات</h4>
            <div class="thdr-acts">
                <input class="tsrch" type="text" placeholder="بحث بالاسم أو الرقم..." id="dashOrderSearch">
                <select class="tfltr" id="dashOrderFilter">
                    <option value="">الكل</option>
                    <option value="delivered">مكتمل</option>
                    <option value="pending">معلق</option>
                    <option value="processing">معالجة</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>الدفع</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="dashOrdersTbody">
            @forelse($recentOrders ?? [] as $order)
                <tr data-status="{{ $order->status }}">
                    <td><b>{{ $order->reference }}</b></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td><b>{{ number_format($order->total, 2) }} ر.ق</b></td>
                    <td>{{ $order->payment_method === 'cash' ? 'عند الاستلام' : $order->payment_method }}</td>
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
                        <a href="{{ route('admin.orders.show', $order) }}" class="aico"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا توجد طلبات بعد</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Monthly bar chart (last 6 months) ─────────────────────────
        (function () {
            const el = document.getElementById('weekChart');
            if (!el) return;
            const data = JSON.parse(el.dataset.revenue || '[]');
            if (!data.length) { el.innerHTML = '<p style="color:#9aa89e;padding:20px;text-align:center;width:100%">لا توجد بيانات</p>'; return; }
            const max = Math.max(...data.map(d => d.value), 1);
            el.innerHTML = data.map(d => `
                <div class="bw">
                    <div class="bar" data-v="${Math.round(d.value).toLocaleString()} ر.ق"
                         style="height:${Math.max(4, (d.value / max) * 100)}%"></div>
                    <span class="blbl">${d.label}</span>
                </div>
            `).join('');
        })();

        // ── Dashboard order search / filter ──────────────────────────
        (function () {
            const searchEl = document.getElementById('dashOrderSearch');
            const filterEl = document.getElementById('dashOrderFilter');
            const tbody    = document.getElementById('dashOrdersTbody');
            if (!searchEl || !filterEl || !tbody) return;

            function applyFilters() {
                const q      = searchEl.value.trim().toLowerCase();
                const status = filterEl.value;
                tbody.querySelectorAll('tr').forEach(row => {
                    const text     = row.textContent.toLowerCase();
                    const rowStatus = row.dataset.status || '';
                    const matchQ   = !q || text.includes(q);
                    const matchS   = !status || rowStatus === status;
                    row.style.display = (matchQ && matchS) ? '' : 'none';
                });
            }

            searchEl.addEventListener('input', applyFilters);
            filterEl.addEventListener('change', applyFilters);
        })();
    </script>
@endpush
