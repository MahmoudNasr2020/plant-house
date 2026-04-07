@extends('admin.layouts.app')

@section('title', 'الصلاحيات والأدوار')
@section('page_title', '🔐 إدارة الصلاحيات والأدوار')

@section('topbar_actions')
    <button class="btn-p" onclick="openRoleModal()">
        <i class="fa fa-plus"></i> إضافة دور جديد
    </button>
@endsection

@section('content')

    @if(session('success'))
        <div style="background:#e6f9ee;color:#1a7a45;border:1.5px solid #b7e4c7;border-radius:10px;padding:12px 16px;font-size:13.5px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fff0f0;color:#c0392b;border:1.5px solid #f5c6c6;border-radius:10px;padding:12px 16px;font-size:13.5px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="tcard">
        <div class="thdr">
            <h4>الأدوار المتاحة ({{ $roles->count() }})</h4>
        </div>
        <table class="dtbl">
            <thead>
            <tr>
                <th>الدور</th>
                <th>المفتاح</th>
                <th>الوصف</th>
                <th>عدد الصلاحيات</th>
                <th>المديرون</th>
                <th>النوع</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px">
                            <div style="width:34px;height:34px;border-radius:9px;background:var(--gf);display:flex;align-items:center;justify-content:center;font-size:16px">
                                {{ $role->emoji ?? '🔒' }}
                            </div>
                            <div style="font-weight:800">{{ $role->label }}</div>
                        </div>
                    </td>
                    <td><code style="background:#f1f5f2;padding:3px 8px;border-radius:5px;font-size:12px;color:var(--gm)">{{ $role->key }}</code></td>
                    <td style="font-size:12.5px;color:#5a7a65">{{ Str::limit($role->description, 60) }}</td>
                    <td>
                        <span class="sbadge spr2">{{ count($role->permissions ?? []) }} صلاحية</span>
                    </td>
                    <td>
                        <span class="sbadge sd">{{ $role->users_count }} مدير</span>
                    </td>
                    <td>
                        @if($role->is_system)
                            <span class="sbadge sp">⚙️ نظامي</span>
                        @else
                            <span class="sbadge sd">مخصص</span>
                        @endif
                    </td>
                    <td>
                        <button class="aico" title="تعديل" onclick='editRole(@json($role))'>
                            <i class="fa fa-edit"></i>
                        </button>
                        @if(!$role->is_system && $role->users_count === 0)
                            <button class="aico" title="حذف" style="color:var(--red)"
                                    onclick="confirmDeleteRole('{{ route('admin.roles.destroy', $role) }}', '{{ $role->label }}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        @else
                            <span style="color:#9aa89e;font-size:12px">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Permissions Matrix --}}
    <div class="fsec" style="margin-top:20px">
        <h3><i class="fa fa-th"></i> مصفوفة الصلاحيات</h3>
        <table class="dtbl">
            <thead>
            <tr>
                <th style="min-width:280px">الصلاحية</th>
                @foreach($roles as $role)
                    <th style="text-align:center">{{ $role->emoji }} {{ $role->label }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $pkey => $plabel)
                <tr>
                    <td style="font-size:13px;font-weight:700">{{ $plabel }}</td>
                    @foreach($roles as $role)
                        <td style="text-align:center;font-size:15px">
                            @if($role->key === 'super_admin' || in_array($pkey, $role->permissions ?? []))
                                ✅
                            @else
                                ❌
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('modals')

    <div class="mov" id="roleModal">
        <div class="modal" style="width:680px">
            <button class="mcls" onclick="closeRoleModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-shield-alt" style="color:var(--gb)"></i>
                <span id="roleModalTitle">إضافة دور جديد</span>
            </div>

            <form id="roleForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="roleMethod" value="POST">

                <div class="frow">
                    <div class="fg">
                        <label>اسم الدور</label>
                        <input type="text" name="label" id="rLabel" required placeholder="مثال: محاسب">
                    </div>
                    <div class="fg">
                        <label>المفتاح (بالإنجليزية)</label>
                        <input type="text" name="key" id="rKey" required placeholder="accountant" pattern="[a-z_]+">
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>الإيموجي</label>
                        <input type="text" name="emoji" id="rEmoji" placeholder="🧾" maxlength="4">
                    </div>
                    <div class="fg">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" id="rSort" value="10" min="0">
                    </div>
                </div>

                <div class="fg">
                    <label>الوصف</label>
                    <textarea name="description" id="rDesc" rows="2" placeholder="وصف مختصر للدور وصلاحياته..."></textarea>
                </div>

                <div class="fg">
                    <label style="margin-bottom:8px">الصلاحيات</label>
                    <div id="permissionsList" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;background:#f8fdf9;border:2px solid var(--gf);border-radius:12px;padding:14px">
                        @foreach($permissions as $pkey => $plabel)
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#2d4a3a;cursor:pointer;padding:6px 9px;border-radius:7px;transition:.2s" onmouseover="this.style.background='var(--gf)'" onmouseout="this.style.background=''">
                                <input type="checkbox" name="permissions[]" value="{{ $pkey }}" class="perm-cb" style="width:16px;height:16px;accent-color:var(--gb)">
                                {{ $plabel }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="display:flex;gap:11px;margin-top:14px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeRoleModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mov" id="deleteRoleModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('deleteRoleModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p id="deleteRoleMsg" style="font-size:14px;color:#5a7a65;margin-bottom:22px"></p>
            <form id="deleteRoleForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px"
                            onclick="document.getElementById('deleteRoleModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
<script>
    const storeUrl = '{{ route('admin.roles.store') }}';

    function openRoleModal() {
        document.getElementById('roleModalTitle').textContent = 'إضافة دور جديد';
        document.getElementById('roleMethod').value = 'POST';
        document.getElementById('roleForm').action = storeUrl;
        document.getElementById('roleForm').reset();
        document.getElementById('rKey').disabled = false;
        document.querySelectorAll('.perm-cb').forEach(cb => cb.checked = false);
        document.getElementById('roleModal').classList.add('on');
    }

    function editRole(role) {
        document.getElementById('roleModalTitle').textContent = 'تعديل الدور: ' + role.label;
        document.getElementById('roleMethod').value = 'PUT';
        document.getElementById('roleForm').action = `/dashboard/roles/${role.id}`;
        document.getElementById('rLabel').value = role.label || '';
        document.getElementById('rKey').value = role.key || '';
        document.getElementById('rKey').disabled = true;
        document.getElementById('rEmoji').value = role.emoji || '';
        document.getElementById('rDesc').value = role.description || '';
        document.getElementById('rSort').value = role.sort_order || 0;

        const perms = role.permissions || [];
        document.querySelectorAll('.perm-cb').forEach(cb => {
            cb.checked = perms.includes(cb.value);
            cb.disabled = (role.key === 'super_admin');
        });

        document.getElementById('roleModal').classList.add('on');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.remove('on');
        document.querySelectorAll('.perm-cb').forEach(cb => cb.disabled = false);
    }

    function confirmDeleteRole(url, name) {
        document.getElementById('deleteRoleMsg').textContent = `هل أنت متأكد من حذف الدور "${name}"؟`;
        document.getElementById('deleteRoleForm').action = url;
        document.getElementById('deleteRoleModal').classList.add('on');
    }
</script>
@endpush
