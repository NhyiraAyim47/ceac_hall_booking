@php
    $adminName   = admin_user()->full_name ?? 'Admin';
    $adminRole   = admin_role();
    $currentPage = request()->path() === 'admin' ? 'index' : basename(request()->path());
    $navItems = [
        ['href'=>'index',        'icon'=>'speedometer2',    'label'=>'Dashboard'],
        ['href'=>'bookings',     'icon'=>'calendar-check',  'label'=>'Bookings',  'badge'=>$pending],
        ['href'=>'payment_logs', 'icon'=>'graph-up',        'label'=>'Payments'],
        ['href'=>'events',       'icon'=>'calendar-event',  'label'=>'Events'],
        ['href'=>'halls',        'icon'=>'building',        'label'=>'Halls'],
        ['href'=>'messages',     'icon'=>'envelope',        'label'=>'Messages',  'badge'=>$unread],
        ['href'=>'users',        'icon'=>'people',          'label'=>'Admin Users', 'superOnly'=>true],
        ['href'=>'logs',         'icon'=>'clock-history',   'label'=>'Activity Log','superOnly'=>true],
        ['href'=>'settings',     'icon'=>'gear',            'label'=>'Settings'],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{!! $pageTitle ?? 'Admin' !!} | CEAC Admin Panel</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ base_url() }}/assets/css/main.css?v={{ config('ceac.version') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ base_url() }}/assets/images/logo.png">
    <link rel="shortcut icon" href="{{ base_url() }}/assets/images/logo.png">
    <style>
    body { font-family:'Inter',sans-serif; background:#F0F2F5; }
    .admin-topbar { background:white; border-bottom:1px solid #E5E7EB; padding:12px 24px; display:flex; align-items:center; gap:16px; position:sticky; top:0; z-index:999; }
    .admin-sidebar-brand { padding:20px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:12px; }
    .admin-sidebar-brand img { width:120px; height:40px; object-fit:contain; }
    .admin-sidebar-brand span { color:white; font-weight:700; font-size:0.9rem; }
    .admin-sidebar-nav { flex:1; padding:12px 0; overflow-y:auto; min-height:0; }
    .sidebar-section { padding:8px 16px 4px; font-size:0.68rem; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:1.5px; font-weight:600; }
    .admin-nav-badge { background:var(--ceac-orange); color:white; font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:100px; min-width:18px; text-align:center; }
    .admin-footer-nav { padding:16px; border-top:1px solid rgba(255,255,255,0.08); }
    /* Mobile sidebar overlay */
    .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; }
    .sidebar-overlay.show { display:block; }
    @media (max-width:992px) {
    .admin-sidebar { transition:transform 0.3s ease !important; z-index:1050 !important; }
    .admin-sidebar.open { box-shadow:4px 0 20px rgba(0,0,0,0.3) !important; }
    .admin-content { margin-left:0 !important; }
    .sidebar-overlay { z-index:1040 !important; }
    }
    .page-header { background:white; border-bottom:1px solid #E5E7EB; padding:20px 24px; margin-bottom:24px; }
    .page-header h4 { margin:0; font-weight:700; color:var(--ceac-dark); }
    </style>
</head>
<body>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <img src="{{ base_url() }}/assets/images/logo.png" alt="CEAC Logo">
        <div>
            <span class="d-block" style="font-size:0.85rem;font-weight:700;color:white;">CEAC Admin</span>
            <span style="font-size:0.68rem;color:var(--ceac-orange);">Airport City Zone</span>
        </div>
    </div>

    <nav class="admin-sidebar-nav">
        <div class="sidebar-section">Main</div>
        @php
foreach ($navItems as $item):
            if (!empty($item['superOnly']) && !is_super_admin()) continue;
            $active = ($currentPage === basename($item['href'], '.php'));
@endphp
        <a href="{!! $item['href'] !!}" class="admin-nav-link{!! $active ? ' active' : '' !!}">
            <i class="bi bi-{!! $item['icon'] !!}"></i>
            <span class="flex-grow-1">{!! $item['label'] !!}</span>
            @if (!empty($item['badge']) && $item['badge'] > 0)
            <span class="admin-nav-badge">{!! $item['badge'] !!}</span>
            @endif
        </a>
        @endforeach
        <div class="sidebar-section mt-3">Quick Actions</div>
        <a href="{{ base_url() }}/" target="_blank" class="admin-nav-link"><i class="bi bi-box-arrow-up-right"></i><span>View Website</span></a>
        <a href="logout" class="admin-nav-link" style="color:rgba(239,68,68,0.8);"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
    </nav>

    <div class="admin-footer-nav">
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;background:var(--ceac-orange);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.8rem;flex-shrink:0;">
                {!! strtoupper(substr($adminName,0,1)) !!}            </div>
            <div style="min-width:0;">
                <div style="color:white;font-size:0.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $adminName }}</div>
                <div style="color:rgba(255,255,255,0.4);font-size:0.7rem;">{!! ucfirst(str_replace('_',' ',$adminRole)) !!}</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Wrapper -->
<div class="admin-content">
    <!-- Topbar -->
    <div class="admin-topbar">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold">{!! $pageTitle ?? 'Dashboard' !!}</h6>
        </div>
        @if ($pending > 0)
        <a href="bookings?status=pending" class="btn btn-sm btn-warning d-flex align-items-center gap-1">
            <i class="bi bi-clock"></i>{!! $pending !!} Pending
        </a>
        @endif
        @if ($unread > 0)
        <a href="messages?filter=unread" class="btn btn-sm btn-info d-flex align-items-center gap-1 text-white">
            <i class="bi bi-envelope"></i>{!! $unread !!} Unread
        </a>
        @endif
        <!-- Notification Bell Toggle -->
        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" id="notifToggleBtn" title="Toggle in-app notifications">
            <i class="bi bi-bell" id="notifBellIcon"></i>
            <span class="d-none d-md-inline" id="notifBtnText">Notifications</span>
        </button>
        <a href="{{ base_url() }}/" target="_blank" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-up-right"></i>Site
        </a>
        <a href="logout" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i></a>
    </div>
    <script>
    window.CEAC_BASE  = '{{ base_url() }}';
    window.CEAC_CSRF  = '{!! csrf_token() !!}';
    </script>
    <!-- Page Content -->
    <div class="p-3 p-md-4">
@yield('content')

</div><!-- end page content -->
</div><!-- end admin-content -->

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index:9999;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Admin sidebar mobile toggle — runs first, independent of other scripts
(function () {
    const sb = document.getElementById('adminSidebar');
    const st = document.getElementById('sidebarToggle');
    const ov = document.getElementById('sidebarOverlay');
    function openSidebar()  { sb && sb.classList.add('open');    ov && ov.classList.add('show'); }
    function closeSidebar() { sb && sb.classList.remove('open'); ov && ov.classList.remove('show'); }
    if (st) st.addEventListener('click', function () {
        (sb && sb.classList.contains('open')) ? closeSidebar() : openSidebar();
    });
    if (ov) ov.addEventListener('click', closeSidebar);
})();
</script>
<script src="{{ base_url() }}/assets/js/main.js?v={{ config('ceac.version') }}"></script>
<script src="{{ base_url() }}/assets/js/notifications.js?v={{ config('ceac.version') }}"></script>
<script>
// Confirm dangerous actions
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (!confirm(this.dataset.confirm)) e.preventDefault();
    });
});
</script>
</body>
</html>