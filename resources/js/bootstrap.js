import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// ----------------------
// Axios setup
// ----------------------
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

// ----------------------
// Pusher setup
// ----------------------
window.Pusher = Pusher;
window.Pusher.logToConsole = true; // optional for debugging

// ----------------------
// Laravel Echo setup
// ----------------------
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: "/broadcasting/auth",
    auth: {
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    },
});

// ----------------------
// Pusher connection events
// ----------------------
const pusherConnection = window.Echo.connector.pusher.connection;

pusherConnection.bind("connected", () => {
    console.log("✅ Pusher connected");
});

pusherConnection.bind("error", (err) => {
    console.error("❌ Pusher error", err);
});

pusherConnection.bind("state_change", (states) => {
    console.log("ℹ️ Pusher state change", states);
});

// ----------------------
// Load custom Echo listeners
// ----------------------
import "./echo-listeners";
