@extends('layouts.admin')

@section('content')
@if (session()->has('reply_result'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    @if (session('reply_result') === 'sent')
    showToast('Reply sent successfully!', 'success');
    @else
    showToast('Reply failed: {!! addslashes(session('reply_error_detail', 'Unknown error — check server error log')) !!}', 'error');
    @endif
});
</script>
@endif

<!-- Filters -->
<div class="bg-white rounded-3 border shadow-sm p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3 col-6">
            <label class="form-label fw-semibold small">Search</label>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Name, email, ref…" value="{{ $search }}">
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach (['pending','approved','rejected','cancelled','completed'] as $s)
                <option value="{!! $s !!}" {!! $status===$s?'selected':'' !!}>{!! ucfirst($s) !!}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold small">Date From</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateF }}">
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold small">Date To</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateT }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-ceac-primary btn-sm flex-grow-1">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            <a href="bookings" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<!-- Results Summary -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0 text-muted small">
        Showing <strong>{!! count($bookings) !!}</strong> of <strong>{!! $total !!}</strong> bookings
        {!! $status ? "with status <strong>" . ucfirst($status) . "</strong>" : "" !!}    </p>
    <div class="d-flex gap-2">
        <a href="bookings?status=pending"
           class="btn btn-sm {!! $status==='pending'?'btn-warning':'btn-outline-warning' !!}">
            Pending ({{ $pendingCount }})
        </a>
        <a href="bookings?status=approved"
           class="btn btn-sm {!! $status==='approved'?'btn-success':'btn-outline-success' !!}">
            Approved ({{ $approvedCount }})
        </a>
    </div>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-3 border shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 admin-table">
            <thead>
                <tr>
                    <th>Ref</th><th>Name / Contact</th><th>Event</th><th>Hall</th>
                    <th>Date & Time</th><th>No. of Attendees</th><th>Status</th><th>Submitted</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (empty($bookings))
                <tr><td colspan="9" class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>No bookings found matching your filters.
                </td></tr>
            @else @foreach ($bookings as $b)
            <tr>
                <td><code class="small fw-bold">{{ $b['booking_ref'] }}</code></td>
                <td>
                    <div class="fw-semibold small">{{ $b['full_name'] }}</div>
                    <div class="text-muted small">{{ $b['email'] }}</div>
                    <div class="text-muted small"><i class="bi bi-telephone"></i> {{ $b['phone'] }}</div>
                </td>
                <td>
                    <div class="small fw-semibold">{{ $b['event_title'] }}</div>
                    <div class="text-muted small">{!! ucwords(str_replace('_', ' ', $b['event_type'])) !!}</div>
                </td>
                <td class="small">{{ $b['hall_name'] }}</td>
                <td>
                    @php
$bDate   = $b['event_date'] ?? '';
                    $bStart  = $b['start_time'] ?? '';
                    $bEnd    = $b['end_time']   ?? '';
                    $dateTs  = $bDate  ? strtotime($bDate)  : 0;
                    $startTs = $bStart ? strtotime($bStart) : 0;
                    $endTs   = $bEnd   ? strtotime($bEnd)   : 0;
@endphp
                    <div class="small fw-semibold">
                        {!! ($dateTs && $dateTs > 0) ? date('D, M j, Y', $dateTs) : '<span class="text-muted">No date given</span>' !!}                    </div>
                    <div class="text-muted small">
                        @if ($startTs && $startTs > 0)
                            {!! date('g:i A', $startTs) !!}                            {!! ($endTs && $endTs > 0) ? ' – ' . date('g:i A', $endTs) : '' !!}                        @else
                            <span class="text-muted">No time given</span>
                        @endif
                    </div>
                </td>
                <td class="small text-center">{!! $b['expected_attendees'] !!}</td>
                <td><span class="status-badge status-{!! $b['status'] !!}">{!! ucfirst($b['status']) !!}</span></td>
                @php
$isOverrun = false;
                if ($b['status'] === 'approved' && $b['event_date'] && $b['end_time']) {
                    $endDateTime = strtotime($b['event_date'] . ' ' . $b['end_time']);
                    $minutesOver = ($endDateTime && $endDateTime > 0) ? floor((time() - $endDateTime) / 60) : 0;
                    $isOverrun = $minutesOver > 15;
                }
@endphp
                @if ($isOverrun)
                <span class="badge bg-danger ms-1" title="This booking's approved time has ended but it hasn't been marked completed">
                    <i class="bi bi-exclamation-triangle"></i> Overrunning ({!! floor($minutesOver / 60) !!}h {!! $minutesOver % 60 !!}m)
                </span>
                @endif

                @php
$pricing = !empty($b['pricing_json']) ? (json_decode($b['pricing_json'], true) ?: []) : [];
                if ($isOverrun && !empty($pricing['penalty_per_hour'])) {
                    $overrunHours = ceil($minutesOver / 60);
                    $penaltyAmt = $overrunHours * (int)$pricing['penalty_per_hour'];
                    echo "<div class='text-danger small mt-1'>Est. penalty: ₵" . number_format($penaltyAmt) . "</div>";
                }
@endphp
                <td class="text-muted small">{!! date('M j, Y g:i A', strtotime($b['created_at'])) !!}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="booking_detail?id={!! $b['id'] !!}"
                           class="btn btn-sm btn-outline-secondary py-0 px-2" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="booking_edit?id={!! $b['id'] !!}"
                           class="btn btn-sm btn-outline-secondary py-0 px-2" title="Edit Details">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if ($b['status'] === 'pending')
                        <a href="booking_action?id={!! $b['id'] !!}&action=approve&csrf_token={{ csrf_token() }}"
                           class="btn btn-sm btn-success py-0 px-2" title="Approve"
                           data-confirm="Approve booking {!! $b['booking_ref'] !!}?">
                            <i class="bi bi-check"></i>
                        </a>
                        <button class="btn btn-sm btn-danger py-0 px-2" title="Reject Booking"
                                onclick="openReasonModal({!! $b['id'] !!},'reject','{{ $b['full_name'] }}')">
                            <i class="bi bi-x"></i>
                        </button>
                        @endif
                        @if ($b['status'] === 'approved')
                        <a href="booking_action?id={!! $b['id'] !!}&action=complete&csrf_token={{ csrf_token() }}"
                           class="btn btn-sm btn-outline-primary py-0 px-2" title="Mark Completed"
                           data-confirm="Mark as completed?">
                            <i class="bi bi-check-all"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Cancel Booking"
                                onclick="openReasonModal({!! $b['id'] !!},'cancel','{{ $b['full_name'] }}')">
                            <i class="bi bi-x"></i>
                        </button>
                        @endif
                        @if (in_array($b['status'], ['rejected', 'cancelled']))
                        <a href="booking_action?id={!! $b['id'] !!}&action=approve&csrf_token={{ csrf_token() }}"
                           class="btn btn-sm btn-success py-0 px-2" title="Re-approve"
                           data-confirm="This booking was previously {!! $b['status'] !!}. Re-approve it?">
                            <i class="bi bi-arrow-repeat"></i>
                        </a>
                        @endif
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Booking"
                                onclick="openDeleteModal('booking',{!! $b['id'] !!},'{{ $b['booking_ref'] }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary py-0 px-2"
                                title="Reply to Booker"
                                data-reply-email="{{ $b['email'] }}"
                                data-reply-name="{{ $b['full_name'] }}"
                                data-reply-subject="{{ 'Re: Your Hall Booking – ' . $b['booking_ref'] }}"
                                data-reply-body="{{ "Dear {$b['full_name']},\n\nRegarding your booking reference: {$b['booking_ref']}\nEvent: {$b['event_title']}\n\n" }}"
                                onclick="openReplyModal(this)">
                            <i class="bi bi-reply"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach @endif
            </tbody>
        </table>
    </div>

    @if ($totalPages > 1)
    <div class="p-3 border-top">
        <nav aria-label="Bookings pagination">
            <ul class="pagination pagination-sm justify-content-center mb-0">
                @for ($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {!! $i===$page?'active':'' !!}">
                    <a class="page-link"
                       href="?page={!! $i !!}&status={!! urlencode($status) !!}&search={!! urlencode($search) !!}">
                        {!! $i !!}                    </a>
                </li>
                @endfor
            </ul>
        </nav>
    </div>
    @endif
</div>

<!-- Reject/Cancel Reason Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#991B1B,#7F1D1D);border-bottom:2px solid #EF4444;">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="reasonModalTitle">
                        <i class="bi bi-x-circle me-2"></i>Provide Reason
                    </h5>
                    <p class="text-white-50 small mb-0" id="reasonModalSubtitle">This reason will be sent to the booker</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reasonBookingId">
                <input type="hidden" id="reasonAction">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Reason <span class="text-danger">*</span></label>
                    <textarea id="reasonText" class="form-control" rows="5"
                              placeholder="Please explain why this booking is being rejected/cancelled. This message will be sent directly to the booker via email…"
                              maxlength="1000"></textarea>
                    <div class="form-text"><span id="reasonCharCount">0</span>/1000 characters</div>
                </div>
                <div class="alert alert-warning py-2 small">
                    <i class="bi bi-info-circle me-1"></i>
                    This reason will be saved to the booking record and emailed to the booker.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReasonBtn">
                    <i class="bi bi-send me-1"></i><span id="confirmReasonBtnText">Confirm Rejection</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-reply me-2"></i>Send Reply
                    </h5>
                    <p class="text-white-50 small mb-0">Message will be sent from hallbookingceac@gmail.com</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;">
                    @csrf                    <input type="hidden" name="reply_email_send" value="1">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">To</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control bg-light" id="replyToName" readonly>
                            <input type="hidden" name="reply_to_name" id="replyToNameHidden">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control bg-light" id="replyToDisplay" readonly>
                            <input type="hidden" name="reply_to" id="replyToHidden">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Subject</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" name="reply_subject" id="replySubject"
                                   class="form-control" required maxlength="200">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Message</label>
                        <textarea name="reply_body" id="replyBody"
                                  class="form-control" rows="10" required
                                  style="font-family:monospace;font-size:0.88rem;"></textarea>
                    </div>

                    <div class="p-3 bg-light rounded border d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Prefer to reply directly from your Gmail account instead?
                        </small>
                        <a id="replyGmailLink" href="#" target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Open in Gmail
                        </a>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-send me-1"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Unified Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#7F1D1D,#991B1B);border-bottom:2px solid #EF4444;">
                <div>
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-trash me-2"></i>Delete <span id="deleteItemType"></span>
                    </h5>
                    <p class="text-white-50 small mb-0" id="deleteItemName"></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">How would you like to delete this <span id="deleteItemTypeBody"></span>?</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#F59E0B!important;background:#FFFBEB;cursor:pointer;"
                             onclick="confirmDelete('soft')">
                            <i class="bi bi-eye-slash fs-3 text-warning d-block mb-2"></i>
                            <strong class="d-block small">Delete from View</strong>
                            <span class="text-muted" style="font-size:0.75rem;">
                                Removed from dashboard but record stays in database
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#EF4444!important;background:#FEF2F2;cursor:pointer;"
                             onclick="confirmDelete('hard')">
                            <i class="bi bi-trash fs-3 text-danger d-block mb-2"></i>
                            <strong class="d-block small">Delete Permanently</strong>
                            <span class="text-muted" style="font-size:0.75rem;">
                                Completely removed from database. Cannot be undone
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
let _deleteType = '', _deleteId = 0, _deleteMode = '';

function openReasonModal(bookingId, action, bookerName) {
    document.getElementById('reasonBookingId').value = bookingId;
    document.getElementById('reasonAction').value    = action;
    document.getElementById('reasonText').value      = '';
    document.getElementById('reasonCharCount').textContent = '0';

    const isReject = action === 'reject';
    document.getElementById('reasonModalTitle').innerHTML =
        `<i class="bi bi-${isReject ? 'x-circle' : 'slash-circle'} me-2"></i>${isReject ? 'Reject' : 'Cancel'} Booking`;
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
    window.location.href = `booking_action?id=${id}&action=${action}&csrf_token=${window.CEAC_CSRF}&reason=${encodeURIComponent(reason)}`;
});

function openReplyModal(btn) {
    const email   = btn.dataset.replyEmail;
    const name    = btn.dataset.replyName;
    const subject = btn.dataset.replySubject;
    const body    = btn.dataset.replyBody;

    document.getElementById('replyToDisplay').value    = email;
    document.getElementById('replyToHidden').value     = email;
    document.getElementById('replyToName').value       = name;
    document.getElementById('replyToNameHidden').value = name;
    document.getElementById('replySubject').value      = subject;
    document.getElementById('replyBody').value         = body;

    document.getElementById('replyGmailLink').href =
        'https://mail.google.com/mail/?view=cm&to=' + encodeURIComponent(email) +
        '&su=' + encodeURIComponent(subject) +
        '&body=' + encodeURIComponent(body);

    new bootstrap.Modal(document.getElementById('replyModal')).show();
}

function openDeleteModal(type, id, name) {
    _deleteType = type;
    _deleteId   = id;
    document.getElementById('deleteItemType').textContent     = type.charAt(0).toUpperCase() + type.slice(1);
    document.getElementById('deleteItemTypeBody').textContent = type;
    document.getElementById('deleteItemName').textContent     = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete(mode) {
    const urls = {
        booking: { soft: `bookings?soft_delete=${_deleteId}&csrf_token={!! csrf_token() !!}`,  hard: `bookings?hard_delete=${_deleteId}&csrf_token={!! csrf_token() !!}` },
        event:   { soft: `events?soft_delete=${_deleteId}`,   hard: `events?delete=${_deleteId}` },
        message: { soft: `messages?delete=${_deleteId}`,      hard: `messages?hard_delete=${_deleteId}` },
        hall:    { soft: `halls?soft_delete=${_deleteId}`,    hard: `halls?delete=${_deleteId}` },
    };
    window.location.href = urls[_deleteType][mode];
}
</script>
@endsection
