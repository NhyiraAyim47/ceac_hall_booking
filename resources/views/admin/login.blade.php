<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login | CEAC Hall Booking</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root{--ceac-orange:#FF6B00;--ceac-dark:#0D1117;--ceac-blue:#1A2E4A;}
    *{box-sizing:border-box;}
    body{min-height:100vh;background:linear-gradient(135deg,var(--ceac-dark) 0%,var(--ceac-blue) 100%);display:flex;align-items:center;justify-content:center;font-family:'Inter',sans-serif;padding:16px;}
    .login-card{background:white;border-radius:20px;overflow:hidden;width:100%;max-width:440px;box-shadow:0 24px 80px rgba(0,0,0,0.5);}
    .login-header{background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));color:white;padding:28px 36px;text-align:center;border-bottom:3px solid var(--ceac-orange);}
    .login-header img{max-width:180px;height:56px;object-fit:contain;margin-bottom:12px;display:block;margin-left:auto;margin-right:auto;}
    .login-body{padding:32px;}
    .form-control:focus{border-color:var(--ceac-orange);box-shadow:0 0 0 3px rgba(255,107,0,0.15);}
    .btn-login{background:linear-gradient(135deg,var(--ceac-orange),#CC5500);color:white;border:none;padding:12px;font-weight:600;border-radius:10px;width:100%;transition:all 0.3s;font-size:0.95rem;}
    .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(255,107,0,0.4);color:white;}
    .btn-login:disabled{opacity:0.6;transform:none;cursor:not-allowed;}
    .input-group-text{background:#F9FAFB;border-color:#D1D5DB;color:var(--ceac-orange);}

    /* Countdown Timer */
    .lockout-box{background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:16px;margin-bottom:20px;}
    .lockout-box .lockout-title{color:#991B1B;font-weight:700;font-size:0.9rem;margin-bottom:8px;}
    .countdown-ring{display:flex;align-items:center;justify-content:center;gap:16px;margin:12px 0;}
    .countdown-timer{font-size:2.2rem;font-weight:800;color:#DC2626;font-variant-numeric:tabular-nums;letter-spacing:2px;}
    .countdown-label{font-size:0.72rem;color:#6B7280;text-align:center;}
    .lockout-unlocked{background:#ECFDF5;border:1px solid #A7F3D0;border-radius:12px;padding:16px;margin-bottom:20px;text-align:center;}
    .lockout-unlocked .unlock-icon{font-size:2rem;color:#10B981;margin-bottom:6px;}
    .lockout-unlocked p{color:#065F46;font-weight:600;margin:0;}

    /* Progress bar for lockout */
    .lockout-progress{height:6px;background:#FCA5A5;border-radius:3px;margin-top:10px;overflow:hidden;}
    .lockout-progress-bar{height:100%;background:#DC2626;border-radius:3px;transition:width 1s linear;}
    </style>
</head>
<body>
<div style="width:100%;max-width:440px;">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ base_url() }}/assets/images/logo.png" alt="CEAC Logo">
            <h4 class="fw-bold mb-1" style="font-size:1.1rem;">CEAC Admin Panel</h4>
            <p class="mb-0" style="color:rgba(255,255,255,0.5);font-size:0.8rem;">Christ Embassy Airport City &bull; Accra Ghana</p>
        </div>

        <div class="login-body">
            <h5 class="fw-bold mb-1">Sign In</h5>
            <p class="text-muted small mb-4">Enter your admin credentials to continue</p>

            @if ($lockoutSecs > 0)
            <!-- LOCKOUT COUNTDOWN -->
            <div class="lockout-box" id="lockoutBox">
                <div class="lockout-title"><i class="bi bi-lock-fill me-2"></i>Account Temporarily Locked</div>
                <p style="font-size:0.82rem;color:#7F1D1D;margin-bottom:8px;">
                    Too many failed login attempts. Please wait before trying again.
                </p>
                <div class="countdown-ring">
                    <div class="text-center">
                        <div class="countdown-timer" id="countdownDisplay">
                            {!! sprintf('%02d:%02d', floor($lockoutSecs/60), $lockoutSecs%60) !!}                        </div>
                        <div class="countdown-label">minutes : seconds remaining</div>
                    </div>
                </div>
                <div class="lockout-progress">
                    <div class="lockout-progress-bar" id="progressBar" style="width:100%"></div>
                </div>
            </div>

            <!-- UNLOCKED MESSAGE (hidden, shown when countdown ends) -->
            <div class="lockout-unlocked d-none" id="unlockedBox">
                <div class="unlock-icon"><i class="bi bi-unlock-fill"></i></div>
                <p>Account unlocked! You may now log in.</p>
            </div>

            @elseif ($error)
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-x-circle-fill flex-shrink-0"></i>
                <div>{{ $error }}</div>
            </div>
            @endif

            <form method="POST" action="" novalidate id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username or Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Enter username or email"
                               required autocomplete="username"
                               value="{{ $username ?? '' }}"
                               {!! $lockoutSecs > 0 ? 'disabled' : '' !!}>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password"
                               placeholder="Enter password" required autocomplete="current-password"
                               id="pwdField" {!! $lockoutSecs > 0 ? 'disabled' : '' !!}>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"
                                {!! $lockoutSecs > 0 ? 'disabled' : '' !!}>
                            <i class="bi bi-eye" id="pwdEye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-login" id="loginBtn" {!! $lockoutSecs > 0 ? 'disabled' : '' !!}>
                    <i class="bi bi-shield-lock me-2"></i>Sign In Securely
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ base_url() }}/" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Back to main website
                </a>
            </div>
        </div>

        <div class="px-4 pb-4 text-center">
            <small class="text-muted">Protected area &bull; Authorised personnel only<br>CEAC Hall Administration &bull; Airport City Zone, Accra</small>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const f = document.getElementById('pwdField'), e = document.getElementById('pwdEye');
    if (f.type === 'password') { f.type = 'text'; e.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; e.className = 'bi bi-eye'; }
}

@if ($lockoutSecs > 0)
// Countdown timer
let remaining = {!! $lockoutSecs !!};
const total   = {!! $lockoutSecs !!};
const display = document.getElementById('countdownDisplay');
const progBar = document.getElementById('progressBar');
const lockBox = document.getElementById('lockoutBox');
const unlockBox = document.getElementById('unlockedBox');
const loginBtn  = document.getElementById('loginBtn');
const inputs    = document.querySelectorAll('#loginForm input, #loginForm button');

const timer = setInterval(() => {
    remaining--;

    if (remaining <= 0) {
        clearInterval(timer);

        // Hide lockout box, show unlock message
        lockBox.classList.add('d-none');
        unlockBox.classList.remove('d-none');

        // Enable form
        inputs.forEach(el => el.disabled = false);
        loginBtn.disabled = false;
        loginBtn.innerHTML = '<i class="bi bi-shield-lock me-2"></i>Sign In Securely';

        // Auto-hide unlock message after 5 seconds
        setTimeout(() => {
            unlockBox.style.transition = 'opacity 0.5s';
            unlockBox.style.opacity = '0';
            setTimeout(() => unlockBox.classList.add('d-none'), 500);
        }, 5000);

        return;
    }

    // Update countdown display
    const mins = Math.floor(remaining / 60);
    const secs = remaining % 60;
    display.textContent = String(mins).padStart(2,'0') + ':' + String(secs).padStart(2,'0');

    // Update progress bar
    const pct = (remaining / total) * 100;
    progBar.style.width = pct + '%';

    // Change colour when nearly done
    if (remaining <= 30) {
        progBar.style.background = '#F59E0B';
        display.style.color = '#D97706';
    }
    if (remaining <= 10) {
        progBar.style.background = '#10B981';
        display.style.color = '#059669';
    }

}, 1000);
@endif
</script>
</body>
</html>
