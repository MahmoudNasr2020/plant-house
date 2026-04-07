@extends('admin.layouts.app')

@section('title', 'إدارة الأقسام')
@section('page_title', '🗂️ إدارة الأقسام')

@section('topbar_actions')
    <button class="btn-p" onclick="openCategoryModal()">
        <i class="fa fa-plus"></i> إضافة قسم
    </button>
@endsection

@section('content')

    {{-- ── CATEGORIES TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>الأقسام ({{ $totalCategories }})</h4>
            <div class="thdr-acts">
                <input class="tsrch" type="text" placeholder="بحث بالاسم..." id="categorySearch">
            </div>
        </div>

        <table class="dtbl">
            <thead>
            <tr>
                <th>الإيموجي</th>
                <th>اسم القسم</th>
                <th>الرابط</th>
                <th>المنتجات</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody id="categoriesTbody">
            @forelse($categories as $category)
                <tr>
                    <td style="font-size:22px;text-align:center">{{ $category->emoji ?? '📦' }}</td>
                    <td><b>{{ $category->name }}</b></td>
                    <td style="color:#9aa89e;font-size:12px">{{ $category->slug }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->sort_order ?? 0 }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="sbadge sd">نشط</span>
                        @else
                            <span class="sbadge sc">معطل</span>
                        @endif
                    </td>
                    <td>
                        <button class="aico" title="تعديل"
                                onclick="editCategory({{ $category->toJson() }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="aico" title="حذف"
                                style="color:var(--red)"
                                onclick="confirmDeleteCategory('{{ route('admin.categories.destroy', $category) }}', '{{ $category->name }}', {{ $category->products_count }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا توجد أقسام</td></tr>
            @endforelse
            </tbody>
        </table>

        @if($categories->hasPages())
            <div class="pgn">
                <span class="pgn-info">
                    {{ $categories->firstItem() }}-{{ $categories->lastItem() }} من {{ $categories->total() }} قسم
                </span>
                <div class="pgn-btns">
                    {{ $categories->links('admin.partials.pagination') }}
                </div>
            </div>
        @endif

    </div>

@endsection

@push('modals')

    {{-- ── ADD / EDIT CATEGORY MODAL ── --}}
    <div class="mov" id="categoryModal">
        <div class="modal" style="width:520px">
            <button class="mcls" onclick="closeCategoryModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-folder-plus" style="color:var(--gb)"></i>
                <span id="categoryModalTitle">إضافة قسم جديد</span>
            </div>

            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="categoryMethod" value="POST">

                <div class="frow">
                    <div class="fg">
                        <label>اسم القسم</label>
                        <input type="text" name="name" id="cName" placeholder="مثال: نباتات داخلية" required>
                    </div>
                    <div class="fg">
                        <label>الإيموجي</label>
                        <input type="text" name="emoji" id="cEmoji" placeholder="🌿" maxlength="10">
                    </div>
                </div>

                <div class="fg">
                    <label>الوصف</label>
                    <textarea name="description" id="cDesc" placeholder="وصف مختصر للقسم..."></textarea>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" id="cSort" placeholder="0" min="0">
                    </div>
                    <div class="fg" style="justify-content:flex-end;padding-bottom:4px">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:auto">
                            <input type="checkbox" name="is_active" id="cActive" value="1" style="width:17px;height:17px;accent-color:var(--gb)">
                            تفعيل القسم
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;font-size:14.5px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ القسم
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeCategoryModal()">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE CONFIRMATION ── --}}
    <div class="mov" id="deleteCategoryModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="closeDeleteCategoryModal()">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p id="deleteCategoryMsg" style="font-size:14px;color:#5a7a65;margin-bottom:22px">
                هل أنت متأكد من حذف هذا القسم؟
            </p>
            <form id="deleteCategoryForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px" onclick="closeDeleteCategoryModal()">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
    <script>
        const storeUrl  = '{{ route('admin.categories.store') }}';

        function openCategoryModal() {
            document.getElementById('categoryModalTitle').textContent = 'إضافة قسم جديد';
            document.getElementById('categoryMethod').value           = 'POST';
            document.getElementById('categoryForm').action            = storeUrl;
            document.getElementById('categoryForm').reset();
            document.getElementById('cActive').checked               = true;
            document.getElementById('categoryModal').classList.add('on');
        }

        function editCategory(cat) {
            document.getElementById('categoryModalTitle').textContent = 'تعديل القسم';
            document.getElementById('categoryMethod').value           = 'PUT';
            document.getElementById('categoryForm').action            = `/dashboard/categories/${cat.id}`;
            document.getElementById('cName').value                    = cat.name        || '';
            document.getElementById('cEmoji').value                   = cat.emoji       || '';
            document.getElementById('cDesc').value                    = cat.description || '';
            document.getElementById('cSort').value                    = cat.sort_order  ?? 0;
            document.getElementById('cActive').checked               = !!cat.is_active;
            document.getElementById('categoryModal').classList.add('on');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.remove('on');
        }

        function confirmDeleteCategory(actionUrl, name, productsCount) {
            const msg = productsCount > 0
                ? `هل أنت متأكد من حذف قسم "${name}"؟ يحتوي على ${productsCount} منتج.`
                : `هل أنت متأكد من حذف قسم "${name}"؟`;
            document.getElementById('deleteCategoryMsg').textContent = msg;
            document.getElementById('deleteCategoryForm').action     = actionUrl;
            document.getElementById('deleteCategoryModal').classList.add('on');
        }

        function closeDeleteCategoryModal() {
            document.getElementById('deleteCategoryModal').classList.remove('on');
        }

        // ── Live search ───────────────────────────────────────────────
        (function () {
            const searchEl = document.getElementById('categorySearch');
            const tbody    = document.getElementById('categoriesTbody');
            if (!searchEl || !tbody) return;

            searchEl.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                tbody.querySelectorAll('tr').forEach(row => {
                    row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
                });
            });
        })();
    </script>
@endpush
