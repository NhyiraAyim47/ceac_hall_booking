@extends('layouts.app')

@section('content')
<!-- ── PAGE HERO ─────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ base_url() }}/">Home</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
            </ol>
        </nav>
        <h1><i class="bi bi-envelope me-2 text-ceac-orange"></i>Contact Us</h1>
        <p class="text-white-50">Reach the CEAC Hall booking team — we're here to help</p>
    </div>
</section>

<!-- ── CONTACT SECTION ───────────────────────────────────── -->
<section class="section-pad" aria-label="Contact">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5" data-animate>
                <div class="contact-info-card">
                    <span class="section-badge mb-4" style="background:rgba(255,107,0,0.15);border-color:rgba(255,107,0,0.3)">
                        <i class="bi bi-geo-alt"></i> Get in Touch
                    </span>
                    <h3 class="text-white fw-bold mb-2">Reach the Hall<br>Booking Team</h3>
                    <p class="text-white-50 mb-4">For all hall booking inquiries, scheduling, and general questions, contact our administration team using the details below.</p>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contact-label">Physical Address</div>
                            <div class="contact-value">
                                10 Blackberry Street<br>
                                Estate Legon, Accra<br>
                                Ghana
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contact-label">Phone / WhatsApp</div>
                            <div class="contact-value">
                                <a href="tel:0244036129">024 403 6129</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contact-label">Email Address</div>
                            <div class="contact-value">
                                <a href="https://mail.google.com/mail/?view=cm&to={!! urlencode(config('ceac.email')) !!}" target="_blank" rel="noopener noreferrer">{{ config('ceac.email') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="contact-label">Admin Office Hours</div>
                            <div class="contact-value">
                                Monday – Friday: 8:00 AM – 5:00 PM<br>
                                Saturday: 9:00 AM – 2:00 PM<br>
                                Sunday: Available after service
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="contact-label">Zone</div>
                            <div class="contact-value">Airport City Zone &bull; Accra, Ghana</div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-2" style="background:rgba(255,107,0,0.1);border:1px solid rgba(255,107,0,0.2);">
                        <p class="mb-2 text-white small"><i class="bi bi-info-circle me-2 text-ceac-orange"></i><strong>Booking Enquiries:</strong></p>
                        <p class="text-white-50 small mb-0">For hall bookings, please use the <strong class="text-white">Book Now</strong> button or fill the contact form. Our team responds within 24 hours.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-animate>
                <div class="contact-form-card">
                    <span class="section-badge mb-3"><i class="bi bi-chat-dots"></i> Send a Message</span>
                    <h3 class="fw-bold mb-1">Send Us a Message</h3>
                    <p class="text-muted mb-4">Fill the form below and our team will respond to your inquiry within 24 hours.</p>

                    <div id="contactAlert" class="d-none mb-3"></div>

                    <div style="position:relative;">
    				<form id="contactForm" novalidate style="opacity:0.35; pointer-events:none; filter:grayscale(40%);">
                        @csrf                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="full_name" placeholder="Your full name" required minlength="2" maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control" name="phone" placeholder="024 XXX XXXX">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control" name="subject" placeholder="How can we help?" required maxlength="200">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="message" rows="6" placeholder="Type your message here…" required minlength="10" maxlength="2000"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-ceac-primary btn-lg w-100">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>

                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; text-align:center; padding:24px;">
                        <div class="p-4 rounded-3" style="background:rgba(255,255,255,0.97); border:1px solid rgba(255,107,0,0.25); box-shadow:0 4px 20px rgba(0,0,0,0.1); max-width:340px;">
                            <i class="bi bi-tools fs-2 text-ceac-orange mb-2 d-block"></i>
                            <p class="fw-semibold mb-1">Still in development</p>
                            <p class="text-muted small mb-0">
                                Email us directly at
                                <a href="mailto:hallbookingceac@gmail.com" class="text-ceac-orange fw-semibold">hallbookingceac@gmail.com</a>
                            </p>
                        </div>
                    </div>
                </div>

                    <div class="mt-4 pt-4 border-top d-flex flex-wrap gap-3">
                        <a href="tel:0244036129" class="btn btn-outline-secondary flex-grow-1">
                            <i class="bi bi-telephone me-1"></i>Call Us
                        </a>
                        <button class="btn btn-ceac-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-plus me-1"></i>Book the Hall
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── MAP SECTION ───────────────────────────────────────── -->
<section class="section-pad-sm bg-light" aria-label="Location Map">
    <div class="container">
        <div class="text-center mb-4" data-animate>
            <span class="section-badge"><i class="bi bi-geo-alt"></i> Location</span>
            <h3 class="section-title">Find Us</h3>
            <p class="text-muted">10 Blackberry Street, Estate Legon, Accra, Ghana</p>
        </div>

        <div class="map-embed" data-animate>
            <div class="p-3 bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <strong><i class="bi bi-geo-alt-fill text-ceac-orange me-1"></i>CEAC Hall – Christ Embassy Airport City</strong>
                    <div class="small text-muted">10 Blackberry Street, Estate Legon, Accra, Ghana</div>
                </div>
                <a href="{{ config('ceac.map_link') }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-ceac-primary">
                    <i class="bi bi-map me-1"></i>Open in Google Maps
                </a>
            </div>
            <!-- Embedded map iframe using the provided link coordinates (Estate Legon area, Accra) -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.4742019696!2d-0.17419102414997217!3d5.622938032694484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9b9c7c027ebb%3A0x2ee0e1ebf1e3bd4!2sEstate%20Legon%2C%20Accra!5e0!3m2!1sen!2sgh!4v1700000000000!5m2!1sen!2sgh"
                width="100%"
                height="420"
                style="border:0;display:block;"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="CEAC Hall Location Map - Estate Legon, Accra Ghana">
            </iframe>
            <div class="p-3 bg-white border-top text-center">
                <a href="{{ config('ceac.map_link') }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-muted small">
                    <i class="bi bi-box-arrow-up-right me-1"></i>
                    Click to open full directions in Google Maps
                </a>
            </div>
        </div>

        <!-- Direction tips -->
        <div class="row g-3 mt-4">
            <div class="col-md-4" data-animate>
                <div class="d-flex gap-3 p-3 bg-white rounded border align-items-start">
                    <i class="bi bi-bus-front-fill text-ceac-orange fs-4 flex-shrink-0"></i>
                    <div>
                        <strong class="d-block small">By Public Transport</strong>
                        <span class="text-muted small">Take a trotro or bus heading to the Airport City / Legon area and alight at the Estate Legon junction.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-animate>
                <div class="d-flex gap-3 p-3 bg-white rounded border align-items-start">
                    <i class="bi bi-car-front-fill text-ceac-orange fs-4 flex-shrink-0"></i>
                    <div>
                        <strong class="d-block small">By Private Car</strong>
                        <span class="text-muted small">Drive to Estate Legon, off the main Legon-Haatso Road. On-site parking is available at the hall.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-animate>
                <div class="d-flex gap-3 p-3 bg-white rounded border align-items-start">
                    <i class="bi bi-phone-fill text-ceac-orange fs-4 flex-shrink-0"></i>
                    <div>
                        <strong class="d-block small">Need Directions?</strong>
                        <span class="text-muted small">Call <a href="tel:0244036129" class="text-ceac-orange">024 403 6129</a> and our team will guide you to the hall.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
