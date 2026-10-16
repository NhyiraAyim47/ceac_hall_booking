@extends('layouts.admin')

@section('content')
@if ($msg)
<div class="alert alert-{!! $msgType !!} d-flex align-items-center gap-2 alert-dismissible mb-4">
    <i class="bi bi-{!! $msgType==='success'?'check-circle-fill':'exclamation-triangle-fill' !!} flex-shrink-0"></i>
    <div>{{ $msg }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    <!-- ── Edit Profile ───────────────────────────────────── -->
    <div class="col-lg-6">
        <div class="bg-white rounded-3 border shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-1"><i class="bi bi-person-circle me-2 text-ceac-orange"></i>My Profile</h6>
            <p class="text-muted small mb-4">Update your name, username and email address</p>

            <form method="POST" novalidate>
                @csrf                <input type="hidden" name="update_profile" value="1">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="full_name" class="form-control"
                               required minlength="2" maxlength="150"
                               value="{{ $admin['full_name'] }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" name="username" class="form-control"
                               required minlength="3" maxlength="80"
                               pattern="[a-zA-Z0-9_]+"
                               value="{{ $admin['username'] }}">
                    </div>
                    <div class="form-text">Letters, numbers and underscores only.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               required maxlength="200"
                               value="{{ $admin['email'] }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-ceac-primary w-100">
                    <i class="bi bi-save me-1"></i>Save Profile Changes
                </button>
            </form>

            <!-- Read-only info -->
            <hr class="my-4">
            <table class="table table-borderless table-sm mb-0">
                <tr>
                    <th class="text-muted small ps-0" style="width:45%">Role</th>
                    <td>
                        <span class="badge bg-{!! $admin['role']==='super_admin'?'danger':($admin['role']==='admin'?'primary':'secondary') !!}">
                            {!! ucwords(str_replace('_',' ',$admin['role'])) !!}                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted small ps-0">Last Login</th>
                    <td class="small">{!! $admin['last_login'] ? date('D, M j, Y g:i A', strtotime($admin['last_login'])) : 'N/A' !!}</td>
                </tr>
                <tr>
                    <th class="text-muted small ps-0">Account Created</th>
                    <td class="small">{!! date('D, M j, Y', strtotime($admin['created_at'])) !!}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ── Change Password ────────────────────────────────── -->
    <div class="col-lg-6">
        <div class="bg-white rounded-3 border shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-1"><i class="bi bi-key me-2 text-ceac-orange"></i>Change Password</h6>
            <p class="text-muted small mb-4">Update your admin account password</p>

            <form method="POST" novalidate>
                @csrf                <input type="hidden" name="change_password" value="1">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="current_password" class="form-control"
                               required autocomplete="current-password" id="currentPwd">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleField('currentPwd','eyeC')">
                            <i class="bi bi-eye" id="eyeC"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="new_password" class="form-control"
                               required minlength="8" autocomplete="new-password" id="newPwd"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleField('newPwd','eyeN')">
                            <i class="bi bi-eye" id="eyeN"></i>
                        </button>
                    </div>
                    <!-- Password strength bar -->
                    <div class="mt-2">
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" id="strengthBar" style="width:0%;transition:all 0.3s;"></div>
                        </div>
                        <small class="text-muted" id="strengthText">Enter a new password</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" class="form-control"
                               required minlength="8" autocomplete="new-password" id="confirmPwd"
                               oninput="checkMatch()">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleField('confirmPwd','eyeCf')">
                            <i class="bi bi-eye" id="eyeCf"></i>
                        </button>
                    </div>
                    <small id="matchText" class="mt-1 d-block"></small>
                </div>

                <button type="submit" class="btn btn-ceac-primary w-100">
                    <i class="bi bi-shield-lock me-1"></i>Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- ── System Info ────────────────────────────────────── -->
    <div class="col-12">
        <div class="bg-white rounded-3 border shadow-sm p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-ceac-orange"></i>System Information</h6>
            <div class="row g-3">
                <div class="col-6 col-md-3 text-center p-3 bg-light rounded">
                    <div class="fw-bold text-ceac-orange">{{ config('ceac.version') }}</div>
                    <div class="small text-muted">App Version</div>
                </div>
                <div class="col-6 col-md-3 text-center p-3 bg-light rounded">
                    <div class="fw-bold">{!! phpversion() !!}</div>
                    <div class="small text-muted">PHP Version</div>
                </div>
                <div class="col-6 col-md-3 text-center p-3 bg-light rounded">
                    <div class="fw-bold">{{ ucfirst(config('app.env')) }}</div>
                    <div class="small text-muted">Environment</div>
                </div>
                <div class="col-6 col-md-3 text-center p-3 bg-light rounded">
                    <div class="fw-bold">{!! date('Y') !!}</div>
                    <div class="small text-muted">Current Year</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleField(fieldId, eyeId) {
    const f = document.getElementById(fieldId);
    const e = document.getElementById(eyeId);
    if (f.type === 'password') { f.type = 'text'; e.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; e.className = 'bi bi-eye'; }
}

function checkStrength(val) {
    const bar  = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let score  = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;

    const levels = [
        { pct:'20%', color:'#EF4444', label:'Very Weak' },
        { pct:'40%', color:'#F97316', label:'Weak' },
        { pct:'60%', color:'#F59E0B', label:'Fair' },
        { pct:'80%', color:'#84CC16', label:'Strong' },
        { pct:'100%',color:'#10B981', label:'Very Strong' },
    ];
    const lvl = levels[Math.min(score, 4)];
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    text.textContent     = 'Strength: ' + lvl.label;
    text.style.color     = lvl.color;
}

function checkMatch() {
    const np   = document.getElementById('newPwd').value;
    const cp   = document.getElementById('confirmPwd').value;
    const text = document.getElementById('matchText');
    if (!cp) { text.textContent = ''; return; }
    if (np === cp) {
        text.textContent  = '✓ Passwords match';
        text.style.color  = '#10B981';
    } else {
        text.textContent  = '✗ Passwords do not match';
        text.style.color  = '#EF4444';
    }
}
</script>
@endsection
