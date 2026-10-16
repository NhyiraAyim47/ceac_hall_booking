@extends('layouts.app')

@section('content')
@php
    // Structured data for search engines (one entry per event)
    $eventSchemaList = [];
    foreach ($events as $ev) {
        if (empty($ev['start_datetime'])) continue;
        $eventSchemaList[] = [
            "@type"       => "Event",
            "name"        => $ev['title'],
            "description" => strip_tags($ev['description'] ?? ''),
            "startDate"   => date('c', strtotime($ev['start_datetime'])),
            "endDate"     => !empty($ev['end_datetime']) ? date('c', strtotime($ev['end_datetime'])) : null,
            "eventStatus" => "https://schema.org/EventScheduled",
            "eventAttendanceMode" => "https://schema.org/OfflineEventAttendanceMode",
            "location"    => [
                "@type"   => "Place",
                "name"    => $ev['hall_name'] ?? "CEAC Hall",
                "address" => [
                    "@type"           => "PostalAddress",
                    "streetAddress"   => "10 Blackberry Street, Estate Legon",
                    "addressLocality" => "Accra",
                    "addressCountry"  => "GH"
                ]
            ],
            "organizer"   => [
                "@type" => "Organization",
                "name"  => $ev['organizer'] ?: "Christ Embassy Airport City"
            ],
        ];
    }

    $eventConfig = [
        'meetings' => ['color'=>'#2563EB','bg'=>'rgba(37,99,235,0.08)','icon'=>'people-fill','label'=>'Meeting'],
    ];
@endphp

<!-- ── PAGE HERO ─────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ base_url() }}/">Home</a></li>
                <li class="breadcrumb-item active">Events</li>
            </ol>
        </nav>
        <h1><i class="bi bi-calendar-event me-2 text-ceac-orange"></i>Events at CEAC Hall</h1>
        <p class="text-white-50">Regular and special gatherings at Christ Embassy Airport City, Accra Ghana</p>
    </div>
</section>

<!-- ── EVENT STATS ───────────────────────────────────────── -->
<section class="stat-counter py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! count($events) !!}">{!! count($events) !!}</div>
                    <div class="stat-label">Total Events</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! $upcomingCount !!}">{!! $upcomingCount !!}</div>
                    <div class="stat-label">Upcoming Events</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! $completedCount !!}">{!! $completedCount !!}</div>
                    <div class="stat-label">Completed Events</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-animate>
                <div class="stat-item">
                    <div class="stat-number" data-count="{!! $recurringCount !!}">{!! $recurringCount !!}</div>
                    <div class="stat-label">Recurring Events</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FILTER TABS ───────────────────────────────────────── -->
<section class="section-pad" aria-label="Events List">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-calendar-event"></i> Our Events</span>
            <h2 class="section-title">Events Held at CEAC Hall</h2>
            <div class="divider-ceac divider-center"></div>
        </div>

        <!-- Filter -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-animate>
            <button class="btn btn-sm btn-ceac-primary event-filter active" data-filter="all">All Events</button>
        </div>

        <!-- Events Grid -->
        <div class="row g-4" id="eventsGrid">
            @php
foreach ($events as $i => $event):
                $ec = $eventConfig['meetings'];
@endphp
            <div class="col-lg-6 event-item" data-type="{!! $event['event_type'] !!}" data-animate style="animation-delay:{!! ($i % 4) * 0.1 !!}s">
                <div class="event-card h-100">
                    <div class="d-flex align-items-start gap-4">
                        <div class="event-icon-wrap flex-shrink-0" style="background:{!! $ec['bg'] !!};width:64px;height:64px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;">
                            <i class="bi bi-{!! $ec['icon'] !!}" style="color:{!! $ec['color'] !!}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="event-type-badge" style="background:{!! $ec['bg'] !!};color:{!! $ec['color'] !!}">
                                    <i class="bi bi-{!! $ec['icon'] !!}"></i> {!! $ec['label'] !!}                                </span>
                                @if ($event['status'] === 'upcoming')
                                <span class="event-type-badge" style="background:rgba(16,185,129,0.1);color:#047857;"><i class="bi bi-circle-fill" style="font-size:0.5rem;vertical-align:middle;"></i> Upcoming</span>
                                @elseif ($event['status'] === 'completed')
                                <span class="event-type-badge" style="background:rgba(107,114,128,0.1);color:#374151;"><i class="bi bi-check-all"></i> Completed</span>
                                @elseif ($event['status'] === 'cancelled')
                                <span class="event-type-badge" style="background:rgba(239,68,68,0.1);color:#991B1B;"><i class="bi bi-x-circle"></i> Cancelled</span>
                                @endif
                                @if ($event['is_recurring'])
                                <span class="event-recurring"><i class="bi bi-arrow-repeat me-1"></i>Recurring &bull; {!! ucfirst($event['recurrence_pattern'] ?? '') !!}</span>
                                @endif
                            </div>
                            <h4 class="fw-bold mb-2">{{ $event['title'] }}</h4>
                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-3" style="font-size:0.92rem;line-height:1.7;">{{ $event['description'] }}</p>

                    <!-- Event Meta -->
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                <i class="bi bi-calendar3 text-ceac-orange"></i>
                                <div>
                                    <div class="small text-muted" style="font-size:0.72rem;">DATE</div>
                                    <div class="small fw-semibold">
                                        @php
$evStartTs = strtotime($event['start_datetime'] ?? '');
                                        echo ($evStartTs && $evStartTs > 0 && date('Y', $evStartTs) > 1)
                                            ? date('D, M j, Y', $evStartTs)
                                            : '—';
@endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                <i class="bi bi-clock text-ceac-orange"></i>
                                <div>
                                    <div class="small text-muted" style="font-size:0.72rem;">TIME</div>
                                    <div class="small fw-semibold">
                                        @php
$evEndTs = strtotime($event['end_datetime'] ?? '');
                                        if ($evStartTs && $evStartTs > 0 && date('Y', $evStartTs) > 1):
                                            echo date('g:i A', $evStartTs);
                                            if ($evEndTs && $evEndTs > 0 && date('Y', $evEndTs) > 1):
                                                echo ' – ' . date('g:i A', $evEndTs);
                                            endif;
                                        else:
                                            echo '—';
                                        endif;
@endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($event['organizer'])
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                <i class="bi bi-person-fill text-ceac-orange"></i>
                                <div>
                                    <div class="small text-muted" style="font-size:0.72rem;">ORGANIZER</div>
                                    <div class="small fw-semibold">{{ $event['organizer'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($event['max_attendees'])
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                <i class="bi bi-people-fill text-ceac-orange"></i>
                                <div>
                                    <div class="small text-muted" style="font-size:0.72rem;">MAX ATTENDEES</div>
                                    <div class="small fw-semibold">{!! $event['max_attendees'] !!} people</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <button class="btn btn-ceac-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-1"></i>Book Hall for Similar Event
                    </button>
                </div>
            </div>
            @endforeach

            @if (empty($events))
            <div class="col-12 text-center py-5">
                <i class="bi bi-calendar-x" style="font-size:3rem;color:#D1D5DB;"></i>
                <h5 class="mt-3 text-muted">No events found</h5>
                <p class="text-muted">Events will appear here as they are added by the admin team.</p>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- ── EVENT TYPES GUIDE ─────────────────────────────────── -->
<section class="section-pad bg-white" aria-label="Event Types">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="section-badge"><i class="bi bi-info-circle"></i> Event Guide</span>
            <h2 class="section-title">Our Regular Gatherings</h2>
            <div class="divider-ceac divider-center"></div>
        </div>

        <div class="row g-4 justify-content-center">
            @php
$eventGuide = [
                ['anchor'=>'meetings','icon'=>'people-fill','color'=>'#2563EB','title'=>'Meetings','freq'=>'Regular','desc'=>'The CEAC Hall hosts a wide range of meetings — from intimate cell gatherings and congregational fellowship to executive planning sessions and all-night services. Whatever the size, purpose, or time of your gathering, the hall is ready to host it. Submit a booking request and our admin team will confirm your reservation within 24 hours.'],
            ];
            foreach ($eventGuide as $eg):
@endphp
            <div class="col-lg-8" data-animate id="{!! $eg['anchor'] !!}">
                <div class="p-4 rounded-3 border h-100" style="border-color:rgba({!! implode(',',sscanf($eg['color'],'#%02x%02x%02x')) !!},0.2)!important;">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <div style="width:52px;height:52px;background:rgba({!! implode(',',sscanf($eg['color'],'#%02x%02x%02x')) !!},0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">
                            <i class="bi bi-{!! $eg['icon'] !!}" style="color:{!! $eg['color'] !!}"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{!! $eg['title'] !!}</h5>
                            <span class="badge" style="background:rgba({!! implode(',',sscanf($eg['color'],'#%02x%02x%02x')) !!},0.1);color:{!! $eg['color'] !!};font-size:0.72rem;">
                                <i class="bi bi-arrow-repeat me-1"></i>{!! $eg['freq'] !!}                            </span>
                        </div>
                    </div>
                    <p class="text-muted" style="font-size:0.92rem;line-height:1.7;">{!! $eg['desc'] !!}</p>
                    <button class="btn btn-sm btn-ceac-outline" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-1"></i>Book for this event type
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── BOOKING CTA ───────────────────────────────────────── -->
<section class="section-pad-sm" style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue))">
    <div class="container text-center text-white" data-animate>
        <h3 class="fw-bold mb-3">Planning an Event at CEAC?</h3>
        <p class="text-white-50 mb-4">Submit your booking request and our admin team will confirm your reservation within 24 hours.</p>
        <button class="btn btn-ceac-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal">
            <i class="bi bi-calendar-plus me-2"></i>Book a Hall
        </button>
    </div>
</section>

<script>
// Filter events
document.querySelectorAll('.event-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.event-filter').forEach(b => {
            b.classList.remove('active', 'btn-ceac-primary');
            b.classList.add('btn-ceac-outline');
        });
        this.classList.add('active', 'btn-ceac-primary');
        this.classList.remove('btn-ceac-outline');
        const filter = this.dataset.filter;
        document.querySelectorAll('.event-item').forEach(item => {
            if (filter === 'all' || item.dataset.type === filter) {
                item.style.display = '';
                item.style.animation = 'fadeInUp 0.4s ease forwards';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

@if (!empty($eventSchemaList))
<script type="application/ld+json">
{!! json_encode(['@' . 'context' => "https://schema.org", "@graph" => $eventSchemaList], JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endsection
