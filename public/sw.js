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
            },
        ],
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Handle notification click
self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    let targetUrl = event.notification.data?.url || "/";
    const announcementId = event.notification.data?.id;

    console.log("🔔 Notification clicked:", {
        targetUrl: targetUrl,
        announcementId: announcementId,
    });

    event.waitUntil(handleNotificationClick(targetUrl));
});

async function handleNotificationClick(targetUrl) {
    try {
        // Ensure targetUrl is a full URL
        if (!targetUrl.startsWith("http")) {
            targetUrl = self.location.origin + targetUrl;
        }

        console.log("Final target URL:", targetUrl);

        // Get all window clients
        const windowClients = await clients.matchAll({
            type: "window",
            includeUncontrolled: true,
        });

        console.log("Found window clients:", windowClients.length);

        // Check for an existing tab with our origin
        for (const client of windowClients) {
            try {
                const clientUrl = new URL(client.url);
                const targetUrlObj = new URL(targetUrl);

                if (clientUrl.origin === targetUrlObj.origin) {
                    console.log("✅ Found existing window with same origin");

                    // Try to focus the client first
                    await client.focus();

                    // Then try to navigate if possible
                    if (client.url !== targetUrl && "navigate" in client) {
                        try {
                            await client.navigate(targetUrl);
                            console.log(
                                "✅ Successfully navigated existing window"
                            );
                        } catch (navError) {
                            console.warn(
                                "Could not navigate existing window:",
                                navError
                            );
                            // Navigation failed, but we still have the window focused
                        }
                    }

                    return; // Successfully handled
                }
            } catch (urlError) {
                console.warn("Error processing client URL:", urlError);
                continue;
            }
        }

        // If no existing window found or all attempts failed, open new window
        console.log("❌ No suitable existing window found, opening new window");
        await clients.openWindow(targetUrl);
    } catch (error) {
        console.error("Error in notification click handler:", error);

        // Final fallback - try to open window directly
        try {
            await clients.openWindow(targetUrl);
        } catch (finalError) {
            console.error(
                "Complete failure to handle notification click:",
                finalError
            );
        }
    }
}

// Alternative simpler version - uncomment if above still has issues
/*
self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    let targetUrl = event.notification.data?.url || "/";

    // Ensure targetUrl is a full URL
    if (!targetUrl.startsWith("http")) {
        targetUrl = self.location.origin + targetUrl;
    }

    console.log("Opening URL:", targetUrl);

    // Simple approach - always open new window/tab
    event.waitUntil(clients.openWindow(targetUrl));
});
*/
