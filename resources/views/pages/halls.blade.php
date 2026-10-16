@extends('layouts.app')

@section('content')
<!-- ── PAGE HERO ─────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ base_url() }}/">Home</a></li>
                <li class="breadcrumb-item {!! $viewHall ? '' : 'active' !!}">
                    @if ($viewHall)
                    <a href="{{ base_url() }}/halls">Halls</a>
                    @else
                    Halls
                    @endif
                </li>
                @if ($viewHall)
                <li class="breadcrumb-item active">{{ $viewHall['name'] }}</li>
                @endif
            </ol>
        </nav>
        @if ($viewHall)
        <h1><i class="bi bi-building me-2 text-ceac-orange"></i>{{ $viewHall['name'] }}</h1>
        <p class="text-white-50">Christ Embassy Airport City &bull; Airport City Zone, Accra Ghana</p>
        @else
        <h1><i class="bi bi-building me-2 text-ceac-orange"></i>Our Halls</h1>
        <p class="text-white-50">Browse all available venues at Christ Embassy Airport City</p>
        @endif
    </div>
</section>

@if ($viewHall)
<!-- ═══════════════════════════════════════════════════════════ -->
<!-- ── SINGLE HALL DETAIL VIEW ──────────────────────────────── -->
<!-- ═══════════════════════════════════════════════════════════ -->
@php
$hallImages  = getAllHallImages($viewHall);
$mainImage   = $hallImages[0];
$thumbImages = $hallImages;

// Parse extra fields stored as JSON
$idealFor    = !empty($viewHall['ideal_for_json'])    ? json_decode($viewHall['ideal_for_json'], true)    : null;
$features    = !empty($viewHall['features_json'])     ? json_decode($viewHall['features_json'], true)     : null;
$seatingInfo = !empty($viewHall['seating_info'])      ? $viewHall['seating_info']                         : null;

// Default ideal for entries based on event types
$defaultIdealFor = [
    ['icon'=>'people-fill',    'color'=>'#2563EB', 'name'=>'Cell Meetings',        'desc'=>'Small group Bible study & fellowship'],
    ['icon'=>'broadcast',      'color'=>'#059669', 'name'=>'Fellowship Meetings',   'desc'=>'Corporate worship & the Word'],
    ['icon'=>'briefcase-fill', 'color'=>'#7C3AED', 'name'=>'Executive Meetings',   'desc'=>'Leadership & ministry strategy sessions'],
    ['icon'=>'moon-stars-fill','color'=>'#C2410C', 'name'=>'All Night Services',   'desc'=>'Overnight prayer & worship events'],
];
$idealForList = $idealFor ?: $defaultIdealFor;

// Default features based on amenities
$defaultFeatures = [];
if ($viewHall['capacity'])      $defaultFeatures[] = $viewHall['capacity'] . ' seating capacity';
if ($viewHall['seating_style']) $defaultFeatures[] = $viewHall['seating_style'] . ' Arrangement';
if ($viewHall['has_projector']) $defaultFeatures[] = 'Large Display Screen';
if ($viewHall['has_pa_system']) $defaultFeatures[] = 'Professional PA System';
if ($viewHall['has_ac'])        $defaultFeatures[] = 'Full Air Conditioning';
if ($viewHall['has_wifi'])      $defaultFeatures[] = 'High-Speed WiFi';
if ($viewHall['has_whiteboard'])$defaultFeatures[] = 'Whiteboard Available';
if ($viewHall['has_parking'])   $defaultFeatures[] = 'On-Site Parking';
$featuresList = $features ?: $defaultFeatures;
@endphp

<section class="section-pad" aria-label="Hall Detail">
    <div class="container">
        <div class="row g-5">
            <!-- ── Left: Gallery + Info ───────────────────── -->
            <div class="col-lg-8">

                <!-- Photo Gallery -->
                <h3 class="fw-bold mb-4"><i class="bi bi-images me-2 text-ceac-orange"></i>Photo Gallery</h3>
                <div class="gallery-main mb-2">
                    <img src="{!! $mainImage !!}" alt="{{ $viewHall['name'] }}" id="mainGalleryImg">
                </div>
                @if (count($thumbImages) > 1)
                <div class="gallery-thumbs mb-5">
                    @foreach ($thumbImages as $i => $img)
                    <div class="gallery-thumb {!! $i === 0 ? 'active' : '' !!}">
                        <img src="{!! $img !!}" alt="{{ $viewHall['name'] }} view {!! $i+1 !!}">
                    </div>
                    @endforeach
                </div>
                @else
                <div class="mb-5"></div>
                @endif

                <!-- About This Hall -->
                @if (!empty($viewHall['description']))
                <div class="mb-5" data-animate>
                    <h3 class="fw-bold mb-3">About This Hall</h3>
                    <div class="divider-ceac mb-4"></div>
                    <p class="text-muted" style="line-height:1.9;">{!! nl2br(htmlspecialchars($viewHall['description'])) !!}</p>
                </div>
                @endif

                <!-- About This Hall (extended) -->
                @if (!empty($viewHall['about_extended']))
                <div class="mb-5" data-animate>
                    <p class="text-muted" style="line-height:1.9;">{!! nl2br(htmlspecialchars($viewHall['about_extended'])) !!}</p>
                </div>
                @endif
            </div>

            <!-- ── Right: Hall Summary Card ───────────────── -->
            <div class="col-lg-4">
                <div class="hall-summary-card" data-animate>
                    <h5><i class="bi bi-info-circle me-2 text-ceac-orange"></i>Hall Summary</h5>
                    <p class="text-white-50 small">Key details at a glance</p>

                    <div class="summary-row">
                        <span class="summary-label">Hall Name</span>
                        <span class="summary-value">{{ $viewHall['name'] }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Capacity</span>
                        <span class="summary-value">{!! $viewHall['capacity'] !!} pax</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Seating Style</span>
                        <span class="summary-value">{{ $viewHall['seating_style'] ?? 'Theatre Style' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Display Screen</span>
                        <span class="summary-value {!! $viewHall['has_projector'] ? 'text-green' : 'text-white-50' !!}">
                            {!! $viewHall['has_projector'] ? '✓ Available' : '✗ Not Available' !!}                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Air Conditioning</span>
                        <span class="summary-value {!! $viewHall['has_ac'] ? 'text-green' : 'text-white-50' !!}">
                            {!! $viewHall['has_ac'] ? '✓ Full AC' : '✗ Not Available' !!}                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">PA System</span>
                        <span class="summary-value {!! $viewHall['has_pa_system'] ? 'text-green' : 'text-white-50' !!}">
                            {!! $viewHall['has_pa_system'] ? '✓ Professional' : '✗ Not Available' !!}                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">WiFi</span>
                        <span class="summary-value {!! $viewHall['has_wifi'] ? 'text-green' : 'text-white-50' !!}">
                            {!! $viewHall['has_wifi'] ? '✓ Available' : '✗ Not Available' !!}                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Parking</span>
                        <span class="summary-value {!! $viewHall['has_parking'] ? 'text-green' : 'text-white-50' !!}">
                            {!! $viewHall['has_parking'] ? '✓ On-site' : '✗ Not Available' !!}                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Status</span>
                        <span class="summary-value text-green">● Active</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Events This Month</span>
                        <span class="summary-value">{!! $viewHall['this_month'] !!}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Total Events Held</span>
                        <span class="summary-value">{!! $viewHall['approved_count'] !!}</span>
                    </div>

                    <h6 class="text-white-50 text-uppercase fw-bold small mt-4 mb-3">Amenities</h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @php
$amenities = [
                            'has_pa_system'  => ['bi-speaker',         'PA System'],
                            'has_projector'  => ['bi-display',         'Screen'],
                            'has_ac'         => ['bi-thermometer-snow','AC'],
                            'has_wifi'       => ['bi-wifi',            'WiFi'],
                            'has_whiteboard' => ['bi-easel',           'Whiteboard'],
                            'has_parking'    => ['bi-p-circle',        'Parking'],
                        ];
                        foreach ($amenities as $key => [$icon, $label]):
                            if ($viewHall[$key]):
@endphp
                        <span class="amenity-tag" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.7)">
                            <i class="bi {!! $icon !!}"></i> {!! $label !!}                        </span>
                        @endif @endforeach
                    </div>

                    <button class="btn btn-ceac-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-2"></i>Book This Hall
                    </button>
                    <div class="mt-3 text-center">
                        <small class="text-white-50">
                            <i class="bi bi-shield-check me-1"></i>All bookings are reviewed &amp; confirmed by our admin team
                        </small>
                    </div>
                </div>

                <!-- Back to all halls -->
                <div class="mt-3 text-center">
                    <a href="{{ base_url() }}/halls" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>View All Halls
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Policy snippet -->
<section class="section-pad-sm bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-animate>
                <span class="section-badge"><i class="bi bi-shield-check"></i> Booking Policy</span>
                <h3 class="section-title">Quick Booking Guide</h3>
                <div class="divider-ceac divider-center mb-4"></div>
                <div class="row g-3 text-start">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-4" style="border-color:var(--ceac-orange)!important">
                            <i class="bi bi-clock-fill text-ceac-orange me-2"></i><strong>Book at least 48 hours in advance</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-4" style="border-color:var(--ceac-orange)!important">
                            <i class="bi bi-shield-check text-ceac-orange me-2"></i><strong>Bookings require admin approval</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-4" style="border-color:var(--ceac-orange)!important">
                            <i class="bi bi-people-fill text-ceac-orange me-2"></i><strong>Max {!! $viewHall['capacity'] !!} attendees for this hall</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-4" style="border-color:var(--ceac-orange)!important">
                            <i class="bi bi-file-text text-ceac-orange me-2"></i><strong>Events must align with CEAC values</strong>
                        </div>
                    </div>
                </div>
                <a href="{{ base_url() }}/policy" class="btn btn-ceac-outline mt-4">
                    <i class="bi bi-book me-1"></i>Read Full Policy &amp; Guidelines
                </a>
            </div>
        </div>
    </div>
</section>

@else
<!-- ═══════════════════════════════════════════════════════════ -->
<!-- ── ALL HALLS LISTING ─────────────────────────────────────── -->
<!-- ═══════════════════════════════════════════════════════════ -->
<section class="section-pad-sm bg-light" aria-label="Hall Listing">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-building"></i> Available Venues</span>
            <h2 class="section-title">Our Halls for Booking</h2>
            <div class="divider-ceac divider-center"></div>
            <p class="section-subtitle">
                {!! count($halls) !!} hall{!! count($halls) !== 1 ? 's' : '' !!} available at Christ Embassy Airport City
            </p>
        </div>

        @if (empty($halls))
        <div class="text-center py-5" data-animate>
            <i class="bi bi-building-x fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No halls currently available</h5>
            <p class="text-muted">Please contact us directly for bookings.</p>
            <a href="{{ base_url() }}/contact" class="btn btn-ceac-primary">
                <i class="bi bi-envelope me-1"></i>Contact Us
            </a>
        </div>
        @else
        <div class="row g-4 justify-content-center">
            @foreach ($halls as $i => $hall)
            <div class="col-lg-6 col-md-8" data-animate style="animation-delay:{!! $i * 0.1 !!}s">
                <div class="hall-card h-100">
                    <div class="hall-img-wrap">
                        <img src="{!! getHallImage($hall) !!}"
                             alt="{{ $hall['name'] }}" loading="lazy">
                        <div class="hall-capacity-badge">
                            <i class="bi bi-people-fill me-1"></i>{!! $hall['capacity'] !!} pax
                        </div>
                        <div class="hall-status-dot">Available</div>
                    </div>
                    <div class="hall-body">
                        <h3 class="hall-title">{{ $hall['name'] }}</h3>
                        @if (!empty($hall['description']))
                        <p class="hall-desc">
                            {{ substr($hall['description'], 0, 180) }}…
                        </p>
                        @endif

                        <!-- Amenity tags -->
                        <div class="hall-amenities">
                            @if ($hall['has_pa_system'])
                            <span class="amenity-tag"><i class="bi bi-speaker"></i> PA System</span>
                            @endif
                            @if ($hall['has_projector'])
                            <span class="amenity-tag"><i class="bi bi-display"></i> Display Screen</span>
                            @endif
                            @if ($hall['has_wifi'])
                            <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                            @endif
                            @if ($hall['has_ac'])
                            <span class="amenity-tag"><i class="bi bi-thermometer-snow"></i> AC</span>
                            @endif
                            @if ($hall['has_whiteboard'])
                            <span class="amenity-tag"><i class="bi bi-easel"></i> Whiteboard</span>
                            @endif
                            @if ($hall['has_parking'])
                            <span class="amenity-tag"><i class="bi bi-p-circle"></i> Parking</span>
                            @endif
                        </div>

                        <!-- Mini stats -->
                        <div class="row g-2 mt-3 mb-3">
                            <div class="col-4 text-center p-2 bg-light rounded">
                                <div class="fw-bold text-ceac-orange">{!! $hall['capacity'] !!}</div>
                                <div style="font-size:0.7rem;" class="text-muted">Capacity</div>
                            </div>
                            <div class="col-4 text-center p-2 bg-light rounded">
                                <div class="fw-bold text-success">{!! $hall['approved_count'] !!}</div>
                                <div style="font-size:0.7rem;" class="text-muted">Events Held</div>
                            </div>
                            <div class="col-4 text-center p-2 bg-light rounded">
                                <div class="fw-bold">{!! $hall['this_month'] !!}</div>
                                <div style="font-size:0.7rem;" class="text-muted">This Month</div>
                            </div>
                        </div>
                    </div>

                    <div class="hall-footer">
                        <a href="{{ base_url() }}/halls?id={!! $hall['id'] !!}"
                           class="btn btn-ceac-outline flex-grow-1">
                            <i class="bi bi-info-circle me-1"></i>View Details
                        </a>
                        <button class="btn btn-ceac-primary flex-grow-1"
                                data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-plus me-1"></i>Book
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- CTA -->
<section class="section-pad-sm" style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue))">
    <div class="container text-center text-white" data-animate>
        <h3 class="fw-bold mb-3">Ready to Book a Hall?</h3>
        <p class="text-white-50 mb-4">Submit your request and our admin team will confirm within 24 hours.</p>
        <button class="btn btn-ceac-primary btn-lg me-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
            <i class="bi bi-calendar-plus me-2"></i>Book Now
        </button>
        <a href="{{ base_url() }}/contact" class="btn btn-outline-light btn-lg">
            <i class="bi bi-envelope me-2"></i>Contact Us
        </a>
    </div>
</section>

@endif
@endsection
