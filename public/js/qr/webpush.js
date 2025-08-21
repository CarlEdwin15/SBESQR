// public/js/webpush.js
if ("serviceWorker" in navigator && "PushManager" in window) {
    navigator.serviceWorker
        .register("/service-worker.js")
        .then((registration) => {
            console.log("Service Worker registered", registration);

            return registration.pushManager
                .getSubscription()
                .then(async (subscription) => {
                    if (subscription) return subscription;

                    const response = await fetch(
                        "/push/subscription/vapid-public-key"
                    );
                    const vapidPublicKey = await response.text();
                    const convertedVapidKey =
                        urlBase64ToUint8Array(vapidPublicKey);

                    return registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey,
                    });
                });
        })
        .then((subscription) => {
            fetch("/push/subscription", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(subscription),
            });
        })
        .catch((err) => console.error("Service Worker Error", err));
}

function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
}
