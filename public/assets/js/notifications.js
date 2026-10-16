// ================================================================
// CEAC Hall Booking – Push Notification Manager
// ================================================================

const CEAC_SW_PATH = window.CEAC_BASE + '/sw.js';

const CEACNotifications = {

    // Check browser support
    isSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    },

    // Request permission and subscribe
    async subscribe() {
        if (!this.isSupported()) {
            showToast('Push notifications are not supported in this browser.', 'warning');
            return false;
        }

        // Request permission
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            showToast('Notification permission denied. Please allow notifications in your browser settings.', 'warning');
            return false;
        }

        try {
            // Register service worker
            const reg = await navigator.serviceWorker.register(CEAC_SW_PATH, { scope: window.CEAC_BASE + '/' });
            await navigator.serviceWorker.ready;

            // Subscribe to push
            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(window.CEAC_VAPID_PUBLIC || '')
            });

            // Save subscription to server
            const resp = await fetch(window.CEAC_BASE + '/api/push-subscription', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ subscription: sub.toJSON(), _token: window.CEAC_CSRF })
            });
            const data = await resp.json();

            if (data.success) {
                this.updateUI(true);
                showToast('Push notifications enabled! You will be notified of new bookings.', 'success');
                return true;
            }
        } catch(err) {
            console.warn('Push subscription error:', err);
            this.startPolling();
            showToast('In-app notifications are active. You will be alerted of new bookings.', 'success');
            this.updateUI(true);
        }
        return false;
    },

    // Unsubscribe
    async unsubscribe() {
        try {
            const reg = await navigator.serviceWorker.getRegistration(CEAC_SW_PATH);
            if (reg) {
                const sub = await reg.pushManager.getSubscription();
                if (sub) await sub.unsubscribe();
            }
            this.updateUI(false);
            showToast('Push notifications disabled.', 'info');
        } catch(e) {}
    },

    // Check current subscription state
    async checkState() {
        if (!this.isSupported()) return false;
        try {
            const reg = await navigator.serviceWorker.getRegistration(CEAC_SW_PATH);
            if (!reg) return false;
            const sub = await reg.pushManager.getSubscription();
            return !!sub;
        } catch(e) { return false; }
    },

    // Update UI bell icon state
    updateUI(subscribed) {
        const btn = document.getElementById('notifToggleBtn');
        const icon = document.getElementById('notifBellIcon');
        const text = document.getElementById('notifBtnText');
        if (!btn) return;
        if (subscribed) {
            icon && (icon.className = 'bi bi-bell-fill text-warning');
            text && (text.textContent = 'Notifications On');
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-warning');
        } else {
            icon && (icon.className = 'bi bi-bell');
            text && (text.textContent = 'Enable Notifications');
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-outline-secondary');
        }
    },

    // ── IN-APP POLLING (fallback for localhost/HTTP) ───────────
    _lastCount: null,
    _pollTimer: null,

    startPolling(intervalMs = 30000) {
        if (this._pollTimer) return;
        this._pollTimer = setInterval(() => this.poll(), intervalMs);
        this.poll(); // immediate first check
    },

    stopPolling() {
        if (this._pollTimer) { clearInterval(this._pollTimer); this._pollTimer = null; }
    },

    async poll() {
        try {
            const r = await fetch(window.CEAC_BASE + '/api/stats');
            const data = await r.json();
            const pending = parseInt(data.pending || 0);

            // Update badge in topbar
            const badge = document.getElementById('pendingBadge');
            if (badge) badge.textContent = pending > 0 ? pending : '';

            // Show in-app toast for NEW pending bookings
            if (this._lastCount !== null && pending > this._lastCount) {
                const diff = pending - this._lastCount;
                this.showInAppAlert(`${diff} new booking request${diff>1?'s':''} received!`, 'admin/bookings?status=pending');
            }
            this._lastCount = pending;

        } catch(e) {}
    },

    showInAppAlert(message, link) {
        // Show a prominent in-app notification bar
        const existing = document.getElementById('inAppNotifBar');
        if (existing) existing.remove();

        const bar = document.createElement('div');
        bar.id = 'inAppNotifBar';
        bar.style.cssText = `
            position:fixed;top:70px;right:16px;z-index:9999;
            background:linear-gradient(135deg,#FF6B00,#CC5500);
            color:white;border-radius:12px;padding:14px 20px;
            box-shadow:0 8px 32px rgba(255,107,0,0.4);
            max-width:320px;animation:slideInRight 0.4s ease;
        `;
        bar.innerHTML = `
            <div style="display:flex;align-items:center;gap:10px;">
                <i class="bi bi-bell-fill" style="font-size:1.3rem;flex-shrink:0;"></i>
                <div style="flex-grow:1;">
                    <strong style="display:block;font-size:0.9rem;">New Booking!</strong>
                    <span style="font-size:0.82rem;opacity:0.9;">${message}</span>
                </div>
                <button onclick="document.getElementById('inAppNotifBar').remove()"
                        style="background:rgba(255,255,255,0.2);border:none;color:white;border-radius:50%;width:24px;height:24px;cursor:pointer;flex-shrink:0;">✕</button>
            </div>
            <a href="${window.CEAC_BASE}/${link}" style="display:block;margin-top:10px;background:rgba(255,255,255,0.15);color:white;text-decoration:none;padding:6px 12px;border-radius:6px;text-align:center;font-size:0.82rem;font-weight:600;">
                View Booking Requests →
            </a>`;

        document.body.appendChild(bar);

        // Also show browser Notification if permitted
        if (Notification.permission === 'granted') {
            new Notification('CEAC Hall Booking', {
                body: message,
                icon: window.CEAC_BASE + '/assets/images/logo.png',
                tag: 'ceac-new-booking'
            });
        }

        // Auto dismiss after 12 seconds
        setTimeout(() => bar && bar.remove(), 12000);
    },

    // Helper: convert VAPID key
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    }
};

// Auto-init on admin pages
document.addEventListener('DOMContentLoaded', async () => {
    if (!window.CEAC_BASE) return;

    // Start in-app polling immediately (works on localhost without HTTPS)
    CEACNotifications.startPolling(30000);

    // Check if already subscribed and update UI
    const subscribed = await CEACNotifications.checkState();
    CEACNotifications.updateUI(subscribed);

    // Restore notification state from localStorage
    const notifEnabled = localStorage.getItem('ceac_notif_enabled') !== 'false';
    if (notifEnabled) {
        CEACNotifications.startPolling(30000);
        CEACNotifications.updateUI(true);
    }

    const btn = document.getElementById('notifToggleBtn');
    if (btn) {
        btn.addEventListener('click', function () {
            if (CEACNotifications._pollTimer) {
                CEACNotifications.stopPolling();
                CEACNotifications.updateUI(false);
                localStorage.setItem('ceac_notif_enabled', 'false');
                showToast('In-app notifications paused.', 'info');
            } else {
                CEACNotifications.startPolling(30000);
                CEACNotifications.updateUI(true);
                localStorage.setItem('ceac_notif_enabled', 'true');
                showToast('In-app notifications active.', 'success');
            }
        });
    }

// CSS for slide-in animation
const style = document.createElement('style');
style.textContent = `
@keyframes slideInRight {
    from { opacity:0; transform:translateX(40px); }
    to   { opacity:1; transform:translateX(0); }
}`;
document.head.appendChild(style);
 });