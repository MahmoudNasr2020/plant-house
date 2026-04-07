@extends('admin.layouts.app')

@section('title', 'التقارير')
@section('page_title', '📊 التقارير والإحصاءات')

@section('content')

    {{-- ── KPIs ── --}}
    <div class="kpis" style="margin-bottom:18px">

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kg"><i class="fa fa-calendar-day" style="color:var(--gb)"></i></div>
                <span class="kchg kup">اليوم</span>
            </div>
            <div class="kval">{{ number_format($todaySales, 0) }}</div>
            <div class="klbl">مبيعات اليوم (ر.ق)</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico ko"><i class="fa fa-calendar-alt" style="color:#e76f51"></i></div>
            </div>
            <div class="kval">{{ number_format($monthlySales, 0) }}</div>
            <div class="klbl">مبيعات الشهر (ر.ق)</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kv"><i class="fa fa-calendar" style="color:#7b5ea7"></i></div>
            </div>
            <div class="kval">{{ number_format($yearlySales, 0) }}</div>
            <div class="klbl">مبيعات العام (ر.ق)</div>
        </div>

        <div class="kcard">
            <div class="kcdr">
                <div class="kico kb"><i class="fa fa-chart-bar" style="color:#457b9d"></i></div>
                @if($lastMonthSales > 0)
                    @php $growth = round((($monthlySales - $lastMonthSales) / $lastMonthSales) * 100, 1); @endphp
                    <span class="kchg {{ $growth >= 0 ? 'kup' : 'kdn' }}">
                        {{ $growth >= 0 ? '▲' : '▼' }} {{ abs($growth) }}%
                    </span>
                @endif
            </div>
            <div class="kval">{{ number_format($lastMonthSales, 0) }}</div>
            <div class="klbl">مبيعات الشهر الماضي (ر.ق)</div>
        </div>

    </div>

    {{-- ── CHARTS ── --}}
    <div class="charts" style="margin-bottom:18px">

        {{-- Monthly bar chart --}}
        <div class="ccard">
            <h4>📊 مقارنة الشهر الحالي بالسابق</h4>
            <div class="barchart" style="margin-top:12px">
                @php
                    $max = max($monthlySales, $lastMonthSales, 1);
                    $months = [
                        ['label' => 'الشهر الماضي', 'value' => $lastMonthSales],
                        ['label' => 'الشهر الحالي', 'value' => $monthlySales],
                    ];
                @endphp
                @foreach($months as $m)
                    <div class="bw" style="flex:0 0 80px">
                        <div class="bar"
                             style="height:{{ max(4, round(($m['value'] / $max) * 120)) }}px"
                             data-v="{{ number_format($m['value'], 0) }} ر.ق"></div>
                        <span class="blbl">{{ $m['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pending orders --}}
        <div class="ccard">
            <h4>⏳ الطلبات المعلقة</h4>
            <div style="display:flex;align-items:center;justify-content:center;height:130px">
                <div style="text-align:center">
                    <div style="font-size:54px;font-weight:900;color:var(--gd)">{{ $pendingOrders }}</div>
                    <div style="font-size:13px;color:#9aa89e;margin-top:4px">طلب ينتظر المعالجة</div>
                    @if($pendingOrders > 0)
                        <a href="{{ route('admin.orders.index') }}" class="btn-p" style="margin-top:12px;font-size:12px;padding:7px 16px">
                            معالجة الطلبات
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── TWO-COLUMN: STATUS + TOP PRODUCTS ── --}}
    <div class="charts" style="margin-bottom:18px">

        {{-- Orders by Status --}}
        <div class="ccard">
            <h4>📦 الطلبات حسب الحالة</h4>
            @php
                $statusLabels = [
                    'delivered'  => ['✅ مكتمل', '#40916c'],
                    'processing' => ['🔄 معالجة', '#f4a261'],
                    'shipped'    => ['🚚 في الشحن', '#457b9d'],
                    'pending'    => ['⏳ معلق', '#e9c46a'],
                    'cancelled'  => ['❌ ملغي', '#e76f51'],
                ];
                $totalOrdersStat = max(1, $totalOrders);
            @endphp
            <div style="margin-top:12px">
                @foreach($statusLabels as $k => $meta)
                    @php $c = $ordersByStatus[$k] ?? 0; $pct = round(($c / $totalOrdersStat) * 100); @endphp
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;font-size:13px">
                        <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $meta[1] }};margin-left:6px"></span>{{ $meta[0] }}</span>
                        <b style="color:var(--gd)">{{ $c }} ({{ $pct }}%)</b>
                    </div>
                    <div style="height:6px;background:var(--gf);border-radius:4px;overflow:hidden;margin-bottom:9px">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $meta[1] }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top Products --}}
        <div class="ccard">
            <h4>🏆 أكثر المنتجات مبيعاً</h4>
            <table class="dtbl" style="margin-top:8px">
                <thead>
                <tr><th>#</th><th>المنتج</th><th>الكمية</th></tr>
                </thead>
                <tbody>
                @forelse($topProducts as $i => $p)
                    <tr>
                        <td><b>{{ $i + 1 }}</b></td>
                        <td>{{ \Illuminate\Support\Str::limit($p->name, 30) }}</td>
                        <td><b>{{ $p->order_items_count }}</b></td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align:center;color:#9aa89e;padding:20px">لا توجد مبيعات بعد</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- ── SUMMARY ── --}}
    <div class="fsec">
        <h3><i class="fa fa-table"></i> ملخص المبيعات (باستثناء الملغاة)</h3>
        <table class="dtbl">
            <thead>
            <tr>
                <th>الفترة</th>
                <th>المبيعات (ر.ق)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>اليوم</td>
                <td><b>{{ number_format($todaySales, 2) }}</b></td>
            </tr>
            <tr>
                <td>الشهر الحالي</td>
                <td><b>{{ number_format($monthlySales, 2) }}</b></td>
            </tr>
            <tr>
                <td>الشهر الماضي</td>
                <td><b>{{ number_format($lastMonthSales, 2) }}</b></td>
            </tr>
            <tr>
                <td>العام الحالي ({{ now()->format('Y') }})</td>
                <td><b>{{ number_format($yearlySales, 2) }}</b></td>
            </tr>
            <tr style="background:var(--gf)">
                <td><b>إيرادات مكتملة (Delivered)</b></td>
                <td><b style="color:var(--gb)">{{ number_format($totalRevenue, 2) }}</b></td>
            </tr>
            <tr>
                <td>متوسط قيمة الطلب</td>
                <td><b>{{ number_format($avgOrderValue, 2) }}</b></td>
            </tr>
            <tr>
                <td>إجمالي العملاء</td>
                <td><b>{{ number_format($totalCustomers) }}</b></td>
            </tr>
            </tbody>
        </table>
    </div>

@endsection
