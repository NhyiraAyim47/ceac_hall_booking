@extends('layouts.admin')

@section('content')
@if (session()->has('reply_result'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    @if (session('reply_result') === 'sent')
    showToast('Reply sent successfully!', 'success');
    @else
    showToast('Reply failed: {!! addslashes(session('reply_error', 'Unknown error — check server error log')) !!}', 'error');
    @endif
});
</script>
@endif

@if ($viewMsg)
<!-- ── VIEW SINGLE MESSAGE ─────────────────────────────── -->
<div class="mb-3">
    <a href="messages" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Messages
    </a>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-bold mb-0">{{ $viewMsg['subject'] }}</h5>
                @if ($viewMsg['replied'])
                <span class="badge bg-success"><i class="bi bi-check me-1"></i>Replied</span>
                @endif
            </div>
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex flex-wrap gap-3">
                    <span class="small text-muted">
                        <i class="bi bi-person me-1"></i>
                        <strong>{{ $viewMsg['full_name'] }}</strong>
                    </span>
                    <span class="small text-muted">
                        <i class="bi bi-envelope me-1"></i>{{ $viewMsg['email'] }}                    </span>
                    @if ($viewMsg['phone'])
                    <span class="small text-muted">
                        <i class="bi bi-telephone me-1"></i>{{ $viewMsg['phone'] }}                    </span>
                    @endif
                    <span class="small text-muted">
                        <i class="bi bi-clock me-1"></i>{!! date('D, M j, Y g:i A', strtotime($viewMsg['created_at'])) !!}                    </span>
                </div>
            </div>
            <div class="message-body" style="font-size:0.95rem;line-height:1.8;white-space:pre-wrap;">{{ $viewMsg['message'] }}</div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-3 border shadow-sm p-4">
            <h6 class="fw-bold mb-3">Actions</h6>
            <div class="d-grid gap-2">
                <button class="btn btn-ceac-primary"
                        data-reply-email="{{ $viewMsg['email'] }}"
                        data-reply-name="{{ $viewMsg['full_name'] }}"
                        data-reply-subject="{{ 'Re: ' . $viewMsg['subject'] }}"
                        data-reply-body="{{ "Dear " . $viewMsg['full_name'] . ",\n\n\n\n— From Admin, CEAC Hall Booking Team —\n\n---\nOriginal message from " . $viewMsg['full_name'] . ":\n" . $viewMsg['message'] }}"
                        onclick="openReplyModal(this)">
                    <i class="bi bi-reply me-1"></i>Reply
                </button>
                @if (!$viewMsg['replied'])
                <a href="messages?view={!! $viewMsg['id'] !!}&mark_replied=1"
                   class="btn btn-outline-success">
                    <i class="bi bi-check me-1"></i>Mark as Replied
                </a>
                @endif
                <button class="btn btn-outline-danger"
                        onclick="openDeleteModal('message',{!! $viewMsg['id'] !!},'{{ $viewMsg['subject'] }}')">
                    <i class="bi bi-trash me-1"></i>Delete Message
                </button>
            </div>

            <div class="mt-3 p-3 rounded-2 bg-light border">
                <p class="small text-muted mb-1">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Tip:</strong> Use <em>Reply via Gmail</em> to send a message through our interface or open Gmail directly in the browser.
                </p>
            </div>
        </div>
    </div>
</div>

@else
<!-- ── TAB NAVIGATION ──────────────────────────────────────── -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {!! $activeTab === 'inbox' ? 'active fw-bold' : '' !!}"
           href="messages?tab=inbox">
            <i class="bi bi-inbox me-1"></i>Inbox
            <span class="badge bg-warning text-dark ms-1">
                {{ $unreadCount }}            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {!! $activeTab === 'sent' ? 'active fw-bold' : '' !!}"
           href="messages?tab=sent">
            <i class="bi bi-send me-1"></i>Sent
            <span class="badge bg-secondary ms-1">
                {{ $sentCount }}            </span>
        </a>
    </li>
</ul>

@if ($activeTab === 'sent')
<!-- ── SENT MESSAGES TAB ──────────────────────────────────── -->
<div class="bg-white rounded-3 border shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 admin-table">
            <thead>
                <tr>
                    <th>To</th><th>Subject</th><th>Sent At</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (empty($sentMessages))
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-send fs-2 d-block mb-2"></i>No sent messages yet.
                    </td>
                </tr>
            @else @foreach ($sentMessages as $sm)
            <tr>
                <td>
                    <div class="small fw-semibold">{{ $sm['sent_to_name'] ?: $sm['sent_to_email'] }}</div>
                    <div class="text-muted small">{{ $sm['sent_to_email'] }}</div>
                </td>
                <td class="small">{{ $sm['subject'] }}</td>
                <td class="small text-muted">{!! date('M j, Y g:i A', strtotime($sm['sent_at'])) !!}</td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                title="View Message"
                                onclick="viewSentMessage({{ json_encode([
                                    'name'    => $sm['sent_to_name'] ?: $sm['sent_to_email'],
                                    'email'   => $sm['sent_to_email'],
                                    'subject' => $sm['subject'],
                                    'body'    => $sm['body'],
                                    'sent_at' => date('D, M j, Y g:i A', strtotime($sm['sent_at'])),
                                ]) }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2"
                                title="Delete"
                                onclick="openSentDeleteModal({!! $sm['id'] !!},'{{ $sm['subject'] }}')">
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

@else
<!-- ── MESSAGES LIST (INBOX TAB) ──────────────────────────── -->
<div class="bg-white rounded-3 border shadow-sm p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search name, email, subject…"
                       value="{{ $search }}">
                <button type="submit" class="btn btn-sm btn-ceac-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <a href="messages"
               class="btn btn-sm {!! !$filter ? 'btn-ceac-primary' : 'btn-outline-secondary' !!}">All</a>
            <a href="messages?filter=unread"
               class="btn btn-sm {!! $filter==='unread' ? 'btn-ceac-primary' : 'btn-outline-warning' !!}">
                Unread ({{ $unreadCount }})
            </a>
            <a href="messages?filter=replied"
               class="btn btn-sm {!! $filter==='replied' ? 'btn-ceac-primary' : 'btn-outline-success' !!}">Replied</a>
        </div>
        <div class="col-md-4 text-end d-flex justify-content-end gap-2 flex-wrap">
            <button class="btn btn-sm btn-ceac-primary"
                    onclick="openSendMessageModal()"
                    data-bs-toggle="modal"
                    data-bs-target="#sendMessageModal">
                <i class="bi bi-send me-1"></i>Send Message
            </button>
            <a href="messages?mark_all_read=1"
               class="btn btn-sm btn-outline-secondary"
               data-confirm="Mark all messages as read?">
                <i class="bi bi-envelope-open me-1"></i>Mark All Read
            </a>
        </div>
    </div>
</div>

<p class="text-muted small mb-3">
    Showing <strong>{!! count($messages) !!}</strong> of <strong>{!! $total !!}</strong> messages
    <span class="text-muted">(localhost messages excluded)</span>
</p>

<div class="bg-white rounded-3 border shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 admin-table">
            <thead>
                <tr>
                    <th>Status</th><th>From</th><th>Subject</th>
                    <th>Received</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (empty($messages))
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-envelope-x fs-2 d-block mb-2"></i>No messages found.
                    </td>
                </tr>
            @else @foreach ($messages as $m)
            <tr class="{!! !$m['is_read'] ? 'fw-semibold table-warning' : '' !!}">
                <td>
                    @if (!$m['is_read'])
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>New
                    </span>
                    @elseif ($m['replied'])
                    <span class="badge bg-success"><i class="bi bi-check me-1"></i>Replied</span>
                    @else
                    <span class="badge bg-light text-muted border">Read</span>
                    @endif
                </td>
                <td>
                    <div class="small">{{ $m['full_name'] }}</div>
                    <div class="text-muted small">{{ $m['email'] }}</div>
                </td>
                <td class="small">{{ $m['subject'] }}</td>
                <td class="small text-muted">{!! date('M j, Y g:i A', strtotime($m['created_at'])) !!}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="messages?view={!! $m['id'] !!}"
                        class="btn btn-sm btn-outline-secondary py-0 px-2" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-primary py-0 px-2" title="Reply"
                                data-reply-email="{{ $m['email'] }}"
                                data-reply-name="{{ $m['full_name'] }}"
                                data-reply-subject="{{ 'Re: ' . $m['subject'] }}"
                                data-reply-body="{{ "Dear {$m['full_name']},\n\n\n\n— From Admin, CEAC Hall Booking Team —\n\n---\nOriginal message from {$m['full_name']}:\n{$m['message']}" }}"
                                onclick="openReplyModal(this)">
                            <i class="bi bi-reply"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete"
                                onclick="openDeleteModal('message',{!! $m['id'] !!},'{{ $m['subject'] }}')">
                            <i class="bi bi-trash"></i>
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
        <nav><ul class="pagination pagination-sm justify-content-center mb-0">
            @for ($i = 1; $i <= $totalPages; $i++)
            <li class="page-item {!! $i===$page?'active':'' !!}">
                <a class="page-link"
                   href="?page={!! $i !!}&filter={!! urlencode($filter) !!}&search={!! urlencode($search) !!}">
                    {!! $i !!}                </a>
            </li>
            @endfor
        </ul></nav>
    </div>
    @endif
</div>
@php
endif; // sent vs inbox
@endphp
@php
endif; // viewMsg vs list
@endphp

<!-- View Sent Message Modal -->
<div class="modal fade" id="viewSentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-send me-2"></i>Sent Message
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted small" style="width:20%;">To</th>
                        <td class="small fw-semibold" id="smViewName"></td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Email</th>
                        <td class="small" id="smViewEmail"></td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Subject</th>
                        <td class="small fw-semibold" id="smViewSubject"></td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Sent</th>
                        <td class="small text-muted" id="smViewSentAt"></td>
                    </tr>
                </table>
                <div class="border-top mt-3 pt-3">
                    <p class="fw-semibold small text-muted text-uppercase mb-2">Message</p>
                    <div id="smViewBody"
                         class="bg-light rounded p-3 small"
                         style="white-space:pre-wrap;line-height:1.8;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Sent Delete Modal -->
<div class="modal fade" id="sentDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#7F1D1D,#991B1B);border-bottom:2px solid #EF4444;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-trash me-2"></i>Delete Sent Message
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">How would you like to delete <strong id="sentDeleteSubject"></strong>?</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#F59E0B!important;background:#FFFBEB;cursor:pointer;"
                             onclick="confirmSentDelete('soft')">
                            <i class="bi bi-eye-slash fs-3 text-warning d-block mb-2"></i>
                            <strong class="d-block small">Delete from View</strong>
                            <span class="text-muted" style="font-size:0.75rem;">Hidden from list, kept in database</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#EF4444!important;background:#FEF2F2;cursor:pointer;"
                             onclick="confirmSentDelete('hard')">
                            <i class="bi bi-trash fs-3 text-danger d-block mb-2"></i>
                            <strong class="d-block small">Delete Permanently</strong>
                            <span class="text-muted" style="font-size:0.75rem;">Removed completely. Cannot be undone</span>
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
let _sentDeleteId = 0;

function viewSentMessage(msg) {
    document.getElementById('smViewName').textContent    = msg.name;
    document.getElementById('smViewEmail').textContent   = msg.email;
    document.getElementById('smViewSubject').textContent = msg.subject;
    document.getElementById('smViewSentAt').textContent  = msg.sent_at;
    document.getElementById('smViewBody').textContent    = msg.body;
    new bootstrap.Modal(document.getElementById('viewSentModal')).show();
}

function openSentDeleteModal(id, subject) {
    _sentDeleteId = id;
    document.getElementById('sentDeleteSubject').textContent = subject;
    new bootstrap.Modal(document.getElementById('sentDeleteModal')).show();
}

function confirmSentDelete(mode) {
    if (mode === 'soft') {
        window.location.href = 'messages?delete_sent=' + _sentDeleteId + '&tab=sent&csrf_token={{ csrf_token() }}';
    } else {
        window.location.href = 'messages?hard_delete_sent=' + _sentDeleteId + '&tab=sent&csrf_token={{ csrf_token() }}';
    }
}
</script>

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
                    <p class="text-white-50 small mb-0">Message will be sent from ceachallbooking@gmail.com</p>
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

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-send me-2"></i>Send New Message
                    </h5>
                    <p class="text-white-50 small mb-0">
                        Message will be sent from ceachallbooking@gmail.com
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;">
                    @csrf                    <input type="hidden" name="send_message_submit" value="1">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Recipient Name <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="sm_to_name" class="form-control"
                                   placeholder="e.g. John Mensah" maxlength="150">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Recipient Email <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="sm_to" class="form-control"
                                   placeholder="recipient@email.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Subject <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" name="sm_subject" class="form-control"
                                   placeholder="Message subject" required maxlength="200">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Message <span class="text-danger">*</span>
                        </label>
                        <textarea name="sm_body" class="form-control" rows="10"
                                  placeholder="Type your message here…" required
                                  style="font-size:0.9rem;line-height:1.7;"></textarea>
                    </div>
                   <div class="p-3 bg-light rounded border">
                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                The recipient will receive a branded CEAC Hall Booking email.
                            </small>
                            <a id="sendMsgGmailLink" href="https://mail.google.com/mail/?view=cm"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-secondary flex-shrink-0">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Send directly via Gmail instead
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-send me-1"></i>Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send message result toast -->
@if (session()->has('send_msg_result'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    @if (session('send_msg_result') === 'sent')
    showToast('Message sent successfully!', 'success');
    @else
    showToast('Failed to send: {!! addslashes(session('send_msg_error_detail', 'Unknown error')) !!}', 'error');
    @endif
});
    
// Update Gmail link as admin types in Send Message form
document.addEventListener('input', function(e) {
    const modal = document.getElementById('sendMessageModal');
    if (!modal || !modal.contains(e.target)) return;
    const toEl  = modal.querySelector('[name="sm_to"]');
    const subEl = modal.querySelector('[name="sm_subject"]');
    const bdEl  = modal.querySelector('[name="sm_body"]');
    const link  = document.getElementById('sendMsgGmailLink');
    if (!link) return;
    const to  = toEl  ? toEl.value  : '';
    const sub = subEl ? subEl.value : '';
    const bd  = bdEl  ? bdEl.value  : '';
    link.href = 'https://mail.google.com/mail/?view=cm'
        + (to  ? '&to='   + encodeURIComponent(to)  : '')
        + (sub ? '&su='   + encodeURIComponent(sub) : '')
        + (bd  ? '&body=' + encodeURIComponent(bd)  : '');
});
    
// Auto-open reply modal from process_contact.php email link
(function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('auto_reply') !== '1') return;
    const email   = params.get('reply_to')      || '';
    const name    = params.get('reply_name')     || '';
    const subject = params.get('reply_subject')  || '';
    const body    = params.get('reply_body')     || '';
    if (!email) return;
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const btn = document.createElement('button');
            btn.dataset.replyEmail   = email;
            btn.dataset.replyName    = name;
            btn.dataset.replySubject = subject;
            btn.dataset.replyBody    = body;
            if (typeof openReplyModal === 'function') openReplyModal(btn);
        }, 500);
    });
})();
</script>
@endif

<script>
function openSendMessageModal() {
    const form = document.querySelector('#sendMessageModal form');
    if (form) form.reset();
    // Reset Gmail link to blank compose
    const gmailLink = document.getElementById('sendMsgGmailLink');
    if (gmailLink) gmailLink.href = 'https://mail.google.com/mail/?view=cm';
}
</script>

<script>
let _deleteType = '', _deleteId = 0, _deleteMode = '';

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
        event:   { soft: `events?soft_delete=${_deleteId}&csrf_token={{ csrf_token() }}`,   hard: `events?delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
        message: { soft: `messages?delete=${_deleteId}&csrf_token={{ csrf_token() }}`,      hard: `messages?hard_delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
        hall:    { soft: `halls?soft_delete=${_deleteId}&csrf_token={{ csrf_token() }}`,    hard: `halls?delete=${_deleteId}&csrf_token={{ csrf_token() }}` },
    };
    window.location.href = urls[_deleteType][mode];
}
</script>
@endsection
