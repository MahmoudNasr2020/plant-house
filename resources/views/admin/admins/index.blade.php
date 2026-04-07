@extends('admin.layouts.app')

@section('title', 'إدارة المديرين')
@section('page_title', '👤 إدارة المديرين والصلاحيات')

@section('topbar_actions')
    @if(auth()->user()->isSuperAdmin())
        <button class="btn-p" onclick="openAdminModal()">
            <i class="fa fa-user-plus"></i> إضافة مدير
        </button>
    @endif
@endsection

@section('content')

    <div class="tcard">
        <div class="thdr">
            <h4>المديرون ({{ $admins->count() }})</h4>
        </div>
        <table class="dtbl">
            <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الصلاحية</th>
                <th>الحالة</th>
                <th>تاريخ الإضافة</th>
                <th>إجراءات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--gf);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:var(--gd)">
                                {{ mb_substr($admin->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight:700">{{ $admin->name }}</div>
                                @if($admin->id === auth()->id())
                                    <div style="font-size:11px;color:var(--gb)">أنت</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        @php $adminRole = $roles->firstWhere('key', $admin->role); @endphp
                        @if($adminRole)
                            <span class="sbadge spr2">{{ $adminRole->emoji }} {{ $adminRole->label }}</span>
                        @else
                            <span class="sbadge sc">{{ $admin->role }}</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->is_active)
                            <span class="sbadge sd">نشط</span>
                        @else
                            <span class="sbadge sc">موقف</span>
                        @endif
                    </td>
                    <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if(auth()->user()->isSuperAdmin() && $admin->id !== auth()->id())
                            <button class="aico" title="تعديل" onclick="editAdmin({{ $admin->toJson() }})">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="aico" title="حذف" style="color:var(--red)"
                                    onclick="confirmDeleteAdmin('{{ route('admin.admins.destroy', $admin) }}', '{{ $admin->name }}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        @else
                            <span style="color:#9aa89e;font-size:12px">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#9aa89e;padding:28px">لا يوجد مديرون</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Permissions Info --}}
    <div class="fsec">
        <h3>
            <i class="fa fa-info-circle"></i> جدول الصلاحيات
            <a href="{{ route('admin.roles.index') }}" style="margin-right:auto;font-size:12px;color:var(--gb);font-weight:700;text-decoration:none">
                <i class="fa fa-edit"></i> إدارة الأدوار
            </a>
        </h3>
        <table class="dtbl">
            <thead>
            <tr>
                <th>الميزة</th>
                @foreach($roles as $role)
                    <th>{{ $role->emoji }} {{ $role->label }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @php
                $allPerms = \App\Http\Controllers\Admin\RoleController::allPermissions();
            @endphp
            @foreach($allPerms as $pkey => $plabel)
                <tr>
                    <td>{{ $plabel }}</td>
                    @foreach($roles as $role)
                        <td style="text-align:center">
                            {{ ($role->key === 'super_admin' || in_array($pkey, $role->permissions ?? [])) ? '✅' : '❌' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('modals')

    <div class="mov" id="adminModal">
        <div class="modal" style="width:500px">
            <button class="mcls" onclick="closeAdminModal()">✕</button>
            <div class="modal-title">
                <i class="fa fa-user-plus" style="color:var(--gb)"></i>
                <span id="adminModalTitle">إضافة مدير جديد</span>
            </div>

            <form id="adminForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="adminMethod" value="POST">

                <div class="frow">
                    <div class="fg">
                        <label>الاسم</label>
                        <input type="text" name="name" id="aName" required>
                    </div>
                    <div class="fg">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" id="aEmail" required>
                    </div>
                </div>

                <div class="frow">
                    <div class="fg">
                        <label>كلمة المرور <span id="pwHint" style="color:#9aa89e;font-size:11px">(اتركها فارغة لعدم التغيير)</span></label>
                        <input type="password" name="password" id="aPassword" placeholder="••••••••" minlength="8">
                    </div>
                    <div class="fg">
                        <label>الصلاحية / الدور</label>
                        <select name="role" id="aRole" class="tfltr" style="width:100%;padding:9px 12px;font-size:13.5px">
                            @foreach($roles as $role)
                                <option value="{{ $role->key }}">{{ $role->emoji }} {{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="fg" style="margin-bottom:14px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="is_active" id="aActive" value="1" style="width:17px;height:17px;accent-color:var(--gb)" checked>
                        الحساب نشط
                    </label>
                </div>

                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;padding:13px;justify-content:center">
                        <i class="fa fa-save"></i> حفظ
                    </button>
                    <button type="button" class="btn-s" style="padding:13px 20px" onclick="closeAdminModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mov" id="deleteAdminModal">
        <div class="modal" style="width:420px">
            <button class="mcls" onclick="document.getElementById('deleteAdminModal').classList.remove('on')">✕</button>
            <div class="modal-title"><i class="fa fa-trash" style="color:var(--red)"></i> تأكيد الحذف</div>
            <p id="deleteAdminMsg" style="font-size:14px;color:#5a7a65;margin-bottom:22px"></p>
            <form id="deleteAdminForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:11px">
                    <button type="submit" class="btn-p" style="flex:1;background:var(--red);justify-content:center">
                        <i class="fa fa-trash"></i> نعم، حذف
                    </button>
                    <button type="button" class="btn-s" style="padding:9px 20px"
                            onclick="document.getElementById('deleteAdminModal').classList.remove('on')">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

@endpush

@push('scripts')
    <script>
        const storeUrl = '{{ route('admin.admins.store') }}';

        function openAdminModal() {
            document.getElementById('adminModalTitle').textContent = 'إضافة مدير جديد';
            document.getElementById('adminMethod').value           = 'POST';
            document.getElementById('adminForm').action            = storeUrl;
            document.getElementById('adminForm').reset();
            document.getElementById('pwHint').style.display        = 'none';
            document.getElementById('aActive').checked             = true;
            document.getElementById('adminModal').classList.add('on');
        }

        function editAdmin(a) {
            document.getElementById('adminModalTitle').textContent = 'تعديل المدير';
            document.getElementById('adminMethod').value           = 'PUT';
            document.getElementById('adminForm').action            = `/dashboard/admins/${a.id}`;
            document.getElementById('aName').value                 = a.name  || '';
            document.getElementById('aEmail').value                = a.email || '';
            document.getElementById('aRole').value                 = a.role  || 'admin';
            document.getElementById('aPassword').value             = '';
            document.getElementById('pwHint').style.display        = 'inline';
            document.getElementById('aActive').checked             = !!a.is_active;
            document.getElementById('adminModal').classList.add('on');
        }

        function closeAdminModal() {
            document.getElementById('adminModal').classList.remove('on');
        }

        function confirmDeleteAdmin(url, name) {
            document.getElementById('deleteAdminMsg').textContent = `هل أنت متأكد من حذف المدير "${name}"؟`;
            document.getElementById('deleteAdminForm').action      = url;
            document.getElementById('deleteAdminModal').classList.add('on');
        }
    </script>
@endpush
