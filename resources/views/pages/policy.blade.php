@extends('layouts.app')

@section('content')
<!-- ── PAGE HERO ─────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ base_url() }}/">Home</a></li>
                <li class="breadcrumb-item active">Policy & Guidelines</li>
            </ol>
        </nav>
        <h1><i class="bi bi-shield-check me-2 text-ceac-orange"></i>Policy &amp; Guidelines</h1>
        <p class="text-white-50">Upholding the integrity and sanctity of the CEAC Hall</p>
    </div>
</section>

<!-- ── INTRO ─────────────────────────────────────────────── -->
<section class="section-pad-sm bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-animate>
                <span class="section-badge"><i class="bi bi-book"></i> Hall Policy</span>
                <h2 class="section-title">Hall Usage Policy &amp; Guidelines</h2>
                <div class="divider-ceac divider-center mb-4"></div>
                <p class="text-muted">
                    The CEAC Hall is a sacred and purposeful space dedicated to advancing the work of God at Christ Embassy Airport City. These guidelines are established to ensure the hall is used appropriately, maintained with excellence, and available to all ministries in an organised and orderly manner. All users of the CEAC Hall are expected to read, understand, and adhere to these policies.
                </p>
                <p class="text-muted small"><em>Last updated: {!! date('F Y') !!} &bull; Christ Embassy Airport City Zone Leadership</em></p>
            </div>
        </div>
    </div>
</section>

<!-- ── POLICY SECTIONS ───────────────────────────────────── -->
<section class="section-pad" aria-label="Policy Sections">
    <div class="container">
        <div class="row g-0">
            <!-- Sidebar TOC -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="position-sticky" style="top:90px;">
                    <div class="bg-white border rounded-3 p-3 shadow-sm">
                        <h6 class="fw-bold text-ceac-orange mb-3 small text-uppercase letter-spacing-1">Contents</h6>
                        <nav id="policyToc">
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#booking">1. Booking Procedures</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#eligibility">2. Eligibility</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#conduct">3. Hall Conduct</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#facilities">4. Facilities Use</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#cancellation">5. Cancellations</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#prohibited">6. Prohibited Activities</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#liability">7. Liability</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#security">8. Security & Safety</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#media">9. Media & Recording</a>
                            <a class="d-block py-1 px-2 rounded text-muted text-decoration-none small policy-toc-link" href="#enforcement">10. Enforcement</a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Policy Content -->
            <div class="col-lg-9 ps-lg-5">

                <div class="policy-section" id="booking" data-animate>
                    <h4><i class="bi bi-calendar-check me-2 text-ceac-orange"></i>1. Booking Procedures</h4>
                    <p class="text-muted">All requests to use the CEAC Hall must be submitted through the official booking system. The following procedures apply:</p>
                    <ul class="policy-list">
                        <li>All bookings must be submitted via the online booking form at least <strong>48 hours in advance</strong> of the intended event date.</li>
                        <li>Bookings are only confirmed upon written approval from the CEAC Hall Administration via email or phone.</li>
                        <li>Each approved booking receives a unique <strong>booking reference number</strong> which must be presented upon use of the hall.</li>
                        <li>Bookings are made on a first-come, first-served basis. Submitting a request does not guarantee hall availability.</li>
                        <li>The hall administrator reserves the right to reject any booking that does not align with CEAC values or policies.</li>
                        <li>All bookings are personal and non-transferable; only the named booking holder may use the hall for the stated event.</li>
                        <li>The hall may be booked for a maximum of <strong>8 consecutive hours</strong> per single booking.</li>
                    </ul>
                </div>

                <div class="policy-section" id="eligibility" data-animate>
                    <h4><i class="bi bi-person-check me-2 text-ceac-orange"></i>2. Eligibility</h4>
                    <p class="text-muted">The CEAC Hall is primarily reserved for activities of Christ Embassy Airport City and its members.</p>
                    <ul class="policy-list">
                        <li>Priority booking is given to official CEAC ministries, departments, cell leaders, and executive leadership.</li>
                        <li>All individual bookers must be identifiable members or affiliated persons of Christ Embassy Airport City.</li>
                        <li>External organisations may only use the hall upon special approval by the Zone Leadership and must be aligned with the values of Christ Embassy International.</li>
                        <li>The hall may not be booked by anonymous or unverified parties. Valid contact details (name, phone, email) are required at the time of booking.</li>
                        <li>Persons previously penalised for misuse of the hall may be barred from future bookings at the discretion of the administration.</li>
                    </ul>
                </div>

                <div class="policy-section" id="conduct" data-animate>
                    <h4><i class="bi bi-person-standing me-2 text-ceac-orange"></i>3. Hall Conduct</h4>
                    <p class="text-muted">All users of the CEAC Hall are expected to maintain decorum and conduct becoming of a child of God.</p>
                    <ul class="policy-list">
                        <li>Noise levels must be kept at a respectful volume so as not to disturb neighbouring spaces or the broader community.</li>
                        <li>All attendees must dress modestly and in a manner befitting the sanctity of the hall as a church space.</li>
                        <li>The use of profane, abusive, or inappropriate language within the hall premises is strictly forbidden.</li>
                        <li>Children must be supervised by a responsible adult at all times while within the hall.</li>
                        <li>The hall must not be used beyond the approved booking time without prior extension approval from the admin team.</li>
                        <li>All persons in the hall are expected to treat one another with kindness, love, and Christian respect.</li>
                    </ul>
                </div>

                <div class="policy-section" id="facilities" data-animate>
                    <h4><i class="bi bi-tools me-2 text-ceac-orange"></i>4. Facilities Use</h4>
                    <p class="text-muted">Hall facilities must be used responsibly and returned in the same condition as found.</p>
                    <ul class="policy-list">
                        <li>All equipment including the PA system, projector/screen, and whiteboard must be operated with care. Misuse that leads to damage will be the responsibility of the booking party.</li>
                        <li>Air conditioning must be switched off after each session unless approved staff is present.</li>
                        <li>Chairs and tables must be arranged and returned to their original theatre-style configuration after use.</li>
                        <li>All litter must be cleared after use. The hall must be left clean, neat, and orderly for the next scheduled user.</li>
                        <li>No furniture or equipment may be removed from the hall premises without prior written consent from the administration.</li>
                        <li>Any technical faults or damage noticed during use must be immediately reported to the hall admin.</li>
                    </ul>
                </div>

                <div class="policy-section" id="cancellation" data-animate>
                    <h4><i class="bi bi-x-circle me-2 text-ceac-orange"></i>5. Cancellation Policy</h4>
                    <ul class="policy-list">
                        <li>Cancellations must be communicated to the admin team <strong>at least 24 hours</strong> before the scheduled event via phone or email.</li>
                        <li>Failure to cancel in advance and subsequent no-shows may result in restricted booking access for a period of time.</li>
                        <li>The hall administration reserves the right to cancel any approved booking due to unforeseen circumstances, emergency church activities, or maintenance needs. Affected parties will be notified as early as possible.</li>
                        <li>Cancellations can be initiated via email to 
                        <a href='https://mail.google.com/mail/?view=cm&to={!! urlencode(config('ceac.email')) !!}&su={!! urlencode('Hall Booking Cancellation Request') !!}&body={!! urlencode("Dear CEAC Hall Administration,\n\nI would like to request a cancellation for my hall booking.\n\nBooking Reference: \nReason for Cancellation: \n\nThank you.") !!}' target='_blank' rel='noopener noreferrer'>{{ config('ceac.email') }}</a>
                         or by calling <a href="tel:0244036129">024 403 6129</a>.</li>
                    </ul>
                </div>

                <div class="policy-section" id="prohibited" data-animate>
                    <h4><i class="bi bi-slash-circle me-2 text-ceac-orange"></i>6. Prohibited Activities</h4>
                    <p class="text-muted">The following are strictly not permitted within the CEAC Hall at any time:</p>
                    <ul class="policy-list">
                        <li>Consumption of alcohol or any controlled substances within the hall or its immediate premises.</li>
                        <li>Smoking of any kind inside or directly outside the hall entrance.</li>
                        <li>Political campaigning, partisan activities, or events that could compromise the church's neutrality.</li>
                        <li>Commercial events, product launches, or activities designed primarily for profit without explicit approval from leadership.</li>
                        <li>Any event, meeting, or activity that contradicts the beliefs, values, and doctrine of Christ Embassy International.</li>
                        <li>Use of the hall for activities that are illegal under Ghanaian law.</li>
                        <li>Subletting or lending of the hall booking to a third party not included in the original booking form.</li>
                        <li>Use of excessive or strobe lighting effects that may constitute a health hazard.</li>
                    </ul>
                </div>

                <div class="policy-section" id="liability" data-animate>
                    <h4><i class="bi bi-exclamation-triangle me-2 text-ceac-orange"></i>7. Liability</h4>
                    <ul class="policy-list">
                        <li>Christ Embassy Airport City and its hall administration shall not be held liable for personal injury, loss, or damage to personal property during any event held at the CEAC Hall.</li>
                        <li>The booking party assumes full responsibility for all attendees and activities during their reserved time slot.</li>
                        <li>Any damage caused to hall property, equipment, or furniture during a booking will be assessed and the cost of repair or replacement will be borne by the booking party.</li>
                        <li>The CEAC Hall administration shall not be responsible for any disputes, accidents, or incidents arising between event organisers and attendees.</li>
                    </ul>
                </div>

                <div class="policy-section" id="security" data-animate>
                    <h4><i class="bi bi-shield-fill-check me-2 text-ceac-orange"></i>8. Security &amp; Safety</h4>
                    <ul class="policy-list">
                        <li>All fire exits must remain accessible and unobstructed at all times during any event.</li>
                        <li>Emergency exits are clearly marked and must not be tampered with or blocked.</li>
                        <li>In the event of a fire or other emergency, all attendees must evacuate the building immediately following the designated escape routes.</li>
                        <li>The maximum occupancy of the hall is <strong>50 persons</strong>. Exceeding this limit is a breach of safety regulations and will result in immediate cancellation of the event.</li>
                        <li>Hall security access keys must not be duplicated or shared outside of authorised personnel.</li>
                        <li>The booking party is responsible for ensuring that all attendees vacate the premises at the end of the approved booking time.</li>
                    </ul>
                </div>

                <div class="policy-section" id="media" data-animate>
                    <h4><i class="bi bi-camera-video me-2 text-ceac-orange"></i>9. Media &amp; Recording</h4>
                    <ul class="policy-list">
                        <li>Photography and video recording of events for personal or church ministry use is permitted.</li>
                        <li>Live streaming of events conducted at the CEAC Hall must align with Christ Embassy's media policies and standards.</li>
                        <li>Media content featuring the CEAC Hall must not misrepresent the church or portray activities inconsistent with our values.</li>
                        <li>Commercial or professional media productions require prior written consent from CEAC Zone Leadership.</li>
                    </ul>
                </div>

                <div class="policy-section" id="enforcement" data-animate>
                    <h4><i class="bi bi-gavel me-2 text-ceac-orange"></i>10. Enforcement &amp; Compliance</h4>
                    <ul class="policy-list">
                        <li>All users of the CEAC Hall are deemed to have read, understood, and agreed to these policies upon submitting a booking request.</li>
                        <li>Violations of any aspect of this policy may result in immediate termination of the event, revocation of booking privileges, and/or reporting to CEAC Zone Leadership for further action.</li>
                        <li>The hall administration reserves the right to update these policies at any time. Updated versions will be published on this page.</li>
                        <li>For clarifications on any policy item, contact the admin team at 
                            <a href='https://mail.google.com/mail/?view=cm&to={!! urlencode(config('ceac.email')) !!}&su={!! urlencode('Policy Clarification Enquiry') !!}&body={!! urlencode("Dear CEAC Hall Administration,\n\nI would like to request clarification on the following policy item:\n\nPolicy Section: \nMy Question: \n\nThank you.") !!}' target='_blank' rel='noopener noreferrer'>{{ config('ceac.email') }}</a> 
                            or call <a href="tel:0244036129">024 403 6129</a>.</li>
                    </ul>
                </div>

                <!-- Agreement Box -->
                <div class="mt-5 p-4 rounded-3 text-white" style="background:linear-gradient(135deg,var(--ceac-dark),var(--ceac-blue));" data-animate>
                    <h5 class="fw-bold mb-3"><i class="bi bi-check-circle-fill text-ceac-orange me-2"></i>Your Agreement</h5>
                    <p class="text-white-50 mb-4">By submitting a booking request for the CEAC Hall, you confirm that you have read, understood, and agree to comply with all the above policies and guidelines. You also agree that your event will honour God and reflect the values of Christ Embassy Airport City.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <button class="btn btn-ceac-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-plus me-1"></i>I Agree — Book the Hall
                        </button>
                        <a href="{{ base_url() }}/contact" class="btn btn-outline-light">
                            <i class="bi bi-question-circle me-1"></i>Ask a Question
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Highlight active TOC link on scroll
const sections = document.querySelectorAll('.policy-section[id]');
const tocLinks = document.querySelectorAll('.policy-toc-link');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
    tocLinks.forEach(link => {
        link.style.background = link.getAttribute('href') === '#'+current ? 'rgba(255,107,0,0.08)' : '';
        link.style.color = link.getAttribute('href') === '#'+current ? 'var(--ceac-orange)' : '';
        link.style.fontWeight = link.getAttribute('href') === '#'+current ? '600' : '';
    });
}, { passive: true });
</script>
@endsection
