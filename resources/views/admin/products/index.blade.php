@extends('admin.layouts.app')

@section('title', 'إدارة المنتجات')
@section('page_title', '📦 إدارة المنتجات')

@section('topbar_actions')
    <button class="btn-s" id="exportProductsBtn">
        <i class="fa fa-download"></i> تصدير
    </button>
    <button class="btn-p" onclick="openProductModal()">
        <i class="fa fa-plus"></i> إضافة منتج
    </button>
@endsection

@section('content')

    {{-- ── PRODUCTS TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>المنتجات ({{ $totalProducts ?? 15 }})</h4>
            <div class="thdr-acts">
                <input class="tsrch" type="text" placeholder="بحث بالاسم أو الماركة..." id="productSearch">
                <select class="tfltr" id="productCatFilter">
                    <option value="">كل الأقسام</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->emoji }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>المنتج</th>
                <th>الفئة</th>
                <th>السعر</th>
                <th>المخزون</th>
                <th>المبيعات</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="productsTbody">
            @forelse($products ?? [] as $product)
                <tr data-cat="{{ $product->category_id }}">
                    <td>
                        <div class="pcell">
                            <img src="{{ $product->image_url }}"
                                 onerror="this.src='https://via.placeholder.com/40/d8f3dc/2d6a4f?text=P'"
                                 alt="{{ $product->name }}">
                            <div>
                                <div class="pn">{{ $product->name }}</div>
                                <div class="pb">{{ $product->brand }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $product->category_label }}</td>
                    <td><b>{{ number_format($product->price, 2) }} ر.ق</b></td>
                    <td>
                        @if($product->stock <= 5)
                            <span style="color:var(--red);font-weight:700">{{ $product->stock }} ⚠️</span>
                        @else
                            {{ $product->stock }}
                        @endif
                    </td>
                    <td>{{ $product->reviews_count ?? 0 }}</td>
                    <td>
                        @if($product->is_active)
                            <span class="sbadge sd">نشط</span>
                        @else
                            <span class="sbadge sc">معطل</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}" class="aico" title="عرض">
                            <i class="fa fa-eye"></i>
                        </a>
                        <button class="aico" title="تعديل"
                                onclick="editProduct({{ $product->toJson() }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="aico" title="حذف"
                                style="color:var(--red)"
                                onclick="confirmDeleteProduct('{{ route('admin.products.destroy', $product) }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا توجد منتجات</td></tr>
            @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if(isset($products) && $products->hasPages())
            <div class="pgn">
                <span class="pgn-info">
                    {{ $products->firstItem() }}-{{ $products->lastItem() }} من {{ $products->total() }} منتج
                </span>
                <div class="pgn-btns">
                    {{ $products->links('admin.partials.pagination') }}
                </div>
            </div>
        @endif

    </div>

@endsection

@push('modals')

    {{-- ── ADD / EDIT PRODUCT MODAL ── --}}
    <div class="mov" id="productModal">
        <div class="modal">
            <button class="mcls" onclick="closeProductModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-plus-circle" style="color:var(--gb)"></i>
                <span id="modalTitle">إضافة / تعديل منتج</span>
            </div>

            <form id="productForm" method="POST" enctype="multipart/form-data" action="">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="product_id" id="productId">

                <div class="frow">
                    <div class="fg">
                        <label>اسم المنتج</label>
                        <input type="text" name="name" id="pName" placeholder="مثال: بروتين واي الذهبي" required>
                    </div>
                    <div class="fg">
                        <label>الماركة</label>
                        <input type="text" name="brand" id="pBrand" placeholder="Optimum Nutrition" required>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>السعر (ر.ق)</label>
                        <input type="number" step="0.01" name="price" id="pPrice" placeholder="189" required>
                    </div>
                    <div class="fg">
                        <label>السعر القديم</label>
                        <input type="number" step="0.01" name="old_price" id="pOldPrice" placeholder="260">
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>القسم</label>
                        <select name="category_id" id="pCategory" required>
                            <option value="">اختر القسم...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->emoji }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>المخزون</label>
                        <input type="number" name="stock" id="pStock" placeholder="50" required>
                    </div>
                </div>

                <div class="fg">
                    <label>الوصف</label>
                    <textarea name="description" id="pDesc" placeholder="اكتب وصفاً للمنتج..."></textarea>
                </div>

                <div class="fg">
                    <label>رابط الصورة (URL)</label>
                    <input type="text" name="image_url" id="pImage" placeholder="https://...">
                </div>
                <div class="fg">
                    <label>أو ارفع صورة من جهازك</label>
                    <input type="file" name="image" id="pImageFile" accept="image/*"
                           style="border:2px solid var(--gp);border-radius:8px;padding:7px 10px;font-size:13px;width:100%">
                </div>

                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;font-size:14.5px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ المنتج
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeProductModal()">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE CONFIRMATION MODAL ── --}}
    <div class="mov" id="deleteProductModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="closeDeleteProductModal()">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع.</p>
            <form id="deleteProductForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px" onclick="closeDeleteProductModal()">
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
        function openProductModal() {
            document.getElementById('modalTitle').textContent  = 'إضافة منتج جديد';
            document.getElementById('formMethod').value        = 'POST';
            document.getElementById('productForm').action      = '{{ route('admin.products.store') }}';
            document.getElementById('productForm').reset();
            document.getElementById('productModal').classList.add('on');
        }

        function editProduct(product) {
            document.getElementById('modalTitle').textContent  = 'تعديل المنتج';
            document.getElementById('formMethod').value        = 'PUT';
            document.getElementById('productForm').action      = `/dashboard/products/${product.id}`;
            document.getElementById('productId').value         = product.id;
            document.getElementById('pName').value             = product.name      || '';
            document.getElementById('pBrand').value            = product.brand     || '';
            document.getElementById('pPrice').value            = product.price     || '';
            document.getElementById('pOldPrice').value         = product.old_price || '';
            document.getElementById('pCategory').value         = product.category_id || '';
            document.getElementById('pStock').value            = product.stock     || '';
            document.getElementById('pDesc').value             = product.description || '';
            document.getElementById('pImage').value            = product.image_url || '';
            document.getElementById('productModal').classList.add('on');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('on');
        }

        function confirmDeleteProduct(actionUrl) {
            document.getElementById('deleteProductForm').action = actionUrl;
            document.getElementById('deleteProductModal').classList.add('on');
        }

        function closeDeleteProductModal() {
            document.getElementById('deleteProductModal').classList.remove('on');
        }

        // ── Search & Category filter ──────────────────────────────────
        (function () {
            const searchEl = document.getElementById('productSearch');
            const catEl    = document.getElementById('productCatFilter');
            const tbody    = document.getElementById('productsTbody');
            if (!searchEl || !tbody) return;

            function applyFilters() {
                const q   = searchEl.value.trim().toLowerCase();
                const cat = catEl.value;
                tbody.querySelectorAll('tr').forEach(row => {
                    const text   = row.textContent.toLowerCase();
                    const rowCat = row.dataset.cat || '';
                    const matchQ = !q || text.includes(q);
                    const matchC = !cat || rowCat === cat;
                    row.style.display = (matchQ && matchC) ? '' : 'none';
                });
            }

            searchEl.addEventListener('input', applyFilters);
            catEl.addEventListener('change', applyFilters);
        })();

        // ── CSV Export ────────────────────────────────────────────────
        document.getElementById('exportProductsBtn')?.addEventListener('click', function () {
            const rows   = document.querySelectorAll('#productsTbody tr');
            const header = ['المنتج', 'الماركة', 'الفئة', 'السعر', 'المخزون', 'الحالة'];
            const lines  = [header.join(',')];
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                const cells = Array.from(row.querySelectorAll('td')).slice(0, 6);
                lines.push(cells.map(c => `"${c.textContent.trim()}"`).join(','));
            });
            const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8' });
            const a    = document.createElement('a');
            a.href     = URL.createObjectURL(blob);
            a.download = 'products.csv';
            a.click();
        });
    </script>
@endpush
