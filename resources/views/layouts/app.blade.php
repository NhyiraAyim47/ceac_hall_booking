@php
    $metaTitle       = $metaTitle ?? config('ceac.site_full');
    $metaDescription = $metaDescription ?? 'Book the CEAC Hall at Christ Embassy Airport City, Accra Ghana for cell meetings, fellowship, executive meetings and all-night services.';
    $metaKeywords    = $metaKeywords ?? 'CEAC Hall, Christ Embassy Airport City, Hall Booking, Accra Ghana, Church Events, Cell Meeting, Fellowship';
    $canonicalUrl    = base_url() . request()->getRequestUri();
    $currentPage     = request()->path() === '/' ? 'index' : basename(request()->path());
@endphp

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO -->
    <title>{{ $metaTitle }} | CEAC</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="author" content="Christ Embassy Airport City">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ base_url() }}/assets/images/logo.png">
    <meta property="og:site_name" content="CEAC Hall Booking">
    <meta property="og:locale" content="en_GH">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    <!-- Security Headers (set via PHP too) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https: http: blob:; frame-src https://maps.google.com https://www.google.com https://maps.app.goo.gl; connect-src 'self' https://cdn.jsdelivr.net https://mail.google.com;">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ base_url() }}/assets/css/main.css?v={{ config('ceac.version') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ base_url() }}/assets/images/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ base_url() }}/assets/images/logo.png">
    <link rel="shortcut icon" href="{{ base_url() }}/assets/images/logo.png">
    <link rel="apple-touch-icon" href="{{ base_url() }}/assets/images/logo.png">

    <script>
    // Expose base URL to JS
    window.CEAC_BASE = '{{ base_url() }}';
    </script>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "EventVenue",
        "name": "CEAC Hall",
        "description": "Hall booking venue at Christ Embassy Airport City, Accra Ghana",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "10 Blackberry Street, Estate Legon",
            "addressLocality": "Accra",
            "addressCountry": "GH"
        },
        "telephone": "{{ config('ceac.phone') }}",
        "email": "{{ config('ceac.email') }}",
        "url": "{{ base_url() }}",
        "maximumAttendeeCapacity": 50
    }
    </script>
</head>
<body>
@php
$navLinks = [
    ['href' => '/',               'label' => 'Home',     'icon' => 'house'],
    ['href' => '/halls','label' => 'Hall',     'icon' => 'building'],
    ['href' => '/events','label' => 'Events',  'icon' => 'calendar-event'],
    ['href' => '/about', 'label' => 'About Us','icon' => 'info-circle'],
    ['href' => '/contact','label' => 'Contact','icon' => 'envelope'],
    ['href' => '/policy','label' => 'Policy',  'icon' => 'shield-check'],
];
@endphp

<!-- ── TOP ANNOUNCEMENT BAR ─────────────────────────────── -->
<div class="announcement-bar" id="announcementBar">
    <div class="container-fluid">
        <span><i class="bi bi-geo-alt-fill me-1"></i>10 Blackberry Street, Estate Legon, Accra</span>
        <span class="mx-3 d-none d-sm-inline">|</span>
        <a href="tel:0244036129" class="text-white text-decoration-none"><i class="bi bi-telephone-fill me-1"></i>024 403 6129</a>
        <span class="mx-3 d-none d-sm-inline">|</span>
        <span class="d-none d-md-inline"><i class="bi bi-clock-fill me-1"></i>Mon–Sat: 8AM–8PM</span>
    </div>
    <button class="btn-close-bar" onclick="document.getElementById('announcementBar').remove()" aria-label="Close"><i class="bi bi-x"></i></button>
</div>

<!-- ── MAIN NAVBAR ─────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg ceac-navbar sticky-top" id="mainNav">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ base_url() }}/">
            <img src="{{ base_url() }}/assets/images/logo.png" alt="Christ Embassy Airport City Logo" class="navbar-logo" width="120" height="44" loading="eager" style="object-fit:contain;">
            <div class="brand-text">
                <span class="brand-title">CEAC</span>
                <span class="brand-sub">Hall Booking</span>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon"><span></span><span></span><span></span></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto gap-1">
                @foreach ($navLinks as $link)
                    @php
                        $active = ($currentPage === basename($link['href'], '.php') || ($currentPage === 'index' && $link['href'] === '/'));
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link{!! $active ? ' active' : '' !!}" href="{!! base_url() . $link['href'] !!}">
                            <i class="bi bi-{!! $link['icon'] !!} me-1"></i>{!! $link['label'] !!}                    </a>
                    </li>
                @endforeach
            </ul>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-ceac-primary btn-book-now" data-bs-toggle="modal" data-bs-target="#bookingModal">
                    <i class="bi bi-calendar-plus me-1"></i>Book Now
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- ── BOOKING MODAL ─────────────────────────────────── -->

<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-height:95vh;">
        <div class="modal-content ceac-modal" style="max-height:92vh;display:flex;flex-direction:column;">

            <!-- Fixed Header -->
            <div class="modal-header flex-shrink-0">
                <div>
                    <h5 class="modal-title" id="bookingModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i>Book a Hall
                    </h5>
                    <p class="text-white-50 small mb-0">Select a hall and fill in your event details</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Scrollable Body -->
            <div class="modal-body flex-grow-1" style="overflow-y:auto;">
                <div id="bookingAlert" class="d-none mb-3"></div>
                <form id="bookingForm" novalidate>
                    @csrf
                    <div class="row g-3">

                        <!-- ── Hall Selection ─────────────────── -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Select Hall <span class="text-danger">*</span>
                            </label>
                            @if (empty($activeHalls))
                            <div class="alert alert-warning py-2 small">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                No halls are currently available for booking. Please contact us directly.
                            </div>
                            <input type="hidden" name="hall_id" value="">
                            @else
                            <div class="row g-2" id="hallSelectionGrid">
                                @foreach ($activeHalls as $h)
                                <div class="col-md-6">
                                    <label class="hall-option-card w-100" for="hall_{!! $h['id'] !!}"
                                           style="cursor:pointer;">
                                        <input type="radio" name="hall_id" value="{!! $h['id'] !!}"
                                               id="hall_{!! $h['id'] !!}"
                                               data-capacity="{!! $h['capacity'] !!}"
                                               data-name="{{ $h['name'] }}"
                                               data-pricing="{{ $h['pricing_json'] ?? '{}' }}"
                                               class="hall-radio d-none"
                                               required
                                               {!! count($activeHalls) === 1 ? 'checked' : '' !!}>
                                        <div class="border rounded-3 p-3 h-100 hall-card-option
                                                    {!! count($activeHalls) === 1 ? 'selected-hall' : '' !!}"
                                             style="transition:all 0.2s;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong class="d-block">{{ $h['name'] }}</strong>
                                                    <small class="text-muted">{{ $h['seating_style'] }}</small>
                                                </div>
                                                <span class="badge"
                                                      style="background:rgba(255,107,0,0.1);color:var(--ceac-orange);font-size:0.75rem;">
                                                    <i class="bi bi-people-fill me-1"></i>{!! $h['capacity'] !!} pax
                                                </span>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <span class="hall-check-icon text-ceac-orange d-none">
                                                    <i class="bi bi-check-circle-fill"></i> Selected
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- ── Hall Capacity Info (updates dynamically) ── -->
                        <div class="col-12" id="hallCapacityInfo" style="display:{!! count($activeHalls) === 1 ? 'block' : 'none' !!};">
                            <div class="alert alert-info py-2 small mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                                <span id="hallCapacityText">
                                    @if (count($activeHalls) === 1)
                                    Capacity: <strong>{!! $activeHalls[0]['capacity'] !!} people</strong>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- ── Hall Pricing Display ── -->
                        <div class="col-12" id="hallPricingSection" style="display:none;">
                            <div class="border rounded-3 p-3" style="background:#FFFBEB;border-color:#FED7AA!important;">
                                <h6 class="fw-bold mb-3" style="color:#92400E;">
                                    <i class="bi bi-cash-coin me-2"></i>Pricing &amp; Duration
                                </h6>
                                <div id="hallPricingBody"></div>
                                <div class="border-top mt-3 pt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Estimated Total:</strong>
                                        <strong class="fs-5 text-ceac-orange" id="hallPricingTotal">₵0</strong>
                                    </div>
                                    <p class="text-muted small mb-0 mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Final amount confirmed by admin upon approval.
                                        A penalty of <span id="penaltyText">₵0</span>/hour applies if you exceed your booked time.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ── Personal Details ─────────────────── -->
                        <div class="col-12">
                            <div class="border-top pt-3 mt-1">
                                <p class="fw-semibold small text-muted text-uppercase mb-3" style="letter-spacing:1px;">
                                    <i class="bi bi-person me-1"></i> Your Details
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="full_name"
                                       placeholder="Your full name" required minlength="2" maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="email"
                                       placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" name="phone"
                                       placeholder="024 XXX XXXX" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Organization / Cell</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" name="organization"
                                       placeholder="Ministry / Cell name">
                            </div>
                        </div>

                        <!-- ── Event Details ─────────────────────── -->
                        <div class="col-12">
                            <div class="border-top pt-3 mt-1">
                                <p class="fw-semibold small text-muted text-uppercase mb-3" style="letter-spacing:1px;">
                                    <i class="bi bi-calendar-event me-1"></i> Event Details
                                </p>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Event Title <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bookmark"></i></span>
                                <input type="text" class="form-control" name="event_title"
                                       placeholder="Name of your event" required maxlength="200">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Event Type</label>
                            <input type="text" class="form-control" value="Meetings" disabled>
                            <input type="hidden" name="event_type" id="bookingEventType" value="meetings">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Attendees <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <input type="number" class="form-control" name="expected_attendees"
                                       id="attendeesInput" min="1" placeholder="Number of people" required>
                            </div>
                            <div class="form-text" id="attendeesHint">
                                @if (count($activeHalls) === 1)
                                Hall capacity: <strong>{!! $activeHalls[0]['capacity'] !!} people</strong>
                                @else
                                Select a hall above to see its capacity
                                @endif
                            </div>
                            <div id="dateAvailabilityFeedback"></div>
                        </div>
                        <!-- ── Duration Selection (shows when event type is selected) ── -->
                        <div class="col-12" id="bookingDurationWrap" style="display:none;">
                            <label class="form-label fw-semibold">
                                Booking Duration <span class="text-danger">*</span>
                            </label>
                            <div id="bookingDurationOptions" class="d-flex flex-wrap gap-2"></div>
                            <div class="form-text">Select your preferred duration. Price updates automatically below.</div>
                            <input type="hidden" name="booking_duration_type" id="bookingDurationType" value="">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Start Date &amp; Time
                                <span class="text-muted fw-normal small">(optional)</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="start_datetime"
                                   min="{!! date('Y-m-d\TH:i', strtotime('+1 day')) !!}">
                            <div class="form-text">Leave blank if date is not yet confirmed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                End Date &amp; Time
                                <span class="text-muted fw-normal small">(optional)</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="end_datetime">
                            <div class="form-text">Can be a different day for multi-day events.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Facilities Needed</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="needs_projector" value="1" id="chkProjector">
                                    <label class="form-check-label" for="chkProjector"><i class="bi bi-display me-1"></i>Projector / Screen</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="needs_pa_system" value="1" id="chkPA">
                                    <label class="form-check-label" for="chkPA"><i class="bi bi-speaker me-1"></i>PA System</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="needs_ac" value="1" id="chkAC" checked>
                                    <label class="form-check-label" for="chkAC"><i class="bi bi-thermometer-snow me-1"></i>Air Conditioning</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="needs_whiteboard" value="1" id="chkWB">
                                    <label class="form-check-label" for="chkWB"><i class="bi bi-easel me-1"></i>Whiteboard</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Special Requirements</label>
                            <textarea class="form-control" name="special_requirements" rows="3"
                                      placeholder="Any special setup or additional requests…" maxlength="1000"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Fixed Footer -->
            <div class="modal-footer flex-shrink-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-ceac-primary" id="submitBooking">
                    <span class="btn-text"><i class="bi bi-send me-1"></i>Submit Booking Request</span>
                    <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-1"></span>Submitting…</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Hall selection card styling */
.hall-card-option {
    border-color: #E5E7EB !important;
    background: #FAFAFA;
}
.hall-card-option:hover {
    border-color: var(--ceac-orange) !important;
    background: rgba(255,107,0,0.03);
}
.selected-hall {
    border-color: var(--ceac-orange) !important;
    background: rgba(255,107,0,0.06) !important;
    box-shadow: 0 0 0 2px rgba(255,107,0,0.2);
}
.selected-hall .hall-check-icon { display: inline !important; }
</style>

<script>
// ── Hall selection interaction ────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const radios    = document.querySelectorAll('.hall-radio');
    const capInfo   = document.getElementById('hallCapacityInfo');
    const capText   = document.getElementById('hallCapacityText');
    const attInput  = document.getElementById('attendeesInput');
    const attHint   = document.getElementById('attendeesHint');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const capacity = parseInt(this.dataset.capacity);
            const name     = this.dataset.name;

            const pricingRaw = this.dataset.pricing || '{}';
            let pricing = {};
            try { pricing = JSON.parse(pricingRaw); } catch(e) {}
            updatePricingDisplay(pricing);

            const cur = pricing.currency === 'USD' ? '$' : pricing.currency === 'EUR' ? '€' : '₵';
            updateDurationOptions(pricing);
            recalcTotal(pricing, cur);

            document.querySelectorAll('.hall-card-option').forEach(card => {
                card.classList.remove('selected-hall');
                card.querySelector('.hall-check-icon').classList.add('d-none');
            });
            const selectedCard = this.closest('label').querySelector('.hall-card-option');
            selectedCard.classList.add('selected-hall');
            selectedCard.querySelector('.hall-check-icon').classList.remove('d-none');

            capInfo.style.display = 'block';
            capText.innerHTML     = `<strong>${name}</strong> — Capacity: <strong>${capacity} people</strong>`;
            attInput.max          = capacity;
            attHint.innerHTML     = `Hall capacity: <strong>${capacity} people</strong>`;
        });
    });

    if (radios.length === 1 && radios[0].checked) {
        radios[0].dispatchEvent(new Event('change'));
    }

    document.addEventListener('change', function(e) {
        const form = document.getElementById('bookingForm');
        if (!form) return;
        if (e.target.type === 'checkbox') {
            const selectedHall = form.querySelector('[name="hall_id"]:checked');
            if (!selectedHall) return;
            let pricing = {};
            try { pricing = JSON.parse(selectedHall.dataset.pricing || '{}'); } catch(e) {}
            const cur = pricing.currency === 'USD' ? '$' : pricing.currency === 'EUR' ? '€' : '₵';
            recalcTotal(pricing, cur);
        }
    });
});

function updatePricingDisplay(pricing) {
    const section = document.getElementById('hallPricingSection');
    const body    = document.getElementById('hallPricingBody');
    const penalty = document.getElementById('penaltyText');
    if (!section || !body) return;

    const cur = pricing.currency === 'USD' ? '$' : pricing.currency === 'EUR' ? '€' : '₵';
    const hp = pricing.meetings_half_price || 0;
    const hh = pricing.meetings_half_hours || 2;
    const fp = pricing.meetings_full_price || 0;
    const fh = pricing.meetings_full_hours || 4;
    if (hp <= 0 && fp <= 0) { section.style.display = 'none'; return; }

    let rows = '<table class="table table-sm table-borderless mb-2" style="font-size:0.82rem;"><thead><tr><th class="text-muted ps-0">Event Type</th><th class="text-muted">Duration</th><th class="text-muted text-end pe-0">Price</th></tr></thead><tbody>';

    let durCell = (hp > 0 && fp > 0) ? `${hh}hrs / ${fh}hrs` : (hp > 0 ? `${hh} hrs` : `${fh} hrs`);
    let priceCell = (hp > 0 && fp > 0) ? `${cur}${hp.toLocaleString()} / ${cur}${fp.toLocaleString()}` : (hp > 0 ? `${cur}${hp.toLocaleString()}` : `${cur}${fp.toLocaleString()}`);

    rows += `<tr>
        <td class="ps-0 fw-semibold">Meetings</td>
        <td class="text-muted">${durCell}</td>
        <td class="text-end pe-0 fw-semibold text-ceac-orange">${priceCell}</td>
    </tr>`;

    const facilityLabels = {
        facility_projector: 'Display Screen',
        facility_pa_system: 'PA System',
        facility_ac:        'Air Conditioning (Bonus)',
        facility_whiteboard:'Wi-Fi',
    };
    let facilityRows = '';
    for (const [key, label] of Object.entries(facilityLabels)) {
        if ((pricing[key] || 0) > 0) {
            facilityRows += `<tr>
                <td class="ps-0 text-muted">${label}</td>
                <td class="text-muted">if selected</td>
                <td class="text-end pe-0 text-muted">+${cur}${(pricing[key] || 0).toLocaleString()}</td>
            </tr>`;
        }
    }
    if (facilityRows) {
        rows += `<tr><td colspan="3" class="ps-0 pt-2"><small class="fw-bold text-uppercase text-muted" style="letter-spacing:0.8px;">Facilities</small></td></tr>`;
        rows += facilityRows;
    }
    rows += '</tbody></table>';

    body.innerHTML = rows;
    section.style.display = '';
    if (penalty && (pricing.penalty_per_hour || 0) > 0) penalty.textContent = `${cur}${pricing.penalty_per_hour}`;

    updateDurationOptions(pricing);
    recalcTotal(pricing, cur);
}

function updateDurationOptions(pricing) {
    const wrap    = document.getElementById('bookingDurationWrap');
    const optsCon = document.getElementById('bookingDurationOptions');
    const hiddenIn = document.getElementById('bookingDurationType');
    if (!wrap || !optsCon) return;

    const cur = pricing.currency === 'USD' ? '$' : pricing.currency === 'EUR' ? '€' : '₵';
    let options = [];
    const hp = pricing.meetings_half_price || 0;
    const hh = pricing.meetings_half_hours || 2;
    const fp = pricing.meetings_full_price || 0;
    const fh = pricing.meetings_full_hours || 4;
    if (hp > 0) options.push({value:'half', label:`Half Time — ${hh} hrs`, price:hp, cur});
    if (fp > 0) options.push({value:'full', label:`Full Time — ${fh} hrs`, price:fp, cur});

    if (options.length === 0) { wrap.style.display = 'none'; hiddenIn.value = ''; return; }

    optsCon.innerHTML = '';
    options.forEach((opt, i) => {
        const isFirst = i === 0;
        const btn = document.createElement('div');
        btn.className = 'form-check-duration';
        btn.innerHTML = `
            <input type="radio" name="duration_selection" id="dur_${opt.value}"
                   value="${opt.value}" class="d-none" ${isFirst ? 'checked' : ''}>
            <label for="dur_${opt.value}" class="btn btn-sm ${isFirst ? 'btn-ceac-primary' : 'btn-outline-secondary'}"
                   style="cursor:pointer;border-radius:8px;padding:8px 16px;"
                   onclick="selectDuration('${opt.value}', ${opt.price}, '${cur}')">
                <strong>${opt.label}</strong>
                <span class="ms-2 fw-bold">${cur}${opt.price.toLocaleString()}</span>
            </label>`;
        optsCon.appendChild(btn);
    });

    if (options.length > 0) hiddenIn.value = options[0].value;
    wrap.style.display = '';
}

function selectDuration(value, price, cur) {
    document.getElementById('bookingDurationType').value = value;
    document.querySelectorAll('[name="duration_selection"]').forEach(r => {
        const lbl = document.querySelector(`label[for="${r.id}"]`);
        if (!lbl) return;
        if (r.value === value) {
            r.checked = true;
            lbl.className = lbl.className.replace('btn-outline-secondary','btn-ceac-primary');
        } else {
            r.checked = false;
            lbl.className = lbl.className.replace('btn-ceac-primary','btn-outline-secondary');
        }
    });
    const selectedHall = document.querySelector('[name="hall_id"]:checked');
    if (!selectedHall) return;
    let pricing = {};
    try { pricing = JSON.parse(selectedHall.dataset.pricing || '{}'); } catch(e) {}
    recalcTotal(pricing, cur);
}

function recalcTotal(pricing, cur) {
    const total    = document.getElementById('hallPricingTotal');
    const durType  = document.getElementById('bookingDurationType')?.value || 'half';
    const form     = document.getElementById('bookingForm');
    if (!total || !form) return;

    let venuePrice = durType === 'full' ? (pricing.meetings_full_price || 0) : (pricing.meetings_half_price || 0);

    let facilityTotal = 0;
    const facilityMap = {
        needs_projector: 'facility_projector',
        needs_pa_system: 'facility_pa_system',
        needs_ac:        'facility_ac',
        needs_whiteboard:'facility_whiteboard',
    };
    Object.entries(facilityMap).forEach(([field, key]) => {
        const cb = form.querySelector(`[name="${field}"]`);
        if (cb && cb.checked && (pricing[key] || 0) > 0) facilityTotal += pricing[key];
    });

    const grandTotal = venuePrice + facilityTotal;
    total.textContent = `${cur || '₵'}${grandTotal.toLocaleString()}`;
}
</script>
<!-- ── PAGE CONTENT STARTS ─────────────────────────── -->
@yield('content')

<!-- ── FOOTER ─────────────────────────────────────────── -->

<footer class="ceac-footer mt-auto">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                <!-- Brand -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <img src="{{ base_url() }}/assets/images/logo.png" alt="CEAC Logo" width="140" height="44" loading="lazy" style="object-fit:contain;">
                        <div>
                            <strong class="footer-brand-name">Christ Embassy</strong><br>
                            <small class="text-muted-light">Airport City, Accra Ghana</small>
                        </div>
                    </div>
                    <p class="footer-desc">
                        The CEAC Hall Booking System is the official platform for reserving the hall at Christ Embassy Airport City for kingdom-advancing gatherings and events.
                    </p>
                    <div class="footer-contact-info">
                        <div class="footer-contact-item">
                            <i class="bi bi-geo-alt-fill text-ceac-orange"></i>
                            <span>10 Blackberry Street, Estate Legon, Accra</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-telephone-fill text-ceac-orange"></i>
                            <a href="tel:0244036129">024 403 6129</a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-envelope-fill text-ceac-orange"></i>
                            <a href="https://mail.google.com/mail/?view=cm&to={!! urlencode(config('ceac.email')) !!}" target="_blank" rel="noopener noreferrer">{{ config('ceac.email') }}</a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="{{ base_url() }}/"><i class="bi bi-chevron-right"></i>Home</a></li>
                        <li><a href="{{ base_url() }}/halls"><i class="bi bi-chevron-right"></i>Our Halls</a></li>
                        <li><a href="{{ base_url() }}/events"><i class="bi bi-chevron-right"></i>Events</a></li>
                        <li><a href="{{ base_url() }}/about"><i class="bi bi-chevron-right"></i>About Us</a></li>
                        <li><a href="{{ base_url() }}/contact"><i class="bi bi-chevron-right"></i>Contact</a></li>
                        <li><a href="{{ base_url() }}/policy"><i class="bi bi-chevron-right"></i>Policy & Guidelines</a></li>
                    </ul>
                </div>

                <!-- Events -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="footer-heading">Regular Events</h6>
                    <ul class="footer-links">
                        <li><a href="{{ base_url() }}/events#cell"><i class="bi bi-chevron-right"></i>Cell Meetings</a></li>
                        <li><a href="{{ base_url() }}/events#fellowship"><i class="bi bi-chevron-right"></i>Fellowship Meetings</a></li>
                        <li><a href="{{ base_url() }}/events#executive"><i class="bi bi-chevron-right"></i>Executive Meetings</a></li>
                        <li><a href="{{ base_url() }}/events#allnight"><i class="bi bi-chevron-right"></i>All Night Services</a></li>
                    </ul>
                </div>

                <!-- Book Now CTA -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Book the Hall</h6>
                    <div class="footer-cta-card">
                        <div class="footer-cta-icon"><i class="bi bi-building-check"></i></div>
                        <h6>CEAC Hall</h6>
                        <p class="small">
                            Capacity: <strong>{!! $footerHall ? htmlspecialchars($footerHall['capacity']) . ' People' : 'Not available' !!}</strong><br>
                            Style: <strong>{!! $footerHall ? htmlspecialchars($footerHall['seating_style']) : 'Not available' !!}</strong>
                        </p>
                        <div class="footer-amenity-badges">
                            <span><i class="bi bi-display"></i> Screen</span>
                            <span><i class="bi bi-speaker"></i> PA</span>
                            <span><i class="bi bi-thermometer-snow"></i> AC</span>
                            <span><i class="bi bi-wifi"></i> WiFi</span>
                        </div>
                        <button class="btn btn-ceac-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-plus me-1"></i>Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small">
                &copy; {!! date('Y') !!} Christ Embassy Airport City &mdash; All Rights Reserved
            </p>
            <p class="mb-0 small text-muted-light">
                Built with <i class="bi bi-heart-fill text-danger mx-1"></i> for Kingdom Impact &bull; Airport City Zone, Accra Ghana
            </p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Main JS -->
<script src="{{ base_url() }}/assets/js/main.js?v={{ config('ceac.version') }}"></script>

<!-- ── Toast Container ─────────────────────────── -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index:9999;"></div>

<!-- ── Back to Top ──────────────────────────────── -->
<button class="back-to-top" id="backToTop" aria-label="Back to top" title="Back to top">
    <i class="bi bi-arrow-up"></i>
</button>
</body>
</html>