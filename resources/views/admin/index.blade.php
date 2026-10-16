@extends('layouts.admin')

@section('content')
<!-- Stats Row -->
<div class="row g-3 mb-4">
    @php
$cards = [
        ['label'=>'Total Bookings',  'value'=>$stats['total_bookings'], 'icon'=>'calendar-check', 'bg'=>'linear-gradient(135deg,#FF6B00,#CC5500)', 'link'=>'bookings'],
        ['label'=>'Pending Review',  'value'=>$stats['pending'],        'icon'=>'clock',           'bg'=>'linear-gradient(135deg,#F59E0B,#B45309)', 'link'=>'bookings?status=pending'],
        ['label'=>'Approved Events', 'value'=>$stats['approved'],       'icon'=>'check-circle',    'bg'=>'linear-gradient(135deg,#10B981,#047857)', 'link'=>'bookings?status=approved'],
        ['label'=>'Unread Messages', 'value'=>$stats['messages_unread'],'icon'=>'envelope',        'bg'=>'linear-gradient(135deg,#3B82F6,#1D4ED8)', 'link'=>'messages?filter=unread'],
    ];
    foreach ($cards as $c):
@endphp
    <div class="col-6 col-lg-3">
        <a href="{!! $c['link'] !!}" class="text-decoration-none">
            <div class="admin-stat-card" style="background:{!! $c['bg'] !!}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 small">{!! $c['label'] !!}</p>
                        <div class="admin-stat-number">{!! $c['value'] !!}</div>
                    </div>
                    <i class="bi bi-{!! $c['icon'] !!}" style="font-size:2rem;opacity:0.4;"></i>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<!-- More Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="bg-white rounded-3 p-3 border text-center">
            <div class="fw-bold fs-4 text-ceac-orange">{!! $stats['this_month'] !!}</div>
            <div class="small text-muted">Bookings This Month</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="bg-white rounded-3 p-3 border text-center">
            <div class="fw-bold fs-4" style="color:#7C3AED">{!! $stats['upcoming_events'] !!}</div>
            <div class="small text-muted">Upcoming Events</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="bg-white rounded-3 p-3 border text-center">
            <div class="fw-bold fs-4" style="color:#059669">{!! $hall['capacity'] ?? 50 !!}</div>
            <div class="small text-muted">Hall Capacity</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="bg-white rounded-3 p-3 border text-center">
            <div class="fw-bold fs-4 {!! $hall['status']==='active'?'text-success':'text-danger' !!}">
                {!! ucfirst($hall['status'] ?? 'Active') !!}            </div>
            <div class="small text-muted">Hall Status</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Bookings -->
    <div class="col-lg-7">
        <div class="bg-white rounded-3 border shadow-sm">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-fill text-warning me-2"></i>Pending Bookings</h6>
                <a href="bookings?status=pending" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr class="table-light"><th>Ref</th><th>Name</th><th>Event</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    @if (empty($pendingB))
                        <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-check-circle-fill text-success me-2"></i>No pending bookings</td></tr>
                    @else @foreach ($pendingB as $b)
                        <tr>
                            <td><code class="small">{{ $b['booking_ref'] }}</code></td>
                            <td class="small fw-semibold">{{ $b['full_name'] }}</td>
                            <td class="small">{{ substr($b['event_title'],0,20) }}…</td>
                            <td class="small">
                                @php
$dTs = !empty($b['event_date']) ? strtotime($b['event_date']) : 0;
                                echo ($dTs && $dTs > 0 && date('Y',$dTs) > 1)
                                    ? date('M j, Y', $dTs)
                                    : '<span class="text-muted">—</span>';
@endphp
                            </td>
                            <td>
                                <a href="booking_action?id={!! $b['id'] !!}&action=approve" class="btn btn-xs btn-success btn-sm py-0 px-2 me-1" title="Approve"
                                   data-confirm="Approve this booking by {{ $b['full_name'] }}?">
                                    <i class="bi bi-check"></i>
                                </a>
                                <button class="btn btn-xs btn-danger btn-sm py-0 px-2" title="Reject Booking"
                                        onclick="openReasonModal({!! $b['id'] !!},'reject','{{ $b['full_name'] }}')">
                                    <i class="bi bi-x"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions + Hall Summary -->
    <div class="col-lg-5">
        <div class="bg-white rounded-3 border shadow-sm mb-3">
            <div class="p-3 border-bottom"><h6 class="fw-bold mb-0"><i class="bi bi-lightning-fill text-ceac-orange me-2"></i>Quick Actions</h6></div>
            <div class="p-3 d-grid gap-2">
                <a href="bookings" class="btn btn-ceac-primary"><i class="bi bi-calendar-check me-2"></i>Manage All Bookings</a>
                <a href="events" class="btn btn-outline-secondary"><i class="bi bi-calendar-event me-2"></i>Manage Events</a>
                <a href="messages" class="btn btn-outline-secondary"><i class="bi bi-envelope me-2"></i>View Messages {!! $unread > 0 ? "<span class='badge bg-danger'>$unread</span>" : '' !!}</a>
                @if (is_super_admin())
                <a href="users" class="btn btn-outline-secondary"><i class="bi bi-people me-2"></i>Admin Users</a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-3 border shadow-sm">
            <div class="p-3 border-bottom"><h6 class="fw-bold mb-0"><i class="bi bi-building me-2 text-ceac-orange"></i>CEAC Hall</h6></div>
            <div class="p-3">
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted small">Status</span><span class="fw-semibold status-badge {!! $hall['status']==='active'?'status-approved':'status-rejected' !!}">{!! ucfirst($hall['status']??'active') !!}</span></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted small">Capacity</span><span class="fw-semibold small">{!! $hall['capacity']??50 !!} people</span></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted small">Style</span><span class="fw-semibold small">{{ $hall['seating_style']??'Theatre' }}</span></div>
                <div class="d-flex justify-content-between py-2"><span class="text-muted small">Bookings This Month</span><span class="fw-semibold small">{!! $stats['this_month'] !!}</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="bg-white rounded-3 border shadow-sm mt-4">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-ceac-orange"></i>Recent Bookings</h6>
        <a href="bookings" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Ref</th><th>Name</th><th>Event</th><th>Date</th><th>Attendees</th><th>Status</th><th>Submitted</th><th>Actions</th></tr></thead>
            <tbody>
            @if (empty($recent))
                <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-calendar-x fs-3 d-block mb-2"></i>No bookings yet</td></tr>
            @else @foreach ($recent as $b)
                <tr>
                    <td><code class="small">{{ $b['booking_ref'] }}</code></td>
                    <td class="small fw-semibold">{{ $b['full_name'] }}</td>
                    <td class="small">{{ substr($b['event_title'],0,25) }}…</td>
                    <td class="small">
                        @php
$dTs = !empty($b['event_date']) ? strtotime($b['event_date']) : 0;
                        echo ($dTs && $dTs > 0 && date('Y',$dTs) > 1)
                            ? date('M j, Y', $dTs)
                            : '<span class="text-muted">—</span>';
@endphp
                    </td>
                    <td class="small text-center">{!! $b['expected_attendees'] !!}</td>
                    <td><span class="status-badge status-{!! $b['status'] !!}">{!! ucfirst($b['status']) !!}</span></td>
                    <td class="small text-muted">{!! date('M j', strtotime($b['created_at'])) !!}</td>
                    <td>
                        <a href="booking_detail?id={!! $b['id'] !!}" class="btn btn-sm btn-outline-secondary py-0 px-2"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
            @endforeach @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#991B1B,#7F1D1D);border-bottom:2px solid #EF4444;">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="reasonModalTitle">
                        <i class="bi bi-x-circle me-2"></i>Reject Booking
                    </h5>
                    <p class="text-white-50 small mb-0" id="reasonModalSubtitle">
                        Reason will be emailed to the booker
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reasonBookingId">
                <input type="hidden" id="reasonAction">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        Reason <span class="text-danger">*</span>
                    </label>
                    <textarea id="reasonText" class="form-control" rows="5"
                              placeholder="Please explain why this booking is being rejected. This message will be sent directly to the booker via email…"
                              maxlength="1000"></textarea>
                    <div class="form-text">
                        <span id="reasonCharCount">0</span>/1000 characters
                    </div>
                </div>
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    This reason will be saved to the booking record and emailed to the booker.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReasonBtn">
                    <i class="bi bi-send me-1"></i>
                    <span id="confirmReasonBtnText">Confirm Rejection</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openReasonModal(bookingId, action, bookerName) {
    document.getElementById('reasonBookingId').value = bookingId;
    document.getElementById('reasonAction').value    = action;
    document.getElementById('reasonText').value      = '';
    document.getElementById('reasonCharCount').textContent = '0';

    const isReject = action === 'reject';
    document.getElementById('reasonModalTitle').innerHTML =
        `<i class="bi bi-${isReject ? 'x-circle' : 'slash-circle'} me-2"></i>
         ${isReject ? 'Reject' : 'Cancel'} Booking`;
    document.getElementById('reasonModalSubtitle').textContent =
        `For booking by ${bookerName} — reason will be emailed to them`;
    document.getElementById('confirmReasonBtnText').textContent =
        isReject ? 'Confirm Rejection' : 'Confirm Cancellation';

    new bootstrap.Modal(document.getElementById('reasonModal')).show();
}

document.getElementById('reasonText')?.addEventListener('input', function () {
    document.getElementById('reasonCharCount').textContent = this.value.length;
});

document.getElementById('confirmReasonBtn')?.addEventListener('click', function () {
    const id     = document.getElementById('reasonBookingId').value;
    const action = document.getElementById('reasonAction').value;
    const reason = document.getElementById('reasonText').value.trim();

    if (!reason) {
        document.getElementById('reasonText').classList.add('is-invalid');
        document.getElementById('reasonText').focus();
        return;
    }
    document.getElementById('reasonText').classList.remove('is-invalid');
    window.location.href =
        `booking_action?id=${id}&action=${action}&reason=${encodeURIComponent(reason)}`;
});
</script>
@endsection
