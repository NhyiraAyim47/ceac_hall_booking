@extends('layouts.admin')

@section('content')
<div class="mb-3 d-flex align-items-center gap-2">
    <a href="bookings" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Bookings
    </a>
    @if ($savedAction && isset($actionMessages[$savedAction]))
    <span class="badge bg-{!! $actionMessages[$savedAction]['type'] !!} fs-6">
        {!! $actionMessages[$savedAction]['msg'] !!}    </span>
    @endif
</div>

@if (request()->has('saved'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
    Admin notes saved successfully.
</div>
@endif

@if ($hasConflict)
<div class="modal fade" id="conflictModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Approval Blocked — Double Booking</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>This booking could not be approved because it overlaps with an <strong>already-approved</strong> booking for this hall:</p>
        <ul class="mb-0">
          <li><strong>Booker:</strong> {{ $conflictName }}</li>
          <li><strong>Reference:</strong> {{ $conflictRef }}</li>
          <li><strong>Time:</strong> {{ $conflictStart }} – {{ $conflictEnd }}</li>
        </ul>
        <p class="mt-3 mb-0 text-muted small">To approve this booking, first cancel or mark the conflicting booking as completed.</p>
      </div>
      <div class="modal-footer">
        <a href="booking_detail?id={!! (int)(request()->query('id', 0)) !!}" class="btn btn-secondary">Close</a>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('conflictModal')).show();
});
</script>
@endif

<div class="row g-4">
    <!-- ── Booking Details ──────────────────────────────── -->
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Booking Details</h5>
                    <code class="text-ceac-orange fs-6">{{ $booking['booking_ref'] }}</code>
                </div>
                <span class="status-badge status-{!! $booking['status'] !!} fs-6">
                    {!! ucfirst($booking['status']) !!}                </span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Booker Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="small text-muted" style="width:40%">Full Name</th>
                            <td class="small fw-semibold">{{ $booking['full_name'] }}</td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Email</th>
                            <td>
                                <a href="https://mail.google.com/mail/?view=cm&to={!! urlencode($booking['email']) !!}&su={!! urlencode('Re: Your CEAC Hall Booking – ' . $booking['booking_ref']) !!}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="small text-ceac-orange text-decoration-none">
                                    {{ $booking['email'] }}                                    <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.7rem;"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Phone</th>
                            <td>
                                <a href="tel:{{ $booking['phone'] }}" class="small">
                                    {{ $booking['phone'] }}                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Organization</th>
                            <td class="small">{{ $booking['organization'] ?: '—' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Event Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="small text-muted" style="width:40%">Event Title</th>
                            <td class="small fw-semibold">{{ $booking['event_title'] }}</td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Event Type</th>
                            <td class="small">{!! ucwords(str_replace('_',' ',$booking['event_type'])) !!}</td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Hall</th>
                            <td class="small">{{ $booking['hall_name'] }}</td>
                        </tr>
                        <tr>
                            <th class="small text-muted">Attendees</th>
                            <td class="small fw-semibold">{!! $booking['expected_attendees'] !!} people</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Date &amp; Time</h6>
                    <table class="table table-sm table-borderless">
                        @php
$bdDate  = $booking['event_date']  ?? '';
                            $bdStart = $booking['start_time']  ?? '';
                            $bdEnd   = $booking['end_time']    ?? '';
                            $bdDateTs  = $bdDate  ? strtotime($bdDate)  : 0;
                            $bdStartTs = $bdStart ? strtotime($bdStart) : 0;
                            $bdEndTs   = $bdEnd   ? strtotime($bdEnd)   : 0;
@endphp
                            <tr>
                                <th class="small text-muted">Date</th>
                                <td class="small fw-semibold">
                                    {!! ($bdDateTs && $bdDateTs > 0) ? date('l, F j, Y', $bdDateTs) : '<span class="text-muted">No date given</span>' !!}                                </td>
                            </tr>
                            <tr>
                                <th class="small text-muted">Start Time</th>
                                <td class="small">
                                    {!! ($bdStartTs && $bdStartTs > 0) ? date('g:i A', $bdStartTs) : '<span class="text-muted">—</span>' !!}                                </td>
                            </tr>
                            <tr>
                                <th class="small text-muted">End Time</th>
                                <td class="small">
                                    {!! ($bdEndTs && $bdEndTs > 0) ? date('g:i A', $bdEndTs) : '<span class="text-muted">—</span>' !!}                                </td>
                            </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Facilities Requested</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($booking['needs_projector'])
                        <span class="badge bg-light text-dark border"><i class="bi bi-display me-1"></i>Screen</span>
                        @endif
                        @if ($booking['needs_pa_system'])
                        <span class="badge bg-light text-dark border"><i class="bi bi-speaker me-1"></i>PA System</span>
                        @endif
                        @if ($booking['needs_ac'])
                        <span class="badge bg-light text-dark border"><i class="bi bi-thermometer-snow me-1"></i>AC</span>
                        @endif
                        @if ($booking['needs_whiteboard'])
                        <span class="badge bg-light text-dark border"><i class="bi bi-easel me-1"></i>Whiteboard</span>
                        @endif
                    </div>
                </div>
                @if ($booking['special_requirements'])
                <div class="col-12">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Special Requirements</h6>
                    <div class="bg-light rounded p-3 small">
                        {!! nl2br(htmlspecialchars($booking['special_requirements'])) !!}                    </div>
                </div>
                @endif
                
                @php
// Fetch hall pricing and calculate bill
                $bHall    = \App\Support\Sql::fetch("SELECT name, pricing_json FROM halls WHERE id=?", [$booking['hall_id']]);
                $bPricing = !empty($bHall['pricing_json']) ? (json_decode($bHall['pricing_json'], true) ?: []) : [];
                $bDurType = !empty($booking['booking_duration_type']) ? $booking['booking_duration_type'] : 'half';
                $bEvType  = !empty($booking['event_type']) ? $booking['event_type'] : 'meetings';
                $bCurSym  = ($bPricing['currency'] ?? 'GHS') === 'USD' ? '$' : (($bPricing['currency'] ?? 'GHS') === 'EUR' ? '€' : '₵');

                $bVenuePrice = 0; $bDurLabel = '';
                if ($bEvType === 'all_night') {
                    $bVenuePrice = (int)($bPricing['all_night_price'] ?? 0);
                    $bDurLabel   = ($bPricing['all_night_hours'] ?? 5) . ' hrs (All Night)';
                } elseif ($bEvType) {
                    if ($bDurType === 'full') {
                        $bVenuePrice = (int)($bPricing[$bEvType . '_full_price'] ?? 0);
                        $bDurLabel   = ($bPricing[$bEvType . '_full_hours'] ?? 4) . ' hrs (Full Time)';
                    } else {
                        $bVenuePrice = (int)($bPricing[$bEvType . '_half_price'] ?? 0);
                        $bDurLabel   = ($bPricing[$bEvType . '_half_hours'] ?? 2) . ' hrs (Half Time)';
                    }
                }

                $bFacilities   = [];
                $bFacilityTotal = 0;
                $facMap = [
                    'needs_projector'  => ['facility_projector',  'Display Screen'],
                    'needs_pa_system'  => ['facility_pa_system',  'PA System'],
                    'needs_ac'         => ['facility_ac',          'Air Conditioning'],
                    'needs_whiteboard' => ['facility_whiteboard',  'Whiteboard'],
                ];
                foreach ($facMap as $field => [$key, $label]) {
                    if ($booking[$field] && !empty($bPricing[$key])) {
                        $amt = (int)$bPricing[$key];
                        $bFacilities[]  = [$label, $amt];
                        $bFacilityTotal += $amt;
                    }
                }
                $bGrandTotal = $bVenuePrice + $bFacilityTotal;
@endphp
                @if ($bGrandTotal > 0)
                <div class="col-12">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2">
                        <i class="bi bi-cash-coin me-1"></i>Bill Summary
                    </h6>
                    <div class="rounded-3 overflow-hidden border">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @if ($bVenuePrice > 0)
                                <tr>
                                    <td class="small text-muted">Venue — {{ $bDurLabel }}</td>
                                    <td class="small text-end">{!! $bCurSym . number_format($bVenuePrice) !!}</td>
                                </tr>
                                @endif
                                @foreach ($bFacilities as [$fLabel, $fAmt])
                                <tr>
                                    <td class="small text-muted">{{ $fLabel }}</td>
                                    <td class="small text-end">{!! $bCurSym . number_format($fAmt) !!}</td>
                                </tr>
                                @endforeach
                                <tr style="background:#FFF7ED;">
                                    <td class="fw-bold" style="color:#92400E;">
                                        <i class="bi bi-calculator me-1"></i>TOTAL ESTIMATED COST
                                    </td>
                                    <td class="fw-bold text-end fs-6" style="color:#FF6B00;">
                                        {!! $bCurSym . number_format($bGrandTotal) !!}                                    </td>
                                </tr>
                                @if (!empty($bPricing['penalty_per_hour']))
                                <tr class="bg-light">
                                    <td colspan="2" class="small text-muted fst-italic">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        A penalty of {!! $bCurSym . $bPricing['penalty_per_hour'] !!}/hr applies if booked time is exceeded.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Admin Notes -->
            <form method="POST" class="mt-4 pt-4 border-top">
                @csrf                <label class="fw-bold small text-uppercase text-muted mb-2 d-block">
                    <i class="bi bi-sticky me-1"></i>Admin Notes (private)
                </label>
                <textarea name="admin_notes" class="form-control mb-3" rows="3"
                          placeholder="Add private notes about this booking…">{{ $booking['admin_notes'] ?? '' }}</textarea>
                <button type="submit" class="btn btn-sm btn-ceac-primary">
                    <i class="bi bi-save me-1"></i>Save Notes
                </button>
            </form>
        </div>
    </div>
    
    @php
$totalCost   = $bGrandTotal;
	$currencySym = $bCurSym;
    $amountPaid  = getBookingPaymentTotal($id);
    $balanceDue  = $totalCost - $amountPaid;
    $paymentStatus = $totalCost <= 0 ? 'N/A' : ($amountPaid <= 0 ? 'Unpaid' : ($balanceDue <= 0 ? 'Paid' : 'Partial'));
    $statusColor = ['Unpaid' => 'danger', 'Partial' => 'warning', 'Paid' => 'success', 'N/A' => 'secondary'][$paymentStatus];
@endphp
    <div class="bg-white rounded-3 border shadow-sm p-4 mb-3">
        <h6 class="fw-bold mb-3">Payment Tracking</h6>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Total Cost</span>
            <strong>{!! $currencySym !!}{!! number_format($totalCost, 2) !!}</strong>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Amount Paid</span>
            <strong class="text-success">{!! $currencySym !!}{!! number_format($amountPaid, 2) !!}</strong>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">Balance Due</span>
            <strong class="{!! $balanceDue > 0 ? 'text-danger' : '' !!}">{!! $currencySym !!}{!! number_format($balanceDue, 2) !!}</strong>
        </div>
        <span class="badge bg-{!! $statusColor !!} mb-3">{!! $paymentStatus !!}</span>

        <form method="POST" action="payment_action" class="border-top pt-3">
            @csrf            <input type="hidden" name="booking_id" value="{!! $id !!}">
            <div class="row g-2">
                <div class="col-6">
                    <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-sm" placeholder="Amount" required>
                </div>
                <div class="col-6">
                    <select name="method" class="form-select form-select-sm" required>
                        <option value="">Method</option>
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div class="col-12">
                    <input type="text" name="reference_note" class="form-control form-control-sm" placeholder="Reference / note (optional)">
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-ceac-primary mt-2 w-100">Log Payment</button>
        </form>

        @php
$payments = getBookingPayments($id); if ($payments):
@endphp
        <div class="mt-3 border-top pt-2">
            <p class="small fw-bold mb-1">Payment History</p>
            @foreach ($payments as $p)
            <div class="small text-muted d-flex justify-content-between">
                <span>{!! date('M j, g:i A', strtotime($p['created_at'])) !!} — {!! ucfirst(str_replace('_',' ',$p['method'])) !!}</span>
                <span>{!! $currencySym !!}{!! number_format($p['amount'], 2) !!}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- ── Actions + Meta ──────────────────────────────── -->
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border shadow-sm p-4 mb-3">
            <h6 class="fw-bold mb-3">Actions</h6>
            <div class="d-grid gap-2">
                @if ($booking['status'] === 'pending')
                <a href="booking_action?id={!! $id !!}&action=approve&csrf_token={{ csrf_token() }}"
                   class="btn btn-success"
                   data-confirm="Approve booking {{ $booking['booking_ref'] }}?">
                    <i class="bi bi-check-circle me-1"></i>Approve Booking
                </a>
                <button class="btn btn-danger w-100"
                        onclick="openReasonModal({!! $id !!},'reject','{{ $booking['full_name'] }}')">
                    <i class="bi bi-x-circle me-1"></i>Reject Booking
                </button>
                @elseif ($booking['status'] === 'approved')
                <a href="booking_action?id={!! $id !!}&action=complete&csrf_token={{ csrf_token() }}"
                   class="btn btn-primary"
                   data-confirm="Mark as completed?">
                    <i class="bi bi-check-all me-1"></i>Mark Completed
                </a>
                <button class="btn btn-outline-danger w-100"
                        onclick="openReasonModal({!! $id !!},'cancel','{{ $booking['full_name'] }}')">
                    <i class="bi bi-x me-1"></i>Cancel Booking
                </button>
                @elseif (in_array($booking['status'], ['rejected', 'cancelled']))
                <a href="booking_action?id={!! $id !!}&action=approve&csrf_token={{ csrf_token() }}"
                   class="btn btn-success"
                   data-confirm="This booking was previously {!! $booking['status'] !!}. Re-approve {{ $booking['booking_ref'] }} now?">
                    <i class="bi bi-arrow-repeat me-1"></i>Re-approve Booking
                </a>
                <p class="text-muted small mt-1 mb-0">
                    <i class="bi bi-info-circle me-1"></i>This booking was previously {!! $booking['status'] !!}. Re-approving will notify the booker by email and will be blocked if it now conflicts with another approved booking.
                </p>
                @endif

                <!-- Reply to Booker — opens reply modal -->
                <button class="btn btn-ceac-primary"
                        data-reply-email="{{ $booking['email'] }}"
                        data-reply-name="{{ $booking['full_name'] }}"
                        data-reply-subject="{{ 'Re: Your Hall Booking – ' . $booking['booking_ref'] }}"
                        data-reply-body="{{ "Dear {$booking['full_name']},\n\nRegarding your booking reference: {$booking['booking_ref']}\nEvent: {$booking['event_title']}\n\n" }}"
                        onclick="openReplyModal(this)">
                    <i class="bi bi-envelope me-1"></i>Reply to Booker
                </button>
            </div>
        </div>

        <div class="bg-white rounded-3 border shadow-sm p-4">
            <h6 class="fw-bold mb-3">Meta Information</h6>
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="small text-muted">Submitted</th>
                    <td class="small">{!! date('M j, Y g:i A', strtotime($booking['created_at'])) !!}</td>
                </tr>
                <tr>
                    <th class="small text-muted">Last Updated</th>
                    <td class="small">{!! date('M j, Y g:i A', strtotime($booking['updated_at'])) !!}</td>
                </tr>
                <tr>
                    <th class="small text-muted">IP Address</th>
                    <td class="small"><code>{{ $booking['ip_address'] ?? 'N/A' }}</code></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Reject/Cancel Reason Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#991B1B,#7F1D1D);border-bottom:2px solid #EF4444;">
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

<script>
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

document.getElementById('reasonText')?.addEventListener('input', function() {
    document.getElementById('reasonCharCount').textContent = this.value.length;
});

document.getElementById('confirmReasonBtn')?.addEventListener('click', function() {
    const id     = document.getElementById('reasonBookingId').value;
    const action = document.getElementById('reasonAction').value;
    const reason = document.getElementById('reasonText').value.trim();

    if (!reason) {
        document.getElementById('reasonText').classList.add('is-invalid');
        document.getElementById('reasonText').focus();
        return;
    }
    document.getElementById('reasonText').classList.remove('is-invalid');

    // Redirect to booking_action with reason encoded in URL
    window.location.href = `booking_action?id=${id}&action=${action}&csrf_token=${window.CEAC_CSRF}&reason=${encodeURIComponent(reason)}`;
});
</script>

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

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold"><i class="bi bi-reply me-2"></i>Send Reply</h5>
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
                            <input type="text" name="reply_subject" id="replySubject" class="form-control" required maxlength="200">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Message</label>
                        <textarea name="reply_body" id="replyBody" class="form-control" rows="10" required style="font-family:monospace;font-size:0.88rem;"></textarea>
                    </div>
                    <div class="p-3 bg-light rounded border d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Prefer Gmail directly?</small>
                        <a id="replyGmailLink" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Open in Gmail
                        </a>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ceac-primary"><i class="bi bi-send me-1"></i>Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
        '&su=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
    new bootstrap.Modal(document.getElementById('replyModal')).show();
}
</script>
@endsection
