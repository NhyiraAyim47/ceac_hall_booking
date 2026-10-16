// ================================================================
// CEAC Hall Booking – Service Worker (Push Notifications)
// ================================================================

const CACHE_NAME = 'ceac-v1';

self.addEventListener('install', e => self.skipWaiting());
self.addEventListener('activate', e => e.waitUntil(clients.claim()));

// Handle incoming push notifications
self.addEventListener('push', function(event) {
    let data = { title: 'CEAC Hall Booking', body: 'You have a new notification.', icon: '/assets/images/logo.png', badge: '/assets/images/logo.png', tag: 'ceac-notification', url: '/admin' };

    if (event.data) {
        try { data = { ...data, ...event.data.json() }; }
        catch(e) { data.body = event.data.text(); }
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body:    data.body,
            icon:    data.icon,
            badge:   data.badge,
            tag:     data.tag,
            data:    { url: data.url },
            vibrate: [200, 100, 200],
            requireInteraction: true,
            actions: [
                { action: 'view',    title: '👁️ View Now' },
                { action: 'dismiss', title: '✕ Dismiss'   }
            ]
        })
    );
});

// Handle notification click
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    if (event.action === 'dismiss') return;
    const url = event.notification.data?.url || '/admin';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
            for (const client of clientList) {
                if (client.url.includes('/admin') && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});