/* public/sw.js */

// Handle push event
self.addEventListener("push", (event) => {
    let data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        console.error("Push event data error:", e);
    }

    const title = data.title || "NEW ANNOUNCEMENT";
    const options = {
        body: data.body || "",
        tag: data.tag || "announcement",
        renotify: true,
        badge: "assetsDashboard/img/icons/badge.png",
        vibrate: data.vibrate || [100, 50, 100],
        icon: data.icon || "assetsDashboard/img/icons/announcement.png",
        data: {
            url: data.url || "/",
            id: data.id || null,
        },
        actions: [
            {
                action: "open",
                title: "Open",
            },
        ],
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Handle notification click
self.addEventListener("notificationclick", (event) => {
    event.notification.close();
    const targetUrl = event.notification?.data?.url || "/";

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientsArr) => {
                for (const client of clientsArr) {
                    if (client.url === targetUrl && "focus" in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});
