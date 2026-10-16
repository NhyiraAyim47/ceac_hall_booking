@php
    $hall   = $hall ?? [];
    $prefix = $prefix ?? 'add';
@endphp
@php
$amenityFields = [
        'has_projector'  => 'Display Screen',
        'has_ac'         => 'Air Conditioning',
        'has_pa_system'  => 'PA System',
        'has_wifi'       => 'WiFi',
        'has_whiteboard' => 'Whiteboard',
        'has_parking'    => 'Parking',
    ];
    $existingFeatures = !empty($hall['features_json']) ? (json_decode($hall['features_json'],true) ?: []) : [];
    $existingIdeal    = !empty($hall['ideal_for_json']) ? (json_decode($hall['ideal_for_json'],true) ?: []) : [];
    $idealDefaults    = [
        ['name'=>'Cell Meetings',      'desc'=>'Small group Bible study & fellowship'],
        ['name'=>'Fellowship Meetings','desc'=>'Corporate worship & the Word'],
        ['name'=>'Executive Meetings', 'desc'=>'Leadership & ministry strategy sessions'],
        ['name'=>'All Night Services', 'desc'=>'Overnight prayer & worship events'],
    ];
    $idealList = !empty($existingIdeal) ? $existingIdeal : $idealDefaults;
    $styles = ['Theatre Style','Classroom Style','Boardroom Style','Banquet Style','U-Shape','Open Space','Mixed'];
@endphp

    <!-- ── SECTION 1: Hall Photos ── -->
    <div class="section-label mb-3">
        <span class="badge text-bg-warning text-dark fw-bold">
            <i class="bi bi-camera me-1"></i>Section 1 — Hall Photos
        </span>
        <small class="text-muted ms-2">Up to 4 images · JPG/PNG/WebP · Max 5MB each</small>
    </div>
    <div class="row g-2 mb-4">
        @php
for ($i = 1; $i <= 4; $i++):
            $existingImgs = !empty($hall['images_json']) ? (json_decode($hall['images_json'],true) ?: []) : [];
            $existingUrl  = '';
            if (!empty($existingImgs[$i-1])) {
                $p = public_path('assets/images/') . $existingImgs[$i-1];
                if (file_exists($p)) $existingUrl = base_url() . '/assets/images/' . $existingImgs[$i-1];
            }
@endphp
        <div class="col-6">
            <div class="border rounded-3 bg-light position-relative overflow-hidden"
                 style="height:110px;cursor:pointer;"
                 onclick="document.getElementById('{!! $prefix !!}Img{!! $i !!}').click()">
                <img id="{!! $prefix !!}Img{!! $i !!}Preview"
                     src="{!! $existingUrl !!}" alt=""
                     class="w-100 h-100 {!! $existingUrl ? '' : 'd-none' !!}"
                     style="object-fit:cover;">
                <div id="{!! $prefix !!}Img{!! $i !!}Placeholder"
                     class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-1 text-muted {!! $existingUrl ? 'd-none' : '' !!}">
                    <i class="bi bi-camera-fill fs-4"></i>
                    <span style="font-size:0.72rem;">Photo {!! $i !!}{!! $i===1?' (Main)':' (Optional)' !!}</span>
                </div>
                <button type="button" onclick="removeImgSlot(event,'{!! $prefix !!}',{!! $i !!})"
                        class="btn btn-sm btn-danger position-absolute {!! $existingUrl ? '' : 'd-none' !!}"
                        id="{!! $prefix !!}Img{!! $i !!}Remove"
                        style="top:4px;right:4px;padding:2px 7px;font-size:0.7rem;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <input type="file" name="hall_image_{!! $i !!}" id="{!! $prefix !!}Img{!! $i !!}"
                   class="d-none" accept="image/jpeg,image/png,image/webp"
                   onchange="previewImgSlot(this,'{!! $prefix !!}',{!! $i !!})">
        </div>
        @endfor
    </div>

    <!-- ── SECTION 2: Basic Info ── -->
    <div class="section-label mb-3">
        <span class="badge text-bg-primary fw-bold">
            <i class="bi bi-info-circle me-1"></i>Section 2 — Basic Information
        </span>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <label class="form-label fw-semibold small">Hall Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input type="text" name="name" class="form-control" required maxlength="150"
                       value="{{ $hall['name'] ?? '' }}">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select">
                @foreach (['active'=>'Active','inactive'=>'Inactive','maintenance'=>'Maintenance'] as $val=>$lbl)
                <option value="{!! $val !!}" {!! ($hall['status']??'active')===$val?'selected':'' !!}>{!! $lbl !!}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">Capacity <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-people"></i></span>
                <input type="number" name="capacity" class="form-control" min="1" max="10000" required
                       value="{!! (int)($hall['capacity']??50) !!}">
                <span class="input-group-text">people</span>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">Seating Style</label>
            <select name="seating_style" class="form-select">
                @foreach ($styles as $s)
                <option value="{!! $s !!}" {!! ($hall['seating_style']??'Theatre Style')===$s?'selected':'' !!}>{!! $s !!}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- ── SECTION 3: Amenities ── -->
    <div class="section-label mb-3">
        <span class="badge text-bg-success fw-bold">
            <i class="bi bi-tools me-1"></i>Section 3 — Amenities
        </span>
    </div>
    <div class="row g-2 mb-4">
        @foreach ($amenityFields as $field => $label)
        <div class="col-6 col-md-4">
            <div class="form-check border rounded p-2 bg-light">
                <input class="form-check-input" type="checkbox" name="{!! $field !!}" value="1"
                       id="{!! $prefix !!}_{!! $field !!}"
                       {!! ($hall[$field]??1) ? 'checked' : '' !!}>
                <label class="form-check-label small" for="{!! $prefix !!}_{!! $field !!}">{!! $label !!}</label>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ── SECTION 4: About the Hall ── -->
    <div class="section-label mb-3">
        <span class="badge text-bg-info text-dark fw-bold">
            <i class="bi bi-file-text me-1"></i>Section 4 — About the Hall
        </span>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold small">Main Description</label>
        <textarea name="description" class="form-control" rows="4"
                  placeholder="Describe the hall — its purpose, history and what makes it special…">{{ $hall['description']??'' }}</textarea>
        <div class="form-text">Displays in the 'About This Hall' section on the hall detail page.</div>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold small">Extended Description <span class="text-muted fw-normal">(optional)</span></label>
        <textarea name="about_extended" class="form-control" rows="3"
                  placeholder="Additional paragraph about equipment, atmosphere, etc…">{{ $hall['about_extended']??'' }}</textarea>
    </div>

    <!-- ── SECTION 5: Pricing & Duration ── -->
    <div class="section-label mb-3">
        <span class="badge fw-bold" style="background:var(--ceac-orange);">
            <i class="bi bi-cash-coin me-1"></i>Section 5 — Pricing &amp; Duration
        </span>
        <small class="text-muted ms-2">
            Leave blank to hide pricing from the booking form.
        </small>
    </div>

    @php
$pricing = !empty($hall['pricing_json'])
        ? (json_decode($hall['pricing_json'], true) ?: [])
        : [];
    $p = function(string $key, $default = '') use ($pricing) {
        return htmlspecialchars((string)($pricing[$key] ?? $default));
    };
@endphp

    <div class="mb-3">
        <label class="form-label fw-semibold small">Currency</label>
        <select name="pricing_currency" class="form-select">
            <option value="GHS" {!! ($pricing['currency'] ?? 'GHS') === 'GHS' ? 'selected' : '' !!}>GHS (Ghana Cedis ₵)</option>
            <option value="USD" {!! ($pricing['currency'] ?? '') === 'USD' ? 'selected' : '' !!}>USD ($)</option>
            <option value="EUR" {!! ($pricing['currency'] ?? '') === 'EUR' ? 'selected' : '' !!}>EUR (€)</option>
        </select>
    </div>

       <div class="p-3 rounded-3 border mb-3"
         style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border-color:#BFDBFE!important;">
        <p class="fw-bold small text-uppercase mb-3" style="letter-spacing:1px;color:#1E40AF;">
            <i class="bi bi-people-fill me-1"></i>Meetings Pricing
        </p>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Half-Time Hours</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                    <input type="number" name="pricing_meetings_half_hours" class="form-control"
                           placeholder="e.g. 2" min="1" value="{!! $p('meetings_half_hours', 2) !!}">
                    <span class="input-group-text">hrs</span>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Half-Time Price (₵)</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_meetings_half_price" class="form-control"
                           placeholder="e.g. 500" min="0" value="{!! $p('meetings_half_price') !!}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Full-Time Hours</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                    <input type="number" name="pricing_meetings_full_hours" class="form-control"
                           placeholder="e.g. 4" min="1" value="{!! $p('meetings_full_hours', 4) !!}">
                    <span class="input-group-text">hrs</span>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Full-Time Price (₵)</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_meetings_full_price" class="form-control"
                           placeholder="e.g. 1000" min="0" value="{!! $p('meetings_full_price') !!}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Penalty/Extra Hour (₵)</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_penalty" class="form-control"
                           placeholder="e.g. 500" min="0" value="{!! $p('penalty_per_hour') !!}">
                </div>
            </div>
        </div>
    </div>

    <div class="p-3 rounded-3 border mb-3"
         style="background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border-color:#BBF7D0!important;">
        <p class="fw-bold small text-uppercase mb-3" style="letter-spacing:1px;color:#14532D;">
            <i class="bi bi-tools me-1"></i>Facility Prices (per booking)
        </p>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">
                    <i class="bi bi-display me-1 text-ceac-orange"></i>Display Screen (₵)
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_projector" class="form-control"
                           placeholder="e.g. 200" min="0" value="{!! $p('facility_projector') !!}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">
                    <i class="bi bi-speaker me-1 text-ceac-orange"></i>PA System (₵)
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_pa_system" class="form-control"
                           placeholder="e.g. 200" min="0" value="{!! $p('facility_pa_system') !!}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">
                    <i class="bi bi-thermometer-snow me-1 text-ceac-orange"></i>Air Conditioning (₵)
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_ac" class="form-control"
                           placeholder="e.g. 200" min="0" value="{!! $p('facility_ac') !!}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">
                    <i class="bi bi-easel me-1 text-ceac-orange"></i>Whiteboard (₵)
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">₵</span>
                    <input type="number" name="pricing_whiteboard" class="form-control"
                           placeholder="e.g. 200" min="0" value="{!! $p('facility_whiteboard') !!}">
                </div>
            </div>
        </div>
    </div>
