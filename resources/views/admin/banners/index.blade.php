@extends('admin.layouts.app')

@section('title', 'إدارة البانرات')
@section('page_title', '🖼️ البانرات والسلايدر')

@section('topbar_actions')
    <button class="btn-p" onclick="openBannerModal()">
        <i class="fa fa-plus"></i> إضافة بانر
    </button>
@endsection

@section('content')

    {{-- ── PREVIEW ── --}}
    @if($banners->isNotEmpty())
        <div class="fsec" style="margin-bottom:18px">
            <h3><i class="fa fa-eye"></i> معاينة البانرات</h3>
            <div style="display:grid;grid-template-columns:1fr 240px;gap:10px">

                @php $hero = $banners->where('type', 'hero')->first(); @endphp
                @php $sides = $banners->where('type', 'side')->take(2); @endphp

                {{-- Hero --}}
                @if($hero)
                    <div style="background:linear-gradient(135deg,{{ $hero->bg_from }},{{ $hero->bg_to }});border-radius:16px;padding:32px 36px;position:relative;overflow:hidden;min-height:160px;display:flex;align-items:center">
                        <div style="position:relative;z-index:1">
                            @if($hero->badge)
                                <div style="display:inline-block;background:#f4a261;color:#1a3a2a;font-size:11px;font-weight:800;padding:4px 12px;border-radius:50px;margin-bottom:9px">{{ $hero->badge }}</div>
                            @endif
                            <div style="font-size:22px;font-weight:900;color:#fff;margin-bottom:6px">{!! nl2br(e($hero->title)) !!}</div>
                            @if($hero->subtitle)
                                <div style="color:rgba(255,255,255,.75);font-size:12px;margin-bottom:14px">{{ $hero->subtitle }}</div>
                            @endif
                            @if($hero->button_text)
                                <div style="display:inline-flex;align-items:center;gap:7px;background:#f4a261;color:#1a3a2a;padding:9px 20px;border-radius:50px;font-weight:800;font-size:12px">
                                    {{ $hero->button_text }}
                                </div>
                            @endif
                        </div>
                        @if($hero->emoji)
                            <div style="position:absolute;left:20px;bottom:10px;font-size:80px;opacity:.15">{{ $hero->emoji }}</div>
                        @endif
                    </div>
                @else
                    <div style="background:var(--gf);border-radius:16px;padding:32px;display:flex;align-items:center;justify-content:center;color:#9aa89e;min-height:160px">
                        لا يوجد بانر رئيسي — أضف بانر من نوع "رئيسي"
                    </div>
                @endif

                {{-- Sides --}}
                <div style="display:flex;flex-direction:column;gap:10px">
                    @forelse($sides as $side)
                        <div style="background:linear-gradient(135deg,{{ $side->bg_from }},{{ $side->bg_to }});border-radius:12px;padding:16px;position:relative;overflow:hidden;flex:1">
                            @if($side->badge)
                                <div style="font-size:10px;font-weight:700;background:rgba(255,255,255,.22);color:#fff;padding:2px 8px;border-radius:50px;display:inline-block;margin-bottom:5px">{{ $side->badge }}</div>
                            @endif
                            <div style="font-size:14px;font-weight:800;color:#fff;line-height:1.3">{{ $side->title }}</div>
                            @if($side->subtitle)
                                <div style="font-size:11px;color:rgba(255,255,255,.75);margin-top:3px">{{ $side->subtitle }}</div>
                            @endif
                            @if($side->emoji)
                                <div style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:36px;opacity:.28">{{ $side->emoji }}</div>
                            @endif
                        </div>
                    @empty
                        <div style="background:var(--gf);border-radius:12px;padding:16px;flex:1;display:flex;align-items:center;justify-content:center;color:#9aa89e;font-size:12px">
                            أضف بانر جانبي
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    @endif

    {{-- ── TABLE ── --}}
    <div class="tcard">
        <div class="thdr">
            <h4>جميع البانرات ({{ $banners->count() }})</h4>
        </div>
        <table class="dtbl">
            <thead>
            <tr>
                <th>النوع</th>
                <th>العنوان</th>
                <th>الألوان</th>
                <th>الإيموجي</th>
                <th>الترتيب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>
                        @if($banner->type === 'hero')
                            <span class="sbadge spr2">🖼️ رئيسي</span>
                        @else
                            <span class="sbadge sp">📌 جانبي</span>
                        @endif
                    </td>
                    <td><b>{{ $banner->title }}</b>{{ $banner->badge ? ' · '.$banner->badge : '' }}</td>
                    <td>
                        <div style="display:flex;gap:5px;align-items:center">
                            <div style="width:18px;height:18px;border-radius:4px;background:{{ $banner->bg_from }}"></div>
                            <div style="width:18px;height:18px;border-radius:4px;background:{{ $banner->bg_to }}"></div>
                        </div>
                    </td>
                    <td style="font-size:20px">{{ $banner->emoji ?? '—' }}</td>
                    <td>{{ $banner->sort_order }}</td>
                    <td>
                        @if($banner->is_active)
                            <span class="sbadge sd">نشط</span>
                        @else
                            <span class="sbadge sc">معطل</span>
                        @endif
                    </td>
                    <td>
                        <button class="aico" title="تعديل" onclick="editBanner({{ $banner->toJson() }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="aico" title="حذف" style="color:var(--red)"
                                onclick="confirmDeleteBanner('{{ route('admin.banners.destroy', $banner) }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#9aa89e;padding:28px">لا توجد بانرات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('modals')

    {{-- ── ADD / EDIT BANNER ── --}}
    <div class="mov" id="bannerModal">
        <div class="modal" style="width:580px">
            <button class="mcls" onclick="closeBannerModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-image" style="color:var(--gb)"></i>
                <span id="bannerModalTitle">إضافة بانر جديد</span>
            </div>

            <form id="bannerForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="bannerMethod" value="POST">

                <div class="frow">
                    <div class="fg">
                        <label>النوع</label>
                        <select name="type" id="bType" class="tfltr" style="width:100%;padding:9px 12px;font-size:13.5px">
                            <option value="hero">🖼️ رئيسي (Hero)</option>
                            <option value="side">📌 جانبي (Side)</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>الإيموجي</label>
                        <input type="text" name="emoji" id="bEmoji" placeholder="🌿" maxlength="10">
                    </div>
                </div>

                <div class="fg">
                    <label>العنوان</label>
                    <input type="text" name="title" id="bTitle" placeholder="غذِّ جسمك بأفضل المكملات" required>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>البادج / الوسم</label>
                        <input type="text" name="badge" id="bBadge" placeholder="⚡ عرض حصري">
                    </div>
                    <div class="fg">
                        <label>العنوان الفرعي</label>
                        <input type="text" name="subtitle" id="bSubtitle" placeholder="وصف مختصر">
                    </div>
                </div>

                <div class="fg">
                    <label>الوصف (للبانر الرئيسي)</label>
                    <textarea name="description" id="bDesc" rows="2" placeholder="نص وصفي أسفل العنوان..."></textarea>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>نص الزر</label>
                        <input type="text" name="button_text" id="bBtnText" placeholder="تسوق الآن">
                    </div>
                    <div class="fg">
                        <label>رابط الزر</label>
                        <input type="text" name="button_link" id="bBtnLink" placeholder="/products">
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>لون البداية (Gradient من)</label>
                        <div style="display:flex;gap:7px;align-items:center">
                            <input type="color" name="bg_from" id="bFrom" value="#1a3a2a" style="width:50px;height:36px;border:none;cursor:pointer;border-radius:6px">
                            <input type="text" id="bFromText" value="#1a3a2a" style="flex:1;border:2px solid var(--gp);border-radius:8px;padding:7px 10px;font-size:13px;outline:none"
                                   oninput="document.getElementById('bFrom').value=this.value">
                        </div>
                    </div>
                    <div class="fg">
                        <label>لون النهاية (Gradient إلى)</label>
                        <div style="display:flex;gap:7px;align-items:center">
                            <input type="color" name="bg_to" id="bTo" value="#2d6a4f" style="width:50px;height:36px;border:none;cursor:pointer;border-radius:6px">
                            <input type="text" id="bToText" value="#2d6a4f" style="flex:1;border:2px solid var(--gp);border-radius:8px;padding:7px 10px;font-size:13px;outline:none"
                                   oninput="document.getElementById('bTo').value=this.value">
                        </div>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" id="bSort" value="0" min="0">
                    </div>
                    <div class="fg" style="justify-content:flex-end;padding-bottom:4px">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:auto">
                            <input type="checkbox" name="is_active" id="bActive" value="1" style="width:17px;height:17px;accent-color:var(--gb)">
                            تفعيل البانر
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:11px;margin-top:7px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ البانر
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeBannerModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE ── --}}
    <div class="mov" id="deleteBannerModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('deleteBannerModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p style="font-size:14px;color:#5a7a65;margin-bottom:22px">هل أنت متأكد من حذف هذا البانر؟</p>
            <form id="deleteBannerForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px"
                            onclick="document.getElementById('deleteBannerModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
    <script>
        const bannerStoreUrl = '{{ route('admin.banners.store') }}';

        // Sync color pickers with text inputs
        document.getElementById('bFrom')?.addEventListener('input', function () {
            document.getElementById('bFromText').value = this.value;
        });
        document.getElementById('bTo')?.addEventListener('input', function () {
            document.getElementById('bToText').value = this.value;
        });

        function openBannerModal() {
            document.getElementById('bannerModalTitle').textContent = 'إضافة بانر جديد';
            document.getElementById('bannerMethod').value           = 'POST';
            document.getElementById('bannerForm').action            = bannerStoreUrl;
            document.getElementById('bannerForm').reset();
            document.getElementById('bFrom').value     = '#1a3a2a';
            document.getElementById('bFromText').value = '#1a3a2a';
            document.getElementById('bTo').value       = '#2d6a4f';
            document.getElementById('bToText').value   = '#2d6a4f';
            document.getElementById('bActive').checked = true;
            document.getElementById('bannerModal').classList.add('on');
        }

        function editBanner(b) {
            document.getElementById('bannerModalTitle').textContent = 'تعديل البانر';
            document.getElementById('bannerMethod').value           = 'PUT';
            document.getElementById('bannerForm').action            = `/dashboard/banners/${b.id}`;
            document.getElementById('bType').value                  = b.type        || 'side';
            document.getElementById('bTitle').value                 = b.title       || '';
            document.getElementById('bBadge').value                 = b.badge       || '';
            document.getElementById('bSubtitle').value              = b.subtitle    || '';
            document.getElementById('bDesc').value                  = b.description || '';
            document.getElementById('bBtnText').value               = b.button_text || '';
            document.getElementById('bBtnLink').value               = b.button_link || '';
            document.getElementById('bEmoji').value                 = b.emoji       || '';
            document.getElementById('bSort').value                  = b.sort_order  ?? 0;
            document.getElementById('bActive').checked             = !!b.is_active;
            const from = b.bg_from || '#1a3a2a';
            const to   = b.bg_to   || '#2d6a4f';
            document.getElementById('bFrom').value     = from;
            document.getElementById('bFromText').value = from;
            document.getElementById('bTo').value       = to;
            document.getElementById('bToText').value   = to;
            document.getElementById('bannerModal').classList.add('on');
        }

        function closeBannerModal() {
            document.getElementById('bannerModal').classList.remove('on');
        }

        function confirmDeleteBanner(url) {
            document.getElementById('deleteBannerForm').action = url;
            document.getElementById('deleteBannerModal').classList.add('on');
        }
    </script>
@endpush
