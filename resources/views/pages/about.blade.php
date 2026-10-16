@extends('layouts.app')

@section('content')
<!-- ── PAGE HERO ─────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ base_url() }}/">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
            </ol>
        </nav>
        <h1><i class="bi bi-info-circle me-2 text-ceac-orange"></i>About Us</h1>
        <p class="text-white-50">The story, mission, and heart behind Christ Embassy Airport City</p>
    </div>
</section>

<!-- ── ABOUT INTRO ───────────────────────────────────────── -->
<section class="section-pad bg-white" aria-label="About CEAC">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-animate>
                <span class="section-badge"><i class="bi bi-building-check"></i> Who We Are</span>
                <h2 class="section-title">Christ Embassy<br><span style="color:var(--ceac-orange)">Airport City</span></h2>
                <div class="divider-ceac mb-4"></div>
                <p class="text-muted mb-3">
                    Christ Embassy Airport City (CEAC) is a vibrant and growing local church under the global ministry of Christ Embassy International, founded by Pastor Chris Oyakhilome. Located in the heart of the Airport City Zone in Accra, Ghana, CEAC is a community of believers committed to living and spreading the gospel of Jesus Christ with boldness, love, and excellence.
                </p>
                <p class="text-muted mb-3">
                    The CEAC Hall stands as a physical testament to our commitment to excellence in ministry. It is a dedicated, purpose-built space that serves the entire congregation and its various groups — providing a dignified, equipped, and inspiring environment for the work of the Kingdom.
                </p>
                <p class="text-muted mb-4">
                    As a zone rooted in the principles of Pastor Chris, CEAC is characterized by its love for the Word of God, fervent prayer culture, strong cell system, and active involvement in community impact. Our hall is the hub where all of this kingdom activity converges.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <button class="btn btn-ceac-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-1"></i>Book the Hall
                    </button>
                    <a href="{{ base_url() }}/contact" class="btn btn-ceac-outline">
                        <i class="bi bi-envelope me-1"></i>Contact Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-animate>
                <div class="position-relative">
                    <img src="{{ base_url() }}/assets/images/hall2.jpg" alt="CEAC Hall" class="img-fluid rounded-3 shadow-lg" style="width:100%;object-fit:cover;height:420px;">
                    <div class="position-absolute bottom-0 start-0 end-0 p-4 rounded-3" style="background:linear-gradient(to top, rgba(13,17,23,0.9), transparent);">
                        <div class="text-white">
                            <strong class="d-block">CEAC Hall</strong>
                            <small class="text-white-50">10 Blackberry Street, Estate Legon, Accra</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS ────────────────────────────────────────────── -->
<section class="stat-counter py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! $stats['total_bookings'] !!}">{!! $stats['total_bookings'] !!}</div>
                    <div class="stat-label">Total Hall Bookings</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    @if ($hall)
                    <div class="stat-number" data-count="{!! $hall['capacity'] !!}">{!! $hall['capacity'] !!}</div>
                    <div class="stat-label">Hall Capacity</div>
                    @else
                    <div class="stat-number"><i class="bi bi-dash-circle" style="font-size:1rem;opacity:0.6;"></i></div>
                    <div class="stat-label">Capacity Not Available</div>
                    @endif
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="4">4</div>
                    <div class="stat-label">Regular Event Types</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! $stats['approved'] !!}">{!! $stats['approved'] !!}</div>
                    <div class="stat-label">Approved Events</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── MISSION, VISION, VALUES ──────────────────────────── -->
<section class="section-pad" aria-label="Mission Vision Values">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-compass"></i> Our Foundation</span>
            <h2 class="section-title">Mission, Vision &amp; Values</h2>
            <div class="divider-ceac divider-center"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-animate>
                <div class="mission-card text-center">
                    <div class="mission-icon"><i class="bi bi-bullseye"></i></div>
                    <h5>Our Mission</h5>
                    <p>To take the divine presence of God to every nation of the world and demonstrate the Lordship of Jesus Christ in every facet of life — through the halls, cells, and ministries of Christ Embassy Airport City.</p>
                </div>
            </div>
            <div class="col-md-4" data-animate>
                <div class="mission-card text-center">
                    <div class="mission-icon"><i class="bi bi-eye"></i></div>
                    <h5>Our Vision</h5>
                    <p>To build a world-class, Word-based local church in the Airport City Zone of Accra that is a beacon of excellence, love, and spiritual power — where every member is equipped to be a soul-winner and a kingdom influencer.</p>
                </div>
            </div>
            <div class="col-md-4" data-animate>
                <div class="mission-card text-center">
                    <div class="mission-icon"><i class="bi bi-heart-fill"></i></div>
                    <h5>Our Culture</h5>
                    <p>We are a people of the Word, prayer, fellowship, and service. Every activity at CEAC — from cell meetings to all-night services — is an expression of our identity as the Body of Christ in the earth.</p>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="row g-5 mt-5 align-items-start">
            <div class="col-lg-6" data-animate>
                <h3 class="fw-bold mb-4"><i class="bi bi-gem me-2 text-ceac-orange"></i>Our Core Values</h3>
                <div class="divider-ceac mb-4"></div>
                @php
$values = [
                    ['icon'=>'book-half','title'=>'The Word of God','desc'=>'We are a Word-based ministry. Every decision, teaching, and event at CEAC is grounded in the truth of God\'s Word.'],
                    ['icon'=>'lightning-charge-fill','title'=>'Prayer & Intercession','desc'=>'We believe in the power of prayer. Our all-night services and cell prayer sessions reflect our dependence on God.'],
                    ['icon'=>'people-fill','title'=>'Community & Fellowship','desc'=>'We are stronger together. Our cell structure and fellowship meetings keep our community tightly knit and growing.'],
                    ['icon'=>'trophy-fill','title'=>'Excellence','desc'=>'We pursue excellence in all we do — from the way we maintain our hall to the quality of our ministry events.'],
                    ['icon'=>'broadcast','title'=>'Soul Winning','desc'=>'Our greatest mandate is to win souls. Every event held at the CEAC Hall is an opportunity to reach the lost.'],
                    ['icon'=>'shield-fill-check','title'=>'Integrity','desc'=>'We operate with transparency, honesty, and accountability in all our hall management and church administration.'],
                ];
                foreach ($values as $v):
@endphp
                <div class="value-item" data-animate>
                    <div class="value-icon"><i class="bi bi-{!! $v['icon'] !!}"></i></div>
                    <div>
                        <strong class="d-block mb-1">{!! $v['title'] !!}</strong>
                        <p class="text-muted small mb-0">{!! $v['desc'] !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="col-lg-6" data-animate>
                <h3 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-ceac-orange"></i>Our Journey</h3>
                <div class="divider-ceac mb-4"></div>
                @php
$timeline = [
                    ['year'=>'Foundation','title'=>'Christ Embassy Established','desc'=>'Christ Embassy International was founded by Pastor Chris Oyakhilome with a mandate to spread the gospel globally. CEAC is one of its thriving local expressions in Ghana.'],
                    ['year'=>'Growth','title'=>'Airport City Zone Established','desc'=>'The Airport City Zone was established to serve the growing community of believers in the greater Accra Airport City area, providing a local church home with full-time pastoral care.'],
                    ['year'=>'Development','title'=>'CEAC Hall Commissioned','desc'=>'The CEAC Hall at 10 Blackberry Street, Estate Legon was commissioned — a dedicated, air-conditioned, ' . ($hall ? $hall['capacity'] . '-seat' : '') . ' facility designed for church meetings, cell groups, and executive sessions.'],
                    ['year'=>'Today','title'=>'Thriving Kingdom Community','desc'=>'Today, CEAC continues to grow in strength and impact. Our hall serves as the central meeting point for cells, fellowship, executive meetings, and all-night services, actively advancing the kingdom in Accra and beyond.'],
                ];
                foreach ($timeline as $t):
@endphp
                <div class="timeline-item" data-animate>
                    <div class="timeline-dot">{!! substr($t['year'],0,2) !!}</div>
                    <div class="bg-white border rounded-3 p-3 shadow-sm">
                        <div class="text-ceac-orange small fw-bold mb-1">{!! $t['year'] !!}</div>
                        <h6 class="fw-bold mb-2">{!! $t['title'] !!}</h6>
                        <p class="text-muted small mb-0">{!! $t['desc'] !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ── ACHIEVEMENTS ──────────────────────────────────────── -->
<section class="section-pad bg-white" aria-label="Achievements">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-trophy"></i> Milestones</span>
            <h2 class="section-title">Hall Achievements &amp; Highlights</h2>
            <div class="divider-ceac divider-center"></div>
        </div>
        <div class="row g-4">
            @php
$achievements = [
                ['icon'=>'building-check','title'=>'State-of-the-Art Facility','desc'=>'The CEAC Hall is a modern, well-maintained venue featuring professional-grade equipment, full air conditioning, and a comfortable theatre-style seating arrangement.'],
                ['icon'=>'people-fill','title'=>'Thriving Cell Network','desc'=>'The hall serves as the meeting hub for an active and growing cell network within the Airport City Zone, enabling consistent spiritual growth at every level.'],
                ['icon'=>'calendar-check','title'=>'Consistent Event Record','desc'=>'The CEAC Hall has hosted a consistent calendar of cell meetings, fellowship gatherings, executive sessions, and powerful all-night prayer events throughout the year.'],
                ['icon'=>'globe','title'=>'Part of a Global Movement','desc'=>'As part of Christ Embassy International — one of the largest and fastest-growing ministries in the world — CEAC carries a global mandate and a local impact in Accra.'],
                ['icon'=>'phone-fill','title'=>'Online Booking System','desc'=>'CEAC is among the first church zones in the area to implement a digital hall booking platform, making it easier for members and groups to plan and manage events.'],
                ['icon'=>'shield-fill-check','title'=>'Transparent Administration','desc'=>'All hall bookings and event approvals are managed with full transparency through our admin system, ensuring accountability and efficient scheduling.'],
            ];
            foreach ($achievements as $a):
@endphp
            <div class="col-lg-4 col-md-6" data-animate>
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-{!! $a['icon'] !!}"></i></div>
                    <h5>{!! $a['title'] !!}</h5>
                    <p>{!! $a['desc'] !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── LEADERSHIP NOTE ───────────────────────────────────── -->
<section class="section-pad" style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue))">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white" data-animate>
                <i class="bi bi-quote" style="font-size:3rem;color:var(--ceac-orange);opacity:0.6;"></i>
                <h4 class="fst-italic fw-normal text-white-75 my-4" style="line-height:1.8;">
                    "We are carriers of the divine life of God. Everything we do — from how we worship to how we manage our hall — must reflect the excellence and character of our Lord Jesus Christ."
                </h4>
                <p class="text-white-50">— CEAC Zone Leadership, Christ Embassy Airport City, Accra Ghana</p>
                <div class="mt-4">
                    <button class="btn btn-ceac-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-1"></i>Book a Hall
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
