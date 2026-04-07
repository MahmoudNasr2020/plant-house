@extends('admin.layouts.app')

@section('title', $customer->name)
@section('page_title', '👤 ملف العميل')

@section('topbar_actions')
    <a href="{{ route('admin.customers.index') }}" class="btn-s">
        <i class="fa fa-arrow-right"></i> العودة
    </a>
    <button class="btn-p" style="background:var(--red)"
            onclick="document.getElementById('deleteModal').classList.add('on')">
        <i class="fa fa-trash"></i> حذف العميل
    </button>
@endsection

@section('content')

    <div style="display:grid;grid-template-columns:300px 1fr;gap:18px;align-items:start">

        {{-- ── PROFILE CARD ── --}}
        <div class="fsec" style="text-align:center">
            <div style="width:80px;height:80px;background:var(--gf);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:34px;margin:0 auto 14px">
                👤
            </div>
            <div style="font-size:18px;font-weight:900;color:var(--gd);margin-bottom:4px">{{ $customer->name }}</div>
            <div style="font-size:12.5px;color:#9aa89e;margin-bottom:18px">عميل منذ {{ $customer->created_at->format('Y') }}</div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px">
                <div class="kcard">
                    <div class="kval">{{ $orders->total() }}</div>
                    <div class="klbl">طلب</div>
                </div>
                <div class="kcard">
                    <div class="kval">{{ number_format($customer->total_spent, 0) }}</div>
                    <div class="klbl">ر.ق</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:9px;text-align:right">
                <div style="display:flex;align-items:center;gap:9px;font-size:13px">
                    <i class="fa fa-envelope" style="color:var(--gb);width:16px"></i>
                    <span>{{ $customer->email }}</span>
                </div>
                @if($customer->phone)
                    <div style="display:flex;align-items:center;gap:9px;font-size:13px">
                        <i class="fa fa-phone" style="color:var(--gb);width:16px"></i>
                        <span>{{ $customer->phone }}</span>
                    </div>
                @endif
                @if($customer->city)
                    <div style="display:flex;align-items:center;gap:9px;font-size:13px">
                        <i class="fa fa-map-marker-alt" style="color:var(--gb);width:16px"></i>
                        <span>{{ $customer->city }}{{ $customer->address ? '، '.$customer->address : '' }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── ORDERS ── --}}
        <div class="tcard">
            <div class="thdr">
                <h4>طلبات العميل ({{ $orders->total() }})</h4>
            </div>
            <table class="dtbl">
                <thead>
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><b>{{ $order->reference }}</b></td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td><b>{{ number_format($order->total, 2) }} ر.ق</b></td>
                        <td>
                            @switch($order->status)
                                @case('delivered')  <span class="sbadge sd">✅ مكتمل</span>   @break
                                @case('pending')    <span class="sbadge sp">⏳ معلق</span>    @break
                                @case('processing') <span class="sbadge spr2">🔄 معالجة</span> @break
                                @case('shipped')    <span class="sbadge spr2">🚚 شحن</span>   @break
                                @case('cancelled')  <span class="sbadge sc">❌ ملغي</span>    @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="aico" title="عرض">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:#9aa89e;padding:22px">لا توجد طلبات</td></tr>
                @endforelse
                </tbody>
            </table>

            @if($orders->hasPages())
                <div class="pgn">
                    <span class="pgn-info">{{ $orders->firstItem() }}-{{ $orders->lastItem() }} من {{ $orders->total() }}</span>
                    <div class="pgn-btns">{{ $orders->links('admin.partials.pagination') }}</div>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('modals')
    <div class="mov" id="deleteModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('deleteModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">
                هل أنت متأكد من حذف العميل <b>{{ $customer->name }}</b> وجميع بياناته؟
            </p>
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px"
                            onclick="document.getElementById('deleteModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
@endpush
