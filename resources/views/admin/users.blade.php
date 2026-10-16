@extends('layouts.admin')

@section('content')
@if ($msg)
<div class="alert alert-{!! $msgType !!} alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-{!! $msgType==='success'?'check-circle-fill':'exclamation-triangle-fill' !!}"></i>
    <div>{{ $msg }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    <!-- ── Create User Form ──────────────────────────────── -->
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border shadow-sm p-4">
            <h6 class="fw-bold mb-1">
                <i class="bi bi-person-plus me-2 text-ceac-orange"></i>Create Admin User
            </h6>
            <p class="text-muted small mb-4">Add a new administrator to the system</p>

            <form method="POST" novalidate>
                @csrf                <input type="hidden" name="action" value="create">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="full_name" class="form-control" required
                               placeholder="e.g. John Mensah">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" name="username" class="form-control" required
                               pattern="[a-zA-Z0-9_]{3,}" placeholder="e.g. john_admin">
                    </div>
                    <div class="form-text">Letters, numbers and underscores only. Min 3 characters.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required
                               placeholder="admin@example.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required
                               minlength="8" placeholder="Min 8 characters" id="createPwd">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePwd('createPwd','createPwdEye')">
                            <i class="bi bi-eye" id="createPwdEye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Role</label>
                    <select name="role" class="form-select">
                        <option value="admin">Admin</option>
                        <option value="viewer">Viewer (read-only)</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-ceac-primary w-100">
                    <i class="bi bi-person-plus me-1"></i>Create Admin User
                </button>
            </form>
        </div>
    </div>

    <!-- ── Users Table ───────────────────────────────────── -->
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border shadow-sm">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people me-2 text-ceac-orange"></i>
                    Admin Users ({!! count($users) !!})
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 admin-table">
                    <thead>
                        <tr>
                            <th>Name</th><th>Username</th><th>Email</th>
                            <th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $u)
                    <tr>
                        <td class="small fw-semibold">{{ $u['full_name'] }}</td>
                        <td class="small"><code>{{ $u['username'] }}</code></td>
                        <td class="small">{{ $u['email'] }}</td>
                        <td>
                            <span class="badge bg-{!! $u['role']==='super_admin'?'danger':($u['role']==='admin'?'primary':'secondary') !!}">
                                {!! ucwords(str_replace('_',' ',$u['role'])) !!}                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{!! $u['is_active']?'success':'secondary' !!}">
                                {!! $u['is_active']?'Active':'Inactive' !!}                            </span>
                        </td>
                        <td class="small text-muted">
                            {!! $u['last_login'] ? date('M j, Y', strtotime($u['last_login'])) : 'Never' !!}                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Edit button — opens modal -->
                                <button class="btn btn-sm btn-ceac-primary py-0 px-2"
                                        title="Edit User"
                                        onclick="openEditUserModal({{ json_encode([
                                            'id'        => $u['id'],
                                            'full_name' => $u['full_name'],
                                            'username'  => $u['username'],
                                            'email'     => $u['email'],
                                            'role'      => $u['role'],
                                        ]) }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <!-- Toggle active/inactive -->
                                @if ($u['id'] !== admin_id())
                                <form method="POST" class="d-inline">
                                    @csrf                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="user_id" value="{!! $u['id'] !!}">
                                    <button class="btn btn-sm btn-outline-{!! $u['is_active']?'warning':'success' !!} py-0 px-2"
                                            title="{!! $u['is_active']?'Disable':'Enable' !!} user">
                                        {!! $u['is_active']?'Disable':'Enable' !!}                                    </button>
                                </form>
                                @else
                                <span class="btn btn-sm btn-outline-secondary py-0 px-2 disabled">You</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!-- ── EDIT USER MODAL ─────────────────────────────────────── -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="editUserLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Admin User
                    </h5>
                    <p class="text-white-50 small mb-0">Update details for <span id="editUserNameDisplay"></span></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    @csrf                    <input type="hidden" name="action" value="edit_user">
                    <input type="hidden" name="user_id" id="editUserId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="full_name" id="editUserFullName"
                                   class="form-control" required maxlength="150">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" id="editUserUsername"
                                   class="form-control" required pattern="[a-zA-Z0-9_]{3,}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="editUserEmail"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role</label>
                        <select name="role" id="editUserRole" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer (read-only)</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">
                            New Password
                            <span class="text-muted fw-normal">(leave blank to keep current)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="new_password" id="editUserPwd"
                                   class="form-control" minlength="8"
                                   placeholder="Min 8 characters — leave blank to keep unchanged">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('editUserPwd','editUserPwdEye')">
                                <i class="bi bi-eye" id="editUserPwdEye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditUserModal(user) {
    document.getElementById('editUserId').value         = user.id;
    document.getElementById('editUserNameDisplay').textContent = user.full_name;
    document.getElementById('editUserFullName').value   = user.full_name;
    document.getElementById('editUserUsername').value   = user.username;
    document.getElementById('editUserEmail').value      = user.email;
    document.getElementById('editUserRole').value       = user.role;
    document.getElementById('editUserPwd').value        = '';
}

function togglePwd(fieldId, eyeId) {
    const f = document.getElementById(fieldId);
    const e = document.getElementById(eyeId);
    if (!f || !e) return;
    if (f.type === 'password') { f.type = 'text';     e.className = 'bi bi-eye-slash'; }
    else                       { f.type = 'password'; e.className = 'bi bi-eye'; }
}
</script>
@endsection
