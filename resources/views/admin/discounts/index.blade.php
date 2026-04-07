@extends('admin.layouts.app')

@section('title', 'إدارة الخصومات')
@section('page_title', '🏷️ إدارة الخصومات')

@section('topbar_actions')
    <button class="btn-p" onclick="openCouponModal()">
        <i class="fa fa-plus"></i> إضافة كوبون
    </button>
@endsection

@section('content')

    {{-- ── KPIs ── --}}
    <div class="kpis" style="margin-bottom:18px">
        <div class="kcard">
            <div class="kcdr"><div class="kico kg">🏷️</div></div>
            <div class="kval">{{ $totalCoupons }}</div>
            <div class="klbl">إجمالي الكوبونات</div>
        </div>
        <div class="kcard">
            <div class="kcdr"><div class="kico kb">✅</div></div>
            <div class="kval">{{ $activeCoupons }}</div>
            <div class="klbl">كوبونات نشطة</div>
        </div>
    </div>

    {{-- ── COUPONS TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>الكوبونات ({{ $totalCoupons }})</h4>
            <div class="thdr-acts">
                <input class="tsrch" type="text" placeholder="بحث بالكود..." id="couponSearch">
            </div>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>الكود</th>
                <th>النوع</th>
                <th>القيمة</th>
                <th>الحد الأدنى</th>
                <th>الاستخدام</th>
                <th>تاريخ الانتهاء</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="couponsTbody">
            @forelse($coupons as $coupon)
                <tr>
                    <td><b style="font-family:monospace;font-size:14px;letter-spacing:1px">{{ $coupon->code }}</b></td>
                    <td>{{ $coupon->type === 'percentage' ? 'نسبة %' : 'مبلغ ثابت' }}</td>
                    <td><b>{{ $coupon->type === 'percentage' ? $coupon->value.'%' : number_format($coupon->value, 2).' ر.ق' }}</b></td>
                    <td>{{ $coupon->min_order_amount > 0 ? number_format($coupon->min_order_amount, 2).' ر.ق' : '—' }}</td>
                    <td>{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '—' }}</td>
                    <td>
                        @php $statusLabel = $coupon->getStatusLabel() @endphp
                        @if($statusLabel === 'نشط')   <span class="sbadge sd">نشط</span>
                        @elseif($statusLabel === 'معطل') <span class="sbadge sc">معطل</span>
                        @elseif($statusLabel === 'منتهي') <span class="sbadge sc">منتهي</span>
                        @else <span class="sbadge sp">{{ $statusLabel }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="aico" title="تعديل" onclick="editCoupon({{ $coupon->toJson() }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="aico" title="حذف" style="color:var(--red)"
                                onclick="confirmDeleteCoupon('{{ route('admin.discounts.destroy', $coupon) }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#9aa89e;padding:28px">لا توجد كوبونات</td></tr>
            @endforelse
            </tbody>
        </table>

        @if($coupons->hasPages())
            <div class="pgn">
                <span class="pgn-info">{{ $coupons->firstItem() }}-{{ $coupons->lastItem() }} من {{ $coupons->total() }}</span>
                <div class="pgn-btns">{{ $coupons->links('admin.partials.pagination') }}</div>
            </div>
        @endif
    </div>

@endsection

@push('modals')

    {{-- ── ADD / EDIT COUPON ── --}}
    <div class="mov" id="couponModal">
        <div class="modal" style="width:540px">
            <button class="mcls" onclick="closeCouponModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-tag" style="color:var(--gb)"></i>
                <span id="couponModalTitle">إضافة كوبون جديد</span>
            </div>

            <form id="couponForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="couponMethod" value="POST">

                <div class="frow">
                    <div class="fg">
                        <label>كود الخصم</label>
                        <input type="text" name="code" id="cCode" placeholder="SAVE20" required style="text-transform:uppercase">
                    </div>
                    <div class="fg">
                        <label>النوع</label>
                        <select name="type" id="cType" class="tfltr" style="width:100%;padding:9px 12px;font-size:13.5px">
                            <option value="percentage">نسبة مئوية (%)</option>
                            <option value="fixed">مبلغ ثابت (ر.ق)</option>
                        </select>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>القيمة</label>
                        <input type="number" step="0.01" name="value" id="cValue" placeholder="20" required min="0">
                    </div>
                    <div class="fg">
                        <label>الحد الأدنى للطلب (ر.ق)</label>
                        <input type="number" step="0.01" name="min_order_amount" id="cMin" placeholder="0" min="0">
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>حد الاستخدام</label>
                        <input type="number" name="usage_limit" id="cLimit" placeholder="بلا حد">
                    </div>
                    <div class="fg">
                        <label>تاريخ الانتهاء</label>
                        <input type="date" name="expires_at" id="cExpiry">
                    </div>
                </div>

                <div class="fg" style="margin-bottom:14px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="is_active" id="cActive" value="1" style="width:17px;height:17px;accent-color:var(--gb)" checked>
                        تفعيل الكوبون
                    </label>
                </div>

                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ الكوبون
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeCouponModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE ── --}}
    <div class="mov" id="deleteCouponModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('deleteCouponModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">هل أنت متأكد من حذف هذا الكوبون؟</p>
            <form id="deleteCouponForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px"
                            onclick="document.getElementById('deleteCouponModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
    <script>
        const couponStoreUrl = '{{ route('admin.discounts.store') }}';

        function openCouponModal() {
            document.getElementById('couponModalTitle').textContent = 'إضافة كوبون جديد';
            document.getElementById('couponMethod').value           = 'POST';
            document.getElementById('couponForm').action            = couponStoreUrl;
            document.getElementById('couponForm').reset();
            document.getElementById('cActive').checked              = true;
            document.getElementById('couponModal').classList.add('on');
        }

        function editCoupon(c) {
            document.getElementById('couponModalTitle').textContent = 'تعديل الكوبون';
            document.getElementById('couponMethod').value           = 'PUT';
            document.getElementById('couponForm').action            = `/dashboard/discounts/${c.id}`;
            document.getElementById('cCode').value                  = c.code         || '';
            document.getElementById('cType').value                  = c.type         || 'percentage';
            document.getElementById('cValue').value                 = c.value        || '';
            document.getElementById('cMin').value                   = c.min_order_amount || '';
            document.getElementById('cLimit').value                 = c.usage_limit  || '';
            document.getElementById('cExpiry').value                = c.expires_at   ? c.expires_at.substring(0, 10) : '';
            document.getElementById('cActive').checked             = !!c.is_active;
            document.getElementById('couponModal').classList.add('on');
        }

        function closeCouponModal() {
            document.getElementById('couponModal').classList.remove('on');
        }

        function confirmDeleteCoupon(url) {
            document.getElementById('deleteCouponForm').action = url;
            document.getElementById('deleteCouponModal').classList.add('on');
        }

        // Search
        document.getElementById('couponSearch')?.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('#couponsTbody tr').forEach(row => {
                row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
            });
        });
    </script>
@endpush
