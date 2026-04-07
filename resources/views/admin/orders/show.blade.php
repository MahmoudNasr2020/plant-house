@extends('admin.layouts.app')

@section('title', 'طلب ' . $order->reference)
@section('page_title', '🛍️ تفاصيل الطلب')

@section('topbar_actions')
    <a href="{{ route('admin.orders.index') }}" class="btn-s">
        <i class="fa fa-arrow-right"></i> العودة
    </a>
    <button class="btn-p" onclick="document.getElementById('statusModal').classList.add('on')">
        <i class="fa fa-sync"></i> تغيير الحالة
    </button>
@endsection

@section('content')

    <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">

        {{-- ── ORDER ITEMS ── --}}
        <div>
            <div class="tcard" style="margin-bottom:18px">
                <div class="thdr">
                    <h4>منتجات الطلب ({{ $order->items->count() }})</h4>
                </div>
                <table class="dtbl">
                    <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td>
                                <div class="pcell">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}"
                                             onerror="this.src='https://via.placeholder.com/40/d8f3dc/2d6a4f?text=P'"
                                             alt="{{ $item->product_name }}">
                                    @endif
                                    <div>
                                        <div class="pn">{{ $item->product_name }}</div>
                                        <div class="pb">{{ $item->product_brand }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} ر.ق</td>
                            <td><b>{{ number_format($item->subtotal, 2) }} ر.ق</b></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#9aa89e;padding:22px">لا توجد منتجات</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── NOTES ── --}}
            @if($order->notes)
                <div class="fsec">
                    <h3><i class="fa fa-sticky-note"></i> ملاحظات</h3>
                    <p style="font-size:14px;color:#2d4a3a;line-height:1.7">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- ── SIDEBAR ── --}}
        <div>
            {{-- Order info --}}
            <div class="fsec" style="margin-bottom:14px">
                <h3><i class="fa fa-receipt"></i> معلومات الطلب</h3>

                <div style="display:flex;flex-direction:column;gap:10px">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:#9aa89e">رقم الطلب</span>
                        <b>{{ $order->reference }}</b>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:#9aa89e">التاريخ</span>
                        <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:#9aa89e">الحالة</span>
                        @switch($order->status)
                            @case('delivered')  <span class="sbadge sd">✅ مكتمل</span>   @break
                            @case('pending')    <span class="sbadge sp">⏳ معلق</span>    @break
                            @case('processing') <span class="sbadge spr2">🔄 معالجة</span> @break
                            @case('shipped')    <span class="sbadge spr2">🚚 شحن</span>   @break
                            @case('cancelled')  <span class="sbadge sc">❌ ملغي</span>    @break
                        @endswitch
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:#9aa89e">الدفع</span>
                        <span>{{ $order->payment_method ?? '—' }}</span>
                    </div>
                    <hr style="border:none;border-top:1px solid var(--gf)">
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:#9aa89e">الإجمالي الفرعي</span>
                        <span>{{ number_format($order->subtotal, 2) }} ر.ق</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div style="display:flex;justify-content:space-between;font-size:13px">
                            <span style="color:#9aa89e">الخصم ({{ $order->coupon_code }})</span>
                            <span style="color:var(--red)">- {{ number_format($order->discount_amount, 2) }} ر.ق</span>
                        </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:#9aa89e">الشحن</span>
                        <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 2).' ر.ق' : 'مجاني' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:900">
                        <span>الإجمالي</span>
                        <span style="color:var(--gd)">{{ number_format($order->total, 2) }} ر.ق</span>
                    </div>
                </div>
            </div>

            {{-- Customer info --}}
            @if($order->customer)
                <div class="fsec">
                    <h3><i class="fa fa-user"></i> العميل</h3>
                    <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px">
                        <div><b>{{ $order->customer->name }}</b></div>
                        <div style="color:#9aa89e"><i class="fa fa-envelope" style="width:16px"></i> {{ $order->customer->email }}</div>
                        <div style="color:#9aa89e"><i class="fa fa-phone" style="width:16px"></i> {{ $order->customer->phone ?? '—' }}</div>
                        <div style="color:#9aa89e"><i class="fa fa-map-marker-alt" style="width:16px"></i> {{ $order->city }}, {{ $order->address }}</div>
                        <a href="{{ route('admin.customers.show', $order->customer) }}" class="btn-s" style="margin-top:4px;justify-content:center">
                            عرض ملف العميل
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('modals')
    <div class="mov" id="statusModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('statusModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-sync" style="color:var(--gb)"></i> تغيير حالة الطلب</div>

            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')
                <div class="fg">
                    <label>الحالة الجديدة</label>
                    <select name="status" class="tfltr" style="width:100%;padding:10px 12px;font-size:14px">
                        @foreach(['pending' => '⏳ معلق', 'processing' => '🔄 معالجة', 'shipped' => '🚚 في الشحن', 'delivered' => '✅ مكتمل', 'cancelled' => '❌ ملغي'] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:12px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ
                    </button>
                    <button type="button" class="btn-s" style="padding:12px 18px"
                            onclick="document.getElementById('statusModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
@endpush
