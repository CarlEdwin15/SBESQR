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
        badge: "/assetsDashboard/img/icons/badge.png",
        vibrate: data.vibrate || [100, 50, 100],
        icon: "/assetsDashboard/img/icons/announcement.png",
        data: {
            url: data.url || "/",
            id: data.id || null,
        },
        actions: [
            {
                action: "open",
                title: "View Announcement",
            },
        ],
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Handle notification click
self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || "/";
    const announcementId = event.notification.data?.id;

    console.log("🔔 Notification clicked:", {
        targetUrl: targetUrl,
        announcementId: announcementId,
    });

    event.waitUntil(
        clients
            .matchAll({
                type: "window",
                includeUncontrolled: true,
            })
            .then((windowClients) => {
                console.log("Found window clients:", windowClients.length);

                // Check if there's already a window/tab open with our domain
                const sameOriginClient = windowClients.find((client) => {
                    const clientUrl = new URL(client.url);
                    const targetUrlObj = new URL(targetUrl);
                    return clientUrl.origin === targetUrlObj.origin;
                });

                if (sameOriginClient) {
                    console.log(
                        "✅ Found existing window with same origin, focusing it"
                    );
                    // Focus the existing window and navigate to the target URL
                    return sameOriginClient
                        .navigate(targetUrl)
                        .then(() => sameOriginClient.focus())
                        .catch((err) => {
                            console.error(
                                "Error navigating existing window:",
                                err
                            );
                            // Fallback: open new window
                            return clients.openWindow(targetUrl);
                        });
                } else {
                    console.log(
                        "❌ No existing window found, opening new window"
                    );
                    // No existing window, open a new one
                    return clients.openWindow(targetUrl);
                }
            })
    );
});
