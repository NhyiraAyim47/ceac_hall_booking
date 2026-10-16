@extends('layouts.admin')

@section('content')
@if ($msg)
<div class="alert alert-{!! $msgType !!} alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-{!! $msgType==='success'?'check-circle-fill':'exclamation-triangle-fill' !!}"></i>
    <div>{{ $msg }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Page Actions Bar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="fw-bold mb-1">Event Management</h6>
        <p class="text-muted small mb-0">{!! count($events) !!} event{!! count($events)!==1?'s':'' !!} registered</p>
    </div>
    <button class="btn btn-ceac-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Event
    </button>
</div>

<!-- Events Table (full width) -->
<div class="bg-white rounded-3 border shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 admin-table">
            <thead>
                <tr>
                    <th>Title</th><th>Type</th><th>Hall</th><th>Date</th>
                    <th>Organizer</th><th>Status</th><th>Recurring</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (empty($events))
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>No events found. Click <strong>Add New Event</strong> to get started.
                    </td>
                </tr>
            @else @foreach ($events as $ev)
            <tr>
                <td class="fw-semibold small">{{ $ev['title'] }}</td>
                <td class="small">
                    @php
if ($ev['event_type'] === 'other' && !empty($ev['other_event_type'])) {
                        echo htmlspecialchars($ev['other_event_type']);
                    } else {
                        echo $eventTypes[$ev['event_type']] ?? ucfirst($ev['event_type']);
                    }
@endphp
                </td>
                <td class="small">{{ $ev['hall_name'] ?? '—' }}</td>
                <td class="small">
                    @php
$evTs = !empty($ev['start_datetime']) ? strtotime($ev['start_datetime']) : 0;
                    echo ($evTs && $evTs > 0 && date('Y', $evTs) > 1)
                        ? date('M j, Y', $evTs)
                        : '<span class="text-muted">—</span>';
@endphp
                </td>
                <td class="small">{{ $ev['organizer'] ?: '—' }}</td>
                <td>
                    <span class="status-badge status-{!! $ev['status']==='upcoming'?'approved':($ev['status']==='cancelled'?'rejected':'completed') !!}">
                        {!! ucfirst($ev['status']) !!}                    </span>
                </td>
                <td class="small">
                    {!! $ev['is_recurring']
                        ? '<span class="text-success"><i class="bi bi-arrow-repeat me-1"></i>' . ucfirst($ev['recurrence_pattern']) . '</span>'
                        : '—' !!}                </td>
                <td>
                    <div class="d-flex gap-1">
                        <!-- Edit opens modal popup -->
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                title="Edit Event"
                                onclick="openEditEventModal({{ json_encode($ev) }})"
                                data-bs-toggle="modal"
                                data-bs-target="#editEventModal">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Event"
                                onclick="openDeleteModal('event',{!! $ev['id'] !!},'{{ $ev['title'] }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!-- ── ADD EVENT MODAL ─────────────────────────────────────── -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="addEventLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Event
                    </h5>
                    <p class="text-white-50 small mb-0">Fill in the event details below</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;">
                    @csrf                    {!! view('admin.partials.event-fields', ['ev' => [], 'halls' => $halls, 'eventTypes' => $eventTypes])->render() !!}
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!-- ── EDIT EVENT MODAL ────────────────────────────────────── -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="editEventLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Event — <span id="editEventTitleDisplay"></span>
                    </h5>
                    <p class="text-white-50 small mb-0">Update the event details and save</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;" id="editEventBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-ceac-orange" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-save me-1"></i>Save Changes
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

const CEAC_EVENTS_DATA  = {!! json_encode($events) !!};
const CEAC_HALLS_LIST   = {!! json_encode($halls) !!};
const CEAC_EVENT_TYPES  = {!! json_encode($eventTypes) !!};
const CEAC_CSRF_TOKEN   = '{!! csrf_token() !!}';

function openEditEventModal(ev) {
    document.getElementById('editEventTitleDisplay').textContent = ev.title;

    // Build hall options
    let hallOpts = '';
    CEAC_HALLS_LIST.forEach(h => {
        hallOpts += `<option value="${h.id}" ${ev.hall_id == h.id ? 'selected' : ''}>${escHtml(h.name)}</option>`;
    });

    // Build event type options
    let typeOpts = '<option value="">Select…</option>';
    for (const [val, lbl] of Object.entries(CEAC_EVENT_TYPES)) {
        typeOpts += `<option value="${val}" ${ev.event_type === val ? 'selected' : ''}>${lbl}</option>`;
    }

    
    const isValidDate = (d) => d && !d.startsWith('0000') && !d.startsWith('-') && new Date(d).getFullYear() > 1;
    const startVal = isValidDate(ev.start_datetime) ? ev.start_datetime.replace(' ','T').slice(0,16) : '';
    const endVal   = isValidDate(ev.end_datetime)   ? ev.end_datetime.replace(' ','T').slice(0,16)   : '';
    const otherDisabled = ev.event_type !== 'other' ? 'disabled style="opacity:0.5;"' : '';

    document.getElementById('editEventBody').innerHTML = `
        <input type="hidden" name="_token" value="${CEAC_CSRF_TOKEN}">
        <input type="hidden" name="event_id" value="${ev.id}">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold small">Event Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required maxlength="200" value="${escHtml(ev.title)}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Hall <span class="text-danger">*</span></label>
                <select name="hall_id" class="form-select">${hallOpts}</select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Event Type</label>
                <input type="text" class="form-control" value="Meetings" disabled>
                <input type="hidden" name="event_type" value="meetings">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Start Date &amp; Time <span class="text-muted fw-normal">(optional)</span></label>
                <input type="datetime-local" name="start_datetime" class="form-control" value="${startVal}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">End Date &amp; Time <span class="text-muted fw-normal">(optional)</span></label>
                <input type="datetime-local" name="end_datetime" class="form-control" value="${endVal}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Organizer</label>
                <input type="text" name="organizer" class="form-control" maxlength="150" value="${escHtml(ev.organizer||'')}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Max Attendees</label>
                <input type="number" name="max_attendees" class="form-control" min="1" max="10000" value="${ev.max_attendees||50}">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">Description</label>
                <textarea name="description" class="form-control" rows="4">${escHtml(ev.description||'')}</textarea>
            </div>
        </div>`;
}
    
function toggleOtherField(selectEl, inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const wrap = document.getElementById(inputId + 'Wrap') || input.closest('.col-12');
    if (selectEl.value === 'other') {
        if (wrap) wrap.style.display = '';
        input.disabled = false;
        input.focus();
    } else {
        if (wrap) wrap.style.display = 'none';
        input.disabled = true;
        input.value    = '';
    }
}

function escHtml(str) {
    return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

// Auto-open edit modal if ?edit= in URL
@if ($autoEditId)
document.addEventListener('DOMContentLoaded', () => {
    const ev = CEAC_EVENTS_DATA.find(e => e.id == {!! $autoEditId !!});
    if (ev) {
        openEditEventModal(ev);
        new bootstrap.Modal(document.getElementById('editEventModal')).show();
    }
});
@endif

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
        event:   { soft: `events?soft_delete=${_deleteId}&csrf_token={{ csrf_token() }}`,   hard: `events?delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
        message: { soft: `messages?delete=${_deleteId}&csrf_token={{ csrf_token() }}`,      hard: `messages?hard_delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
        hall:    { soft: `halls?soft_delete=${_deleteId}&csrf_token={{ csrf_token() }}`,    hard: `halls?delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
    };
    window.location.href = urls[_deleteType][mode];
}
</script>
@endsection
