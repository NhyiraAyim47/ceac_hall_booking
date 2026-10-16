/* ================================================================
   CEAC Hall Booking System – Main JavaScript
   ================================================================ */

'use strict';

// ── Navbar scroll effect ────────────────────────────────────
const mainNav = document.getElementById('mainNav');
if (mainNav) {
    window.addEventListener('scroll', () => {
        mainNav.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });
}

// ── Back to Top ─────────────────────────────────────────────
const backToTop = document.getElementById('backToTop');
if (backToTop) {
    window.addEventListener('scroll', () => {
        backToTop.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// ── Toast Notification System ────────────────────────────────
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };
    const labels = { success: 'Success', error: 'Error', warning: 'Warning', info: 'Info' };

    const id = 'toast-' + Date.now();
    const html = `
        <div id="${id}" class="toast toast-ceac toast-${type} show mb-2" role="alert" aria-live="assertive">
            <div class="toast-body d-flex align-items-start gap-3 p-3">
                <i class="bi ${icons[type]} fs-5 flex-shrink-0 mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">${labels[type]}</strong>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-sm flex-shrink-0" onclick="document.getElementById('${id}').remove()"></button>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);

    setTimeout(() => {
        const el = document.getElementById(id);
        if (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateX(100%)';
            el.style.transition = 'all 0.4s ease';
            setTimeout(() => el.remove(), 400);
        }
    }, duration);
}

// ── Alert Helper (inside modals/forms) ─────────────────────
function showAlert(containerId, message, type = 'danger') {
    const el = document.getElementById(containerId);
    if (!el) return;
    const icons = { success: 'bi-check-circle', danger: 'bi-x-circle', warning: 'bi-exclamation-triangle', info: 'bi-info-circle' };
    el.className = `alert alert-${type} d-flex align-items-center gap-2 animate-fade-up`;
    el.innerHTML = `<i class="bi ${icons[type] || 'bi-info-circle'}"></i><div>${message}</div>`;
}

// ── Booking Form Submission ──────────────────────────────────
const bookingForm = document.getElementById('bookingForm');
const submitBtn   = document.getElementById('submitBooking');

if (bookingForm && submitBtn) {
    submitBtn.addEventListener('click', async () => {
        // Client-side validation
        if (!bookingForm.checkValidity()) {
            bookingForm.classList.add('was-validated');
            showAlert('bookingAlert', 'Please fill in all required fields correctly.', 'warning');
            return;
        }

        // Hall selection validation
        const selectedHall = bookingForm.querySelector('[name="hall_id"]:checked');
        if (!selectedHall) {
            showAlert('bookingAlert', 'Please select a hall before submitting your booking.', 'warning');
            return;
        }

        // Get selected hall capacity dynamically
        const hallCapacity = parseInt(selectedHall.dataset.capacity) || 9999;
        const hallName     = selectedHall.dataset.name || 'the selected hall';

        // Business validation
        const startDatetime = bookingForm.querySelector('[name="start_datetime"]')?.value || '';
        const endDatetime   = bookingForm.querySelector('[name="end_datetime"]')?.value   || '';
        const attendees     = parseInt(bookingForm.querySelector('[name="expected_attendees"]').value);

        if (startDatetime && endDatetime && startDatetime >= endDatetime) {
            showAlert('bookingAlert', 'End date & time must be after start date & time.', 'danger');
            return;
        }
        if (attendees > hallCapacity) {
            showAlert('bookingAlert', `${hallName} has a capacity of ${hallCapacity} people. Please reduce the number of attendees.`, 'danger');
            return;
        }

        // Show loading
        const btnText    = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        document.getElementById('bookingAlert').className = 'd-none';

        try {
            const formData = new FormData(bookingForm);
        const resp = await fetch(window.CEAC_BASE + '/booking/submit', { method: 'POST', body: formData });
            const data = await resp.json();

            if (data.success) {
                bookingForm.reset();
                bookingForm.classList.remove('was-validated');
                showAlert('bookingAlert', `
                    <strong>Booking request submitted!</strong><br>
                    Your reference number is <strong class="text-success">${data.booking_ref}</strong>.<br>
                    We will contact you at <strong>${data.email}</strong> shortly with a confirmation.
                `, 'success');
                showToast('Booking request submitted successfully! Reference: ' + data.booking_ref, 'success', 8000);
                // Update live stats
                updateLiveStats();
            } else {
                showAlert('bookingAlert', data.message || 'Submission failed. Please try again.', 'danger');
                showToast(data.message || 'Booking failed. Please try again.', 'error');
            }
        } catch (e) {
            showAlert('bookingAlert', 'Network error. Please check your connection and try again.', 'danger');
            showToast('Network error. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
        }
    });
}

// ── Contact Form ─────────────────────────────────────────────
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!contactForm.checkValidity()) {
            contactForm.classList.add('was-validated');
            return;
        }
        const btn = contactForm.querySelector('[type="submit"]');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending…';

        try {
            const resp = await fetch(window.CEAC_BASE + '/contact/submit', { method: 'POST', body: new FormData(contactForm) });
            const data = await resp.json();
            if (data.success) {
                showAlert('contactAlert', 'Your message has been sent! We will respond within 24 hours.', 'success');
                contactForm.reset();
                showToast('Message sent successfully!', 'success');
            } else {
                showAlert('contactAlert', data.message || 'Failed to send message. Please try again.', 'danger');
            }
        } catch {
            showAlert('contactAlert', 'Network error. Please try again later.', 'danger');
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    });
}

// ── Counter Animation ─────────────────────────────────────────
function animateCounter(el, target, duration = 2000) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
        start += step;
        if (start >= target) { el.textContent = target; clearInterval(timer); return; }
        el.textContent = Math.floor(start);
    }, 16);
}

function updateLiveStats() {
    fetch(window.CEAC_BASE + '/api/stats')
        .then(r => r.json())
        .then(data => {
            document.querySelectorAll('[data-stat]').forEach(el => {
                const key = el.dataset.stat;
                if (data[key] !== undefined) {
                    el.textContent = data[key];
                }
            });
        })
        .catch(() => {});
}

// ── Intersection Observer (Animate on Scroll) ────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('animated'), i * 100);

            // Counter animation
            const num = entry.target.querySelector('[data-count]');
            if (num) {
                const target = parseInt(num.dataset.count);
                animateCounter(num, target);
            }
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

// ── Gallery Toggle ─────────────────────────────────────────
document.querySelectorAll('.gallery-thumb').forEach(thumb => {
    thumb.addEventListener('click', () => {
        const src = thumb.querySelector('img').src;
        const mainImg = document.querySelector('.gallery-main img');
        if (mainImg) {
            mainImg.style.opacity = '0';
            mainImg.style.transform = 'scale(0.97)';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.transition = 'opacity 0.3s, transform 0.3s';
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 200);
        }
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    });
});

// ── Admin Sidebar Mobile — handled in admin_footer.php IIFE ──
// (removed from here to avoid double-listener conflict)

// ── Form: Real-time hall/time availability check ───────────────
(function () {
    const startInput = document.querySelector('[name="start_datetime"]');
    const endInput    = document.querySelector('[name="end_datetime"]');
    const feedback    = document.getElementById('dateAvailabilityFeedback');
    if (!startInput || !endInput || !feedback) return;

    async function runCheck() {
        const hallRadio = document.querySelector('[name="hall_id"]:checked');
        const start = startInput.value;
        const end   = endInput.value;
        if (!hallRadio || !start || !end) {
            feedback.innerHTML = '';
            return;
        }
        try {
            const params = new URLSearchParams({ hall_id: hallRadio.value, start_datetime: start, end_datetime: end });
            const r = await fetch(`${window.CEAC_BASE}/api/check-availability?${params}`);
            const d = await r.json();
            if (!d.checked) { feedback.innerHTML = ''; return; }
            if (d.available) {
                feedback.className = 'form-text text-success';
                feedback.innerHTML = '<i class="bi bi-check-circle me-1"></i>This hall is available for the selected time.';
            } else {
                feedback.className = 'form-text text-danger';
                feedback.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>This hall is already booked during part of this time. Please choose a different time or hall.';
            }
        } catch {}
    }

    startInput.addEventListener('change', runCheck);
    endInput.addEventListener('change', runCheck);
    document.querySelectorAll('[name="hall_id"]').forEach(r => r.addEventListener('change', runCheck));
})();

// ── Smooth Scroll for Anchor Links ──────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            const offset = 80;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    });
});

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    updateLiveStats();

    // Animate hero elements on load
    document.querySelectorAll('.hero-content > *').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 200 + i * 120);
    });
});