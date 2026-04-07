@extends('admin.layouts.app')

@section('title', $product->name)
@section('page_title', '📦 تفاصيل المنتج')

@section('topbar_actions')
    <a href="{{ route('admin.products.index') }}" class="btn-s">
        <i class="fa fa-arrow-right"></i> العودة
    </a>
    <button class="btn-p" onclick="editProduct({{ $product->toJson() }})">
        <i class="fa fa-edit"></i> تعديل
    </button>
@endsection

@section('content')

    <div style="display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start">

        {{-- ── IMAGE & STATUS ── --}}
        <div class="fsec" style="text-align:center">
            <div style="background:var(--gf);border-radius:14px;padding:24px;margin-bottom:16px">
                <img src="{{ $product->image_url ?: 'https://via.placeholder.com/200/d8f3dc/2d6a4f?text=P' }}"
                     onerror="this.src='https://via.placeholder.com/200/d8f3dc/2d6a4f?text=P'"
                     alt="{{ $product->name }}"
                     style="max-width:200px;max-height:200px;object-fit:contain">
            </div>

            @if($product->is_active)
                <span class="sbadge sd" style="font-size:13px;padding:6px 16px">✅ نشط</span>
            @else
                <span class="sbadge sc" style="font-size:13px;padding:6px 16px">❌ معطل</span>
            @endif

            @if($product->is_on_sale)
                <span class="sbadge sp" style="font-size:13px;padding:6px 16px;margin-right:6px">🔥 عرض</span>
            @endif

            <div style="margin-top:18px;display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="kcard">
                    <div class="kval">{{ number_format($product->price, 2) }}</div>
                    <div class="klbl">السعر ر.ق</div>
                </div>
                <div class="kcard">
                    <div class="kval">{{ $product->stock }}</div>
                    <div class="klbl">المخزون</div>
                </div>
                <div class="kcard">
                    <div class="kval">{{ $product->rating ?? '—' }}</div>
                    <div class="klbl">التقييم</div>
                </div>
                <div class="kcard">
                    <div class="kval">{{ $product->reviews_count ?? 0 }}</div>
                    <div class="klbl">مراجعة</div>
                </div>
            </div>
        </div>

        {{-- ── DETAILS ── --}}
        <div>
            <div class="fsec">
                <h3><i class="fa fa-info-circle"></i> معلومات المنتج</h3>

                <div class="frow">
                    <div class="fg">
                        <label>اسم المنتج</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:14px;font-weight:700">{{ $product->name }}</div>
                    </div>
                    <div class="fg">
                        <label>الماركة</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:14px">{{ $product->brand }}</div>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>القسم</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:14px">
                            {{ $product->category?->emoji }} {{ $product->category?->name ?? '—' }}
                        </div>
                    </div>
                    <div class="fg">
                        <label>الرابط (Slug)</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:13px;color:#9aa89e">{{ $product->slug }}</div>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>السعر الحالي</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:15px;font-weight:900;color:var(--gd)">
                            {{ number_format($product->price, 2) }} ر.ق
                        </div>
                    </div>
                    <div class="fg">
                        <label>السعر القديم</label>
                        <div style="padding:9px 12px;background:var(--gf);border-radius:8px;font-size:14px;color:#9aa89e;text-decoration:line-through">
                            {{ $product->old_price ? number_format($product->old_price, 2) . ' ر.ق' : '—' }}
                        </div>
                    </div>
                </div>

                @if($product->description)
                    <div class="fg">
                        <label>الوصف</label>
                        <div style="padding:10px 12px;background:var(--gf);border-radius:8px;font-size:13.5px;line-height:1.7">
                            {{ $product->description }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── ORDER ITEMS ── --}}
            <div class="tcard">
                <div class="thdr">
                    <h4>آخر الطلبات التي تحتوي هذا المنتج ({{ $product->orderItems->count() }})</h4>
                </div>
                <table class="dtbl">
                    <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($product->orderItems->take(10) as $item)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $item->order_id) }}" style="color:var(--gb);font-weight:700">
                                    {{ $item->order?->reference ?? '#'.$item->order_id }}
                                </a>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} ر.ق</td>
                            <td><b>{{ number_format($item->subtotal, 2) }} ر.ق</b></td>
                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;color:#9aa89e;padding:22px">لا توجد طلبات بعد</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('modals')
    {{-- Edit modal (reuse from index) --}}
    <div class="mov" id="productModal">
        <div class="modal">
            <button class="mcls" onclick="document.getElementById('productModal').classList.remove('on')">✕</button>
            <div class="modal-title">
                <i class="fa fa-edit" style="color:var(--gb)"></i> تعديل المنتج
            </div>

            <form id="productForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="frow">
                    <div class="fg">
                        <label>اسم المنتج</label>
                        <input type="text" name="name" id="pName" required>
                    </div>
                    <div class="fg">
                        <label>الماركة</label>
                        <input type="text" name="brand" id="pBrand" required>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>السعر (ر.ق)</label>
                        <input type="number" step="0.01" name="price" id="pPrice" required>
                    </div>
                    <div class="fg">
                        <label>السعر القديم</label>
                        <input type="number" step="0.01" name="old_price" id="pOldPrice">
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>القسم</label>
                        <select name="category_id" id="pCategory" required>
                            <option value="">اختر القسم...</option>
                            @foreach(\App\Models\Category::active()->get() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->emoji }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>المخزون</label>
                        <input type="number" name="stock" id="pStock" required>
                    </div>
                </div>

                <div class="fg">
                    <label>الوصف</label>
                    <textarea name="description" id="pDesc"></textarea>
                </div>

                <div class="fg">
                    <label>رابط الصورة (URL)</label>
                    <input type="text" name="image_url" id="pImage" placeholder="https://...">
                </div>
                <div class="fg">
                    <label>أو ارفع صورة</label>
                    <input type="file" name="image" accept="image/*" style="padding:7px">
                </div>

                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ التعديلات
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px"
                            onclick="document.getElementById('productModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        function editProduct(product) {
            document.getElementById('productForm').action  = `/dashboard/products/${product.id}`;
            document.getElementById('pName').value         = product.name        || '';
            document.getElementById('pBrand').value        = product.brand       || '';
            document.getElementById('pPrice').value        = product.price       || '';
            document.getElementById('pOldPrice').value     = product.old_price   || '';
            document.getElementById('pCategory').value     = product.category_id || '';
            document.getElementById('pStock').value        = product.stock       || '';
            document.getElementById('pDesc').value         = product.description || '';
            document.getElementById('pImage').value        = product.image_url   || '';
            document.getElementById('productModal').classList.add('on');
        }
    </script>
@endpush
