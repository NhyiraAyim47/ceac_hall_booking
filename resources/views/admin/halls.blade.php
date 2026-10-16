@extends('layouts.admin')

@section('content')
@php
// Pick up session messages from pre-header redirects
if (!empty(session('hall_msg'))) {
    $msg     = session('hall_msg');
    $msgType = session('hall_msg_type', 'success');
    }
@endphp
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
        <h6 class="fw-bold mb-1">Hall Management</h6>
        <p class="text-muted small mb-0">{!! count($halls) !!} hall{!! count($halls)!==1?'s':'' !!} registered</p>
    </div>
    <button class="btn btn-ceac-primary" data-bs-toggle="modal" data-bs-target="#addHallModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Hall
    </button>
</div>

<!-- Hall Cards (always full width now — edit is a modal) -->
<div class="row g-4">
    @if (empty($halls))
    <div class="col-12">
        <div class="bg-white rounded-3 border shadow-sm p-5 text-center">
            <i class="bi bi-building-x fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No halls registered yet</h5>
            <p class="text-muted small">Click <strong>Add New Hall</strong> to get started.</p>
            <button class="btn btn-ceac-primary mt-2" data-bs-toggle="modal" data-bs-target="#addHallModal">
                <i class="bi bi-plus-circle me-2"></i>Add First Hall
            </button>
        </div>
    </div>
    @else @foreach ($halls as $h)
    <div class="col-12">
        <div class="bg-white rounded-3 border shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-3 position-relative">
                    <img src="{!! hallImage($h) !!}" alt="{{ $h['name'] }}"
                         class="img-fluid h-100 w-100" style="object-fit:cover;min-height:200px;">
                    <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center"
                         style="background:rgba(0,0,0,0.5);">
                        <small class="text-white"><i class="bi bi-image me-1"></i>Hall Photo</small>
                    </div>
                </div>
                <div class="col-md-9 p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $h['name'] }}</h5>
                            <span class="text-muted small">{{ $h['seating_style'] }}</span>
                        </div>
                        <span class="status-badge status-{!! $h['status']==='active'?'approved':($h['status']==='maintenance'?'pending':'rejected') !!}">
                            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;vertical-align:middle;"></i>
                            {!! ucfirst($h['status']) !!}                        </span>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-sm-3">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold text-ceac-orange fs-5">{!! $h['capacity'] !!}</div>
                                <div class="small text-muted">Capacity</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold text-success fs-5">{!! $h['approved_count'] !!}</div>
                                <div class="small text-muted">Approved</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold text-warning fs-5">{!! $h['pending_count'] !!}</div>
                                <div class="small text-muted">Pending</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold fs-5">{!! $h['total_count'] !!}</div>
                                <div class="small text-muted">Total</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if ($h['has_projector'])<span class="amenity-tag"><i class="bi bi-display"></i> Screen</span>@endif
                        @if ($h['has_pa_system'])<span class="amenity-tag"><i class="bi bi-speaker"></i> PA System</span>@endif
                        @if ($h['has_ac'])<span class="amenity-tag"><i class="bi bi-thermometer-snow"></i> AC</span>@endif
                        @if ($h['has_wifi'])<span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>@endif
                        @if ($h['has_whiteboard'])<span class="amenity-tag"><i class="bi bi-easel"></i> Whiteboard</span>@endif
                        @if ($h['has_parking'])<span class="amenity-tag"><i class="bi bi-p-circle"></i> Parking</span>@endif
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Edit button now opens a modal popup -->
                        <button class="btn btn-sm btn-ceac-primary"
                                onclick="openEditModal({{ json_encode($h) }})"
                                data-bs-toggle="modal" data-bs-target="#editHallModal">
                            <i class="bi bi-pencil me-1"></i>Edit Hall
                        </button>
                        <button class="btn btn-sm btn-outline-secondary"
                                onclick="previewHall({{ json_encode([
                                    'name'           => $h['name'],
                                    'capacity'       => $h['capacity'],
                                    'seating_style'  => $h['seating_style'],
                                    'description'    => $h['description'],
                                    'has_projector'  => $h['has_projector'],
                                    'has_pa_system'  => $h['has_pa_system'],
                                    'has_ac'         => $h['has_ac'],
                                    'has_wifi'       => $h['has_wifi'],
                                    'has_whiteboard' => $h['has_whiteboard'],
                                    'has_parking'    => $h['has_parking'],
                                    'image'          => hallImage($h),
                                ]) }})"
                                data-bs-toggle="modal" data-bs-target="#previewModal">
                            <i class="bi bi-eye me-1"></i>Preview
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="openDeleteModal('hall',{!! $h['id'] !!},{!! $h['total_count'] !!},'{{ $h['name'] }}')">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach @endif
</div>



<!-- ════════════════════════════════════════════════════════════ -->
<!-- ── ADD HALL MODAL ──────────────────────────────────────── -->
<!-- ════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="addHallModal" tabindex="-1" aria-labelledby="addHallLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="addHallLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Hall
                    </h5>
                    <p class="text-white-50 small mb-0">Complete all sections to create a fully detailed hall listing</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data"
                  style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;">
                    @csrf                    <input type="hidden" name="form_action" value="add">
                    @include('admin.partials.hall-form-body', ['hall' => [], 'prefix' => 'add'])
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-plus-circle me-1"></i>Add Hall
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- ── EDIT HALL MODAL ─────────────────────────────────────── -->
<!-- ════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="editHallModal" tabindex="-1" aria-labelledby="editHallLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content" style="max-height:92vh;display:flex;flex-direction:column;">
            <div class="modal-header flex-shrink-0"
                 style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));border-bottom:2px solid var(--ceac-orange);">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="editHallLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Hall — <span id="editHallNameTitle"></span>
                    </h5>
                    <p class="text-white-50 small mb-0">Update any section and click Save Changes</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="editHallForm"
                  style="display:flex;flex-direction:column;flex:1;min-height:0;">
                <div class="modal-body flex-grow-1 p-4" style="overflow-y:auto;" id="editHallBody">
                    <!-- Populated dynamically by openEditModal() -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-ceac-orange" role="status"></div>
                        <p class="text-muted mt-2">Loading hall details…</p>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 border-top bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-ceac-primary">
                        <i class="bi bi-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── PREVIEW MODAL ─────────────────────────────────────────── -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="previewHallName">Hall Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="previewHallImg" src="" alt="Hall" class="w-100"
                     style="height:200px;object-fit:cover;" onerror="this.style.display='none'">
                <div class="p-4">
                    <div class="row g-3 mb-3" id="previewStats"></div>
                    <div class="mb-3" id="previewAmenities"></div>
                    <p class="text-muted small mb-0" id="previewDesc"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Hall Modal -->
<div class="modal fade" id="deleteHallModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#7F1D1D,#991B1B);border-bottom:2px solid #EF4444;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-trash me-2"></i>Delete Hall
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteHallId">
                <div id="deleteHallWarning" style="display:none;" class="mb-2"></div>
                <p class="mb-3">How would you like to delete <strong id="deleteHallName"></strong>?</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#F59E0B!important;background:#FFFBEB;cursor:pointer;"
                             onclick="confirmHallDelete('soft')">
                            <i class="bi bi-eye-slash fs-3 text-warning d-block mb-2"></i>
                            <strong class="d-block small">Delete from View</strong>
                            <span class="text-muted" style="font-size:0.75rem;">
                                Hidden from dashboard, record stays in database
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-3 text-center h-100"
                             style="border-color:#EF4444!important;background:#FEF2F2;cursor:pointer;"
                             onclick="confirmHallDelete('hard')">
                            <i class="bi bi-trash fs-3 text-danger d-block mb-2"></i>
                            <strong class="d-block small">Delete Permanently</strong>
                            <span class="text-muted" style="font-size:0.75rem;">
                                Completely removed. Cannot be undone
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

<!-- ── PHP data for JS: encode all halls for edit modal ─────── -->
<script>
const CEAC_HALLS_DATA = {!! json_encode(
    array_map(function($h) {
        return [
            'id'             => $h['id'],
            'name'           => $h['name'],
            'status'         => $h['status'],
            'capacity'       => $h['capacity'],
            'seating_style'  => $h['seating_style'],
            'description'    => $h['description'] ?? '',
            'about_extended' => $h['about_extended'] ?? '',
            'seating_info'   => $h['seating_info'] ?? '',
            'features_json'  => $h['features_json'] ?? '',
            'ideal_for_json' => $h['ideal_for_json'] ?? '',
            'images_json'    => $h['images_json'] ?? '',
            'image'          => $h['image'] ?? '',
            'has_projector'  => $h['has_projector'],
            'has_ac'         => $h['has_ac'],
            'has_pa_system'  => $h['has_pa_system'],
            'has_wifi'       => $h['has_wifi'],
            'has_whiteboard' => $h['has_whiteboard'],
            'has_parking'    => $h['has_parking'],
            'pricing_json'   => $h['pricing_json'] ?? '',
        ];
    }, $halls)
) !!};

const CEAC_BASE_URL = '{{ base_url() }}';

// ── Open Edit Hall Modal ──────────────────────────────────────
function openEditModal(hall) {
    document.getElementById('editHallNameTitle').textContent = hall.name;

    const amenityFields = {
        has_projector: 'Display Screen',
        has_ac: 'Air Conditioning',
        has_pa_system: 'PA System',
        has_wifi: 'WiFi',
        has_whiteboard: 'Whiteboard',
        has_parking: 'Parking'
    };

    const styles = ['Theatre Style','Classroom Style','Boardroom Style','Banquet Style','U-Shape','Open Space','Mixed'];
    const statusOptions = {active:'Active', inactive:'Inactive', maintenance:'Maintenance'};

    // Parse existing images
    let existingImages = [];
    try { existingImages = JSON.parse(hall.images_json || '[]'); } catch(e) {}

    // Parse existing features
    let existingFeatures = [];
    try { existingFeatures = JSON.parse(hall.features_json || '[]'); } catch(e) {}

    // Parse existing ideal for
    let existingIdeal = [];
    try { existingIdeal = JSON.parse(hall.ideal_for_json || '[]'); } catch(e) {}
    if (!existingIdeal.length) {
        existingIdeal = [
            {name:'Cell Meetings',      desc:'Small group Bible study & fellowship'},
            {name:'Fellowship Meetings',desc:'Corporate worship & the Word'},
            {name:'Executive Meetings', desc:'Leadership & ministry strategy sessions'},
            {name:'All Night Services', desc:'Overnight prayer & worship events'},
        ];
    }

    // Build image slots
    let imgSlots = '';
    for (let i = 1; i <= 4; i++) {
        const rawPath = existingImages[i-1] || '';
        const imgUrl  = rawPath ? CEAC_BASE_URL + '/assets/images/' + rawPath : '';
        imgSlots += `
        <div class="col-6">
            <div class="border rounded-3 bg-light position-relative overflow-hidden"
                 style="height:110px;cursor:pointer;"
                 onclick="document.getElementById('editImg${i}').click()">
                <img id="editImg${i}Preview" src="${imgUrl}" alt=""
                     class="w-100 h-100 ${imgUrl?'':'d-none'}" style="object-fit:cover;">
                <div id="editImg${i}Placeholder"
                     class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-1 text-muted ${imgUrl?'d-none':''}">
                    <i class="bi bi-camera-fill fs-4"></i>
                    <span style="font-size:0.72rem;">Photo ${i}${i===1?' (Main)':' (Optional)'}</span>
                </div>
                <button type="button" onclick="removeImgSlot(event,'edit',${i})"
                        class="btn btn-sm btn-danger position-absolute ${imgUrl?'':'d-none'}"
                        id="editImg${i}Remove"
                        style="top:4px;right:4px;padding:2px 7px;font-size:0.7rem;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <input type="file" name="hall_image_${i}" id="editImg${i}"
                   class="d-none" accept="image/jpeg,image/png,image/webp"
                   onchange="previewImgSlot(this,'edit',${i})">
        </div>`;
    }

    // Build status options
    let statusHtml = '';
    for (const [val,lbl] of Object.entries(statusOptions)) {
        statusHtml += `<option value="${val}" ${hall.status===val?'selected':''}>${lbl}</option>`;
    }

    // Build seating style options
    let styleHtml = '';
    for (const s of styles) {
        styleHtml += `<option value="${s}" ${hall.seating_style===s?'selected':''}>${s}</option>`;
    }

    // Build amenity checkboxes
    let amenityHtml = '';
    for (const [field, label] of Object.entries(amenityFields)) {
        amenityHtml += `
        <div class="col-6 col-md-4">
            <div class="form-check border rounded p-2 bg-light">
                <input class="form-check-input" type="checkbox" name="${field}" value="1"
                       id="edit_${field}" ${hall[field]?'checked':''}>
                <label class="form-check-label small" for="edit_${field}">${label}</label>
            </div>
        </div>`;
    }

    // Build ideal for rows
    let idealHtml = '';
    for (const item of existingIdeal) {
        idealHtml += `
        <div class="row g-2 mb-2">
            <div class="col-5">
                <input type="text" name="ideal_name[]" class="form-control form-control-sm"
                       placeholder="Event type" value="${escHtml(item.name||'')}">
            </div>
            <div class="col-7">
                <input type="text" name="ideal_desc[]" class="form-control form-control-sm"
                       placeholder="Short description" value="${escHtml(item.desc||'')}">
            </div>
        </div>`;
    }

    // Final modal body HTML
    document.getElementById('editHallBody').innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('#addHallModal [name=_token]').value}">
        <input type="hidden" name="form_action" value="edit">
        <input type="hidden" name="hall_id" value="${hall.id}">

        <!-- Section 1: Photos -->
        <div class="mb-3"><span class="badge text-bg-warning text-dark fw-bold"><i class="bi bi-camera me-1"></i>Section 1 — Hall Photos</span>
        <small class="text-muted ms-2">Up to 4 images · JPG/PNG/WebP · Max 5MB each</small></div>
        <div class="row g-2 mb-4">${imgSlots}</div>

        <!-- Section 2: Basic Info -->
        <div class="mb-3"><span class="badge text-bg-primary fw-bold"><i class="bi bi-info-circle me-1"></i>Section 2 — Basic Information</span></div>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Hall Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" name="name" class="form-control" required maxlength="150" value="${escHtml(hall.name)}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">${statusHtml}</select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Capacity <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                    <input type="number" name="capacity" class="form-control" min="1" max="10000" required value="${hall.capacity}">
                    <span class="input-group-text">people</span>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Seating Style</label>
                <select name="seating_style" class="form-select">${styleHtml}</select>
            </div>
        </div>

        <!-- Section 3: Amenities -->
        <div class="mb-3"><span class="badge text-bg-success fw-bold"><i class="bi bi-tools me-1"></i>Section 3 — Amenities</span></div>
        <div class="row g-2 mb-4">${amenityHtml}</div>

        <!-- Section 4: About -->
        <div class="mb-3"><span class="badge text-bg-info text-dark fw-bold"><i class="bi bi-file-text me-1"></i>Section 4 — About the Hall</span></div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Main Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Describe the hall…">${escHtml(hall.description)}</textarea>
            <div class="form-text">Displays in the 'About This Hall' section.</div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small">Extended Description <span class="text-muted fw-normal">(optional)</span></label>
            <textarea name="about_extended" class="form-control" rows="3" placeholder="Additional paragraph…">${escHtml(hall.about_extended)}</textarea>
        </div>

        <!-- Section 5: Pricing -->
        <div class="section-label mb-3">
            <span class="badge fw-bold" style="background:var(--ceac-orange);">
                <i class="bi bi-cash-coin me-1"></i>Section 5 — Pricing &amp; Duration
            </span>
            <small class="text-muted ms-2">Leave blank to hide pricing from the booking form.</small>
        </div>

        ${buildPricingFormHtml(hall.pricing_json || '{}')}
    `;
}

// ── Build pricing form HTML for edit modal ────────────────────
function buildPricingFormHtml(pricingRaw) {
    let p = {};
    try { p = JSON.parse(pricingRaw); } catch(e) {}

    const cur = p.currency || 'GHS';
    const curOpts = ['GHS','USD','EUR'].map(c =>
        `<option value="${c}" ${cur===c?'selected':''}>${c==='GHS'?'GHS (Ghana Cedis ₵)':c==='USD'?'USD ($)':'EUR (€)'}</option>`
    ).join('');

    const v = (key, def='') => escHtml(String(p[key] ?? def));

    const evTypes = [
    {key:'meetings', label:'Meetings', icon:'people-fill', color:'#2563EB', bg:'#EFF6FF', border:'#BFDBFE'},
	];

    let evSections = '';
    for (const ev of evTypes) {
            evSections += `
            <div class="p-3 rounded-3 border mb-3" style="background:${ev.bg};border-color:${ev.border}!important;">
                <p class="fw-bold small text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing:1px;color:${ev.color};">
                    <i class="bi bi-${ev.icon}"></i>${ev.label}
                </p>
                <div class="row g-2">
                    <div class="col-12 mb-1"><span class="badge bg-secondary fw-normal" style="font-size:0.72rem;">Half Time</span></div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Half-Time Duration</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="number" name="pricing_${ev.key}_half_hours" class="form-control" placeholder="e.g. 2" min="1" value="${v(ev.key+'_half_hours','2')}">
                            <span class="input-group-text">hrs</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Half-Time Price (₵)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₵</span>
                            <input type="number" name="pricing_${ev.key}_half_price" class="form-control" placeholder="e.g. 500" min="0" value="${v(ev.key+'_half_price')}">
                        </div>
                    </div>
                    <div class="col-12 mt-2 mb-1"><span class="badge bg-secondary fw-normal" style="font-size:0.72rem;">Full Time</span></div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Full-Time Duration</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="number" name="pricing_${ev.key}_full_hours" class="form-control" placeholder="e.g. 4" min="1" value="${v(ev.key+'_full_hours','4')}">
                            <span class="input-group-text">hrs</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Full-Time Price (₵)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₵</span>
                            <input type="number" name="pricing_${ev.key}_full_price" class="form-control" placeholder="e.g. 1000" min="0" value="${v(ev.key+'_full_price')}">
                        </div>
                    </div>
                </div>
            </div>`;
    }

    return `
    <div class="mb-3">
        <label class="form-label fw-semibold small">Currency</label>
        <select name="pricing_currency" class="form-select" style="max-width:200px;">${curOpts}</select>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold small"><i class="bi bi-exclamation-circle me-1 text-warning"></i>Penalty/Extra Hour (₵)</label>
        <div class="input-group input-group-sm" style="max-width:200px;">
            <span class="input-group-text">₵</span>
            <input type="number" name="pricing_penalty" class="form-control" placeholder="e.g. 500" min="0" value="${v('penalty_per_hour')}">
            <span class="input-group-text">/hr</span>
        </div>
    </div>
    ${evSections}
    <div class="p-3 rounded-3 border mb-3" style="background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border-color:#BBF7D0!important;">
        <p class="fw-bold small text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing:1px;color:#14532D;">
            <i class="bi bi-tools"></i>Facility Prices — shared across all event types
        </p>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small fw-semibold"><i class="bi bi-display me-1 text-ceac-orange"></i>Display Screen (₵)</label>
                <div class="input-group input-group-sm"><span class="input-group-text">₵</span>
                    <input type="number" name="pricing_projector" class="form-control" placeholder="e.g. 200" min="0" value="${v('facility_projector')}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold"><i class="bi bi-speaker me-1 text-ceac-orange"></i>PA System (₵)</label>
                <div class="input-group input-group-sm"><span class="input-group-text">₵</span>
                    <input type="number" name="pricing_pa_system" class="form-control" placeholder="e.g. 200" min="0" value="${v('facility_pa_system')}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold"><i class="bi bi-thermometer-snow me-1 text-ceac-orange"></i>Air Conditioning (₵)</label>
                <div class="input-group input-group-sm"><span class="input-group-text">₵</span>
                    <input type="number" name="pricing_ac" class="form-control" placeholder="e.g. 200" min="0" value="${v('facility_ac')}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold"><i class="bi bi-easel me-1 text-ceac-orange"></i>Whiteboard (₵)</label>
                <div class="input-group input-group-sm"><span class="input-group-text">₵</span>
                    <input type="number" name="pricing_whiteboard" class="form-control" placeholder="e.g. 200" min="0" value="${v('facility_whiteboard')}">
                </div>
            </div>
        </div>
    </div>`;
}

// ── HTML escape helper ────────────────────────────────────────
function escHtml(str) {
    return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

// ── Image slot preview ────────────────────────────────────────
function previewImgSlot(input, prefix, slot) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview     = document.getElementById(prefix + 'Img' + slot + 'Preview');
        const placeholder = document.getElementById(prefix + 'Img' + slot + 'Placeholder');
        const removeBtn   = document.getElementById(prefix + 'Img' + slot + 'Remove');
        if (preview)     { preview.src = e.target.result; preview.classList.remove('d-none'); }
        if (placeholder)  placeholder.classList.add('d-none');
        if (removeBtn)    removeBtn.classList.remove('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}

function removeImgSlot(e, prefix, slot) {
    e.stopPropagation();
    const preview     = document.getElementById(prefix + 'Img' + slot + 'Preview');
    const placeholder = document.getElementById(prefix + 'Img' + slot + 'Placeholder');
    const removeBtn   = document.getElementById(prefix + 'Img' + slot + 'Remove');
    const input       = document.getElementById(prefix + 'Img' + slot);
    if (preview)     { preview.src = ''; preview.classList.add('d-none'); }
    if (placeholder)   placeholder.classList.remove('d-none');
    if (removeBtn)     removeBtn.classList.add('d-none');
    if (input)         input.value = '';

    // Tell the server this slot was explicitly cleared
    const formBody = document.getElementById('editHallBody') || document.querySelector('#addHallModal .modal-body');
    if (formBody) {
        let hidden = document.getElementById('remove_img_slot_' + slot);
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id   = 'remove_img_slot_' + slot;
            hidden.name = 'remove_img_slot[]';
            formBody.appendChild(hidden);
        }
        hidden.value = slot;
    }
}
// ── Add Ideal For row ─────────────────────────────────────────
function addIdealRow(listId) {
    const list = document.getElementById(listId);
    if (!list) return;
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2';
    row.innerHTML = `
        <div class="col-5">
            <input type="text" name="ideal_name[]" class="form-control form-control-sm" placeholder="Event type">
        </div>
        <div class="col-7">
            <input type="text" name="ideal_desc[]" class="form-control form-control-sm" placeholder="Short description">
        </div>`;
    list.appendChild(row);
}

// ── Hall preview modal ────────────────────────────────────────
function previewHall(hall) {
    document.getElementById('previewHallName').textContent = hall.name;
    document.getElementById('previewDesc').textContent     = hall.description || 'No description provided.';
    const img = document.getElementById('previewHallImg');
    if (hall.image) { img.src = hall.image; img.style.display = 'block'; }
    else img.style.display = 'none';

    document.getElementById('previewStats').innerHTML = `
        <div class="col-6"><div class="text-center p-3 bg-light rounded">
            <div class="fw-bold fs-4 text-ceac-orange">${hall.capacity}</div>
            <div class="small text-muted">Capacity</div>
        </div></div>
        <div class="col-6"><div class="text-center p-3 bg-light rounded">
            <div class="fw-bold">${hall.seating_style}</div>
            <div class="small text-muted">Seating Style</div>
        </div></div>`;

    const amenityMap = {
        has_projector:'<i class="bi bi-display"></i> Screen',
        has_pa_system:'<i class="bi bi-speaker"></i> PA System',
        has_ac:'<i class="bi bi-thermometer-snow"></i> AC',
        has_wifi:'<i class="bi bi-wifi"></i> WiFi',
        has_whiteboard:'<i class="bi bi-easel"></i> Whiteboard',
        has_parking:'<i class="bi bi-p-circle"></i> Parking'
    };
    let badges = '';
    for (const [key,label] of Object.entries(amenityMap)) {
        if (hall[key]==1) badges += `<span class="amenity-tag me-1 mb-1">${label}</span>`;
    }
    document.getElementById('previewAmenities').innerHTML =
        badges || '<span class="text-muted small">No amenities listed.</span>';
}

// Auto-open edit modal if ?edit= param present
@if ($editHall)
document.addEventListener('DOMContentLoaded', () => {
    const hall = CEAC_HALLS_DATA.find(h => h.id === {!! (int)$editHall['id'] !!});
    if (hall) {
        openEditModal(hall);
        const modal = new bootstrap.Modal(document.getElementById('editHallModal'));
        modal.show();
    }
});
@endif

// ── Delete modal ──────────────────────────────────────────────
function openDeleteModal(type, id, bookingCount, name) {
    document.getElementById('deleteHallId').value = id;
    document.getElementById('deleteHallName').textContent = name;

    const warningEl = document.getElementById('deleteHallWarning');
    if (bookingCount > 0) {
        warningEl.innerHTML = `<div class="alert alert-warning py-2 small">
            <i class="bi bi-exclamation-triangle me-1"></i>
            This hall has <strong>${bookingCount} booking(s)</strong> linked to it.
            Deleting permanently will also affect those booking records.
            <strong>Delete from View</strong> is the safer option.
        </div>`;
        warningEl.style.display = 'block';
    } else {
        warningEl.style.display = 'none';
    }
    new bootstrap.Modal(document.getElementById('deleteHallModal')).show();
}

function confirmHallDelete(mode) {
    const id = document.getElementById('deleteHallId').value;
    if (mode === 'soft') {
        window.location.href = 'halls?soft_delete=' + id + '&csrf_token={{ csrf_token() }}';
    } else {
        if (confirm('This will permanently delete the hall and cannot be undone. Are you sure?')) {
            window.location.href = 'halls?delete=' + id + '&csrf_token={{ csrf_token() }}';
        }
    }
}
</script>
@endsection
