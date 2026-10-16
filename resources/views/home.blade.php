@extends('layouts.app')

@section('content')
<!-- ── HERO ──────────────────────────────────────────────── -->
<section class="hero-section" aria-label="Hero">
    <div class="hero-bg-pattern"></div>
    <div class="hero-floating-orb hero-orb-1"></div>
    <div class="hero-floating-orb hero-orb-2"></div>

    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-content">
                <p class="hero-eyebrow"><i class="bi bi-geo-alt me-1"></i>Airport City Zone &bull; Accra, Ghana</p>
                <h1 class="hero-title">
                    Your Kingdom<br>
                    Events, <span class="highlight">Perfectly Hosted</span>
                </h1>
                <p class="hero-desc">
                    The CEAC Hall at Christ Embassy Airport City is your dedicated space for cell meetings, fellowship gatherings, executive sessions, and powerful all-night services — all in one inspiring venue.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <button class="btn btn-ceac-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-2"></i>Book the Hall Now
                    </button>
                    <a href="{{ base_url() }}/halls" class="btn btn-ceac-outline btn-lg">
                        <i class="bi bi-eye me-2"></i>View Hall Details
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat" data-animate>
                        <div class="hero-stat-number" data-stat="total_bookings" data-count="{!! $stats['total_bookings'] !!}">{!! $stats['total_bookings'] !!}</div>
                        <div class="hero-stat-label">Total Bookings</div>
                    </div>
                    <div class="hero-stat" data-animate>
                        <div class="hero-stat-number" data-stat="approved" data-count="{!! $stats['approved'] !!}">{!! $stats['approved'] !!}</div>
                        <div class="hero-stat-label">Approved</div>
                    </div>
                    <div class="hero-stat" data-animate>
                        <div class="hero-stat-number" data-count="{!! $hall ? $hall['capacity'] : 50 !!}">{!! $hall ? $hall['capacity'] : 50 !!}</div>
                        <div class="hero-stat-label">Hall Capacity</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hero-image-area">
                <div class="hero-card position-relative">
                    <img src="{{ base_url() }}/assets/images/hall1.jpg" alt="CEAC Hall Interior" class="hero-card-img" loading="eager">
                    <div class="hero-badge-float hero-badge-1">
                        <i class="bi bi-people-fill"></i> 40-Seat Capacity
                    </div>
                    <div class="hero-badge-float hero-badge-2">
                        <i class="bi bi-check-circle-fill"></i> Now Accepting Bookings
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SERVICES ───────────────────────────────────────────── -->
<section class="section-pad bg-white" aria-label="Our Services">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-grid-3x3-gap"></i> What We Offer</span>
            <h2 class="section-title">Serving the Kingdom<br>with Excellence</h2>
            <div class="divider-ceac divider-center"></div>
            <p class="section-subtitle">The CEAC Hall is purpose-built to support every facet of the Christ Embassy Airport City ministry.</p>
        </div>

        <div class="row g-4">
            @php
$services = [
                ['icon'=>'calendar-check','title'=>'Easy Hall Booking','desc'=>'Book the CEAC Hall in minutes through our secure online system. Receive instant confirmation and a unique booking reference.'],
                ['icon'=>'people-fill','title'=>'Cell Meetings','desc'=>'A warm, intimate space designed for cell group gatherings — fostering spiritual growth and deep community connections.'],
                ['icon'=>'broadcast','title'=>'Fellowship Meetings','desc'=>'Corporate worship and teaching sessions held in the CEAC Hall, bringing the whole congregation together in faith.'],
                ['icon'=>'briefcase-fill','title'=>'Executive Meetings','desc'=>'Professional, focused meeting space for leadership sessions, ministry strategy, and church administration gatherings.'],
                ['icon'=>'moon-stars-fill','title'=>'All Night Services','desc'=>'An inspiring overnight setting for powerful prayer, praise, and encounters with the Holy Spirit through the night.'],
                ['icon'=>'shield-check-fill','title'=>'Secure & Transparent','desc'=>'Every booking is tracked and managed with full transparency. Admins review and approve bookings to maintain hall integrity.'],
            ];
            foreach ($services as $i => $s):
@endphp
            <div class="col-lg-4 col-md-6" data-animate style="animation-delay:{!! $i * 0.08 !!}s">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-{!! $s['icon'] !!}"></i></div>
                    <h5>{!! $s['title'] !!}</h5>
                    <p>{!! $s['desc'] !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── STATS COUNTER ──────────────────────────────────────── -->
<section class="stat-counter" aria-label="Statistics">
    <div class="container">
        <div class="row g-4 text-center">
            @php
$hallCapacityValue = $hall ? $hall['capacity'] : null;
            $statItems = [
                ['value'=>$stats['total_bookings'],'label'=>'Total Bookings','icon'=>'calendar-check'],
                ['value'=>$stats['approved'],'label'=>'Approved Bookings','icon'=>'check-circle-fill'],
                ['value'=>$stats['upcoming_events'],'label'=>'Upcoming Events','icon'=>'calendar-event'],
                ['value'=>$hallCapacityValue,'label'=>'Hall Capacity','icon'=>'people-fill'],
            ];
            foreach ($statItems as $s):
@endphp
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="mb-2"><i class="bi bi-{!! $s['icon'] !!}" style="font-size:2rem;color:rgba(255,255,255,0.3);"></i></div>
                    @if ($s['value'] === null)
                    <div class="stat-number"><i class="bi bi-dash-circle" style="font-size:1rem;opacity:0.6;"></i></div>
                    <div class="stat-label">Capacity Not Available</div>
                    @else
                    <div class="stat-number" data-count="{!! $s['value'] !!}">{!! $s['value'] !!}</div>
                    <div class="stat-label">{!! $s['label'] !!}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── HALL PREVIEW ───────────────────────────────────────── -->
<section class="section-pad" aria-label="Hall Preview">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-animate>
                <span class="section-badge"><i class="bi bi-building"></i> Our Main Hall</span>
                <h2 class="section-title">CEAC Hall –<br>Your Kingdom Space</h2>
                <div class="divider-ceac"></div>
                <p class="text-muted mb-4">
                    The CEAC Hall is a modern, well-equipped meeting space at the heart of Christ Embassy Airport City. With a capacity of <strong>50 people</strong> in theatre-style arrangement, it is the ideal venue for all your church events.
                </p>
                <div class="feature-check-grid mb-4">
                    @php
$hallFeatures = ['50-Seat Capacity','Theatre-Style Seating','Large Display Screen','Professional PA System','Full Air Conditioning','High-Speed WiFi','Whiteboard Available','On-Site Parking'];
                    foreach ($hallFeatures as $f):
@endphp
                    <div class="feature-check-item">
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <span>{!! $f !!}</span>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ base_url() }}/halls" class="btn btn-ceac-primary"><i class="bi bi-info-circle me-1"></i>Full Hall Details</a>
                    <button class="btn btn-ceac-outline" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="bi bi-calendar-plus me-1"></i>Book Now</button>
                </div>
            </div>
            <div class="col-lg-6" data-animate>
                <div class="position-relative">
                    <div class="gallery-main mb-2">
                        <img src="{{ base_url() }}/assets/images/hall1.jpg" alt="CEAC Hall view 1" id="mainGalleryImg">
                    </div>
                    <div class="gallery-thumbs">
                        <div class="gallery-thumb active">
                            <img src="{{ base_url() }}/assets/images/hall1.jpg" alt="CEAC Hall Interior" onclick="switchGallery(this)">
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ base_url() }}/assets/images/hall2.jpg" alt="CEAC Hall Seating" onclick="switchGallery(this)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── UPCOMING EVENTS ────────────────────────────────────── -->
<section class="section-pad bg-white" aria-label="Events">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-calendar-event"></i> Events</span>
            <h2 class="section-title">Upcoming Events at<br>the CEAC Hall</h2>
            <div class="divider-ceac divider-center"></div>
        </div>

        <div class="row g-4">
            @php
$eventColors = [
                'cell_meeting'       => ['bg'=>'rgba(59,130,246,0.1)',  'color'=>'#2563EB','icon'=>'people-fill'],
                'fellowship_meeting' => ['bg'=>'rgba(16,185,129,0.1)',  'color'=>'#059669','icon'=>'broadcast'],
                'executive_meeting'  => ['bg'=>'rgba(139,92,246,0.1)',  'color'=>'#7C3AED','icon'=>'briefcase-fill'],
                'all_night'          => ['bg'=>'rgba(255,107,0,0.1)',   'color'=>'#C2410C','icon'=>'moon-stars-fill'],
                'special'            => ['bg'=>'rgba(245,158,11,0.1)',  'color'=>'#D97706','icon'=>'star-fill'],
                'other'              => ['bg'=>'rgba(107,114,128,0.1)', 'color'=>'#374151','icon'=>'calendar-event'],
            ];
            // Rotate fallback colors for events of the same type so duplicates look distinct
            $colorPool = [
                ['bg'=>'rgba(59,130,246,0.1)',  'color'=>'#2563EB','icon'=>'people-fill'],
                ['bg'=>'rgba(16,185,129,0.1)',  'color'=>'#059669','icon'=>'broadcast'],
                ['bg'=>'rgba(139,92,246,0.1)',  'color'=>'#7C3AED','icon'=>'briefcase-fill'],
                ['bg'=>'rgba(255,107,0,0.1)',   'color'=>'#C2410C','icon'=>'moon-stars-fill'],
                ['bg'=>'rgba(245,158,11,0.1)',  'color'=>'#D97706','icon'=>'star-fill'],
                ['bg'=>'rgba(14,165,233,0.1)',  'color'=>'#0369A1','icon'=>'calendar-event'],
            ];
            $colorIndex = 0;
            foreach ($upcomingEvents as $event):
                $ec = $eventColors[$event['event_type']] ?? $colorPool[$colorIndex % count($colorPool)];
                $colorIndex++;
@endphp
            <div class="col-lg-6 col-md-6" data-animate>
                <div class="event-card h-100">
                    <div class="event-icon-wrap" style="background:{!! $ec['bg'] !!}">
                        <i class="bi bi-{!! $ec['icon'] !!}" style="color:{!! $ec['color'] !!}"></i>
                    </div>
                    <div class="event-type-badge" style="background:{!! $ec['bg'] !!};color:{!! $ec['color'] !!}">
                        <i class="bi bi-{!! $ec['icon'] !!}"></i>
                        {!! ($event['event_type'] === 'other' && !empty($event['other_event_type']))
                            ? htmlspecialchars($event['other_event_type'])
                            : ucwords(str_replace('_',' ',$event['event_type'])) !!}                    </div>
                    @if ($event['is_recurring'])
                    <div class="event-recurring mb-2"><i class="bi bi-arrow-repeat me-1"></i>Recurring {!! $event['recurrence_pattern'] !!}</div>
                    @endif
                    <h5 class="fw-bold mb-2">{{ $event['title'] }}</h5>
                    <p class="text-muted small mb-3">{{ substr($event['description'], 0, 140) }}…</p>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        @php
$evTs = strtotime($event['start_datetime'] ?? '');
                        $evValid = $evTs && $evTs > 0 && date('Y', $evTs) > 1;
@endphp
                        @if ($evValid)
                        <span><i class="bi bi-clock me-1 text-ceac-orange"></i>{!! date('g:i A', $evTs) !!}</span>
                        <span><i class="bi bi-calendar me-1 text-ceac-orange"></i>{!! date('D, M j, Y', $evTs) !!}</span>
                        @else
                        <span><i class="bi bi-calendar me-1 text-ceac-orange"></i>Date to be announced</span>
                        @endif
                        @if ($event['organizer'])<span><i class="bi bi-person me-1 text-ceac-orange"></i>{{ $event['organizer'] }}</span>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ base_url() }}/events" class="btn btn-ceac-primary btn-lg">
                <i class="bi bi-calendar-event me-2"></i>View All Events
            </a>
        </div>
    </div>
</section>

<!-- ── CTA ───────────────────────────────────────────────── -->
<section class="section-pad" style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue))" aria-label="Call to Action">
    <div class="container text-center text-white">
        <div data-animate>
            <span class="section-badge" style="background:rgba(255,107,0,0.2);border-color:rgba(255,107,0,0.4)">
                <i class="bi bi-calendar-plus"></i> Reserve Your Spot
            </span>
            <h2 class="section-title text-white mt-3">Ready to Book a Hall?</h2>
            <div class="divider-ceac divider-center"></div>
            <p class="text-white-50 mb-5 mx-auto" style="max-width:540px">
                Submit your booking request today and our team will confirm your reservation. For queries, contact us at {{ config('ceac.phone') }}.
            </p>
            <button class="btn btn-ceac-primary btn-lg me-3" data-bs-toggle="modal" data-bs-target="#bookingModal">
                <i class="bi bi-calendar-plus me-2"></i>Book Now
            </button>
            <a href="{{ base_url() }}/contact" class="btn btn-outline-light btn-lg">
                <i class="bi bi-envelope me-2"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<script>
function switchGallery(img) {
    const main = document.getElementById('mainGalleryImg');
    if (!main) return;
    main.style.opacity = '0';
    setTimeout(() => { main.src = img.src; main.style.opacity = '1'; }, 200);
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    img.closest('.gallery-thumb').classList.add('active');
}
main.style.transition = 'opacity 0.3s';
</script>
@endsection
