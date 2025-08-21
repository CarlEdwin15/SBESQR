import Swal from "sweetalert2";

// Toast for Teachers/Admins
const StaffToast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 8000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// Modal for Parents
function showParentModal(e) {
    Swal.fire({
        title: e.title,
        html: e.body, // ✅ use html to render Quill content
        icon: "info",
        footer: `📅 ${e.date}`,
        confirmButtonText: "Got it!",
        confirmButtonColor: "#3085d6",
        backdrop: true,
        timer: 15000,
        timerProgressBar: true,
    });
}

// Dropdown notification item
function addNotification(e) {
    const list = document.getElementById("notification-list");
    if (!list) return;

    const li = document.createElement("li");
    li.innerHTML = `
        <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                 style="width:36px; height:36px;">📢</div>
            <div>
                <strong>${e.title}</strong>
                <div class="text-muted small">${e.body}</div>
                <small class="text-muted">${e.date}</small>
            </div>
            <span class="ms-auto text-primary mt-1"><i class="bx bxs-circle"></i></span>
        </a>
    `;
    list.prepend(li);
}

// ======================
// Staff channel
// ======================
window.Echo?.private("announcements.teacher").listen(
    ".announcement.posted", // 👈 add dot
    (e) => {
        console.log("📡 Teacher/Admin announcement:", e);

        // Show toast
        StaffToast.fire({
            icon: "info",
            title: e.title,
            html: `<div>${e.body}</div><small class="text-muted">📅 ${e.date}</small>`,
            didOpen: (toast) => {
                toast.addEventListener("click", () => {
                    Swal.fire({
                        title: e.title,
                        html: e.body,
                        footer: `📅 ${e.date}`,
                        icon: "info",
                        confirmButtonText: "Close",
                    });
                });
            },
        });

        addNotification(e);
    }
);

// ======================
// Parent channel
// ======================
window.Echo?.private("announcements.parent").listen(
    ".announcement.posted", // 👈 add dot
    (e) => {
        console.log("📡 Parent announcement:", e);
        showParentModal(e);
        addNotification(e);
    }
);
