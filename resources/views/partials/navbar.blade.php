<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    {{-- @php
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $startYear = $now->lt($cutoff) ? $year - 1 : $year;
        $schoolYear = $startYear . '-' . ($startYear + 1);
    @endphp

    <div class="d-flex align-items-center ms-3">
        <h5 class="mb-0">Current School Year: {{ $schoolYear }}</h5>
    </div> --}}

    @php
        $nowPH = now('Asia/Manila')->format('Y-m-d H:i:s');
    @endphp

    <div class="d-flex align-items-center ms-3 text-primary">
        <h6 class="mb-0"><span id="realtime-clock">{{ $nowPH }}</span></h6>
    </div>

    <script>
        let currentTime = new Date("{{ $nowPH }} GMT+0800");

        function updateClock() {
            currentTime.setSeconds(currentTime.getSeconds() + 1);
            const formatted = currentTime.toLocaleString('en-PH', {
                weekday: 'long', // Include day name
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                timeZone: 'Asia/Manila'
            }).replace(' at ', ' '); // Remove the word "at"
            document.getElementById('realtime-clock').textContent = formatted;
        }

        setInterval(updateClock, 1000);
    </script>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <button id="enablePush" class="btn btn-outline-primary">Enable SBESqr notifications</button>

            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-bell fs-4'></i>
                    @if ($notifications->count())
                        <span class="badge bg-danger rounded-pill badge-notifications">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="notificationDropdown"
                    style="min-width: 350px; max-height: 400px; overflow-y: auto;">

                    <li class="px-3 pt-2">
                        <h6 class="mb-1 d-flex justify-content-between">
                            Notifications
                            <span class="badge bg-light-primary text-primary fw-bold">
                                {{ $notifications->count() }} New
                            </span>
                        </h6>
                    </li>

                    @forelse($notifications as $notif)
                        <li>
                            <a class="dropdown-item d-flex align-items-start gap-2 py-3 view-announcement"
                                href="javascript:void(0);" data-id="{{ $notif->id }}">
                                <div class="rounded-circle d-flex justify-content-center align-items-center overflow-hidden"
                                    style="width:36px; height:36px; background-color:#f8f9fa;">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="img-fluid"
                                        style="width:100%; height:100%; object-fit:contain;">
                                </div>
                                <div>
                                    <strong>{{ $notif->title }}</strong>
                                    <div class="text-muted small">{!! Str::limit(strip_tags($notif->body), 40) !!}</div>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="ms-auto text-primary mt-1">
                                    <i class="bx bxs-circle"></i>
                                </span>
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="dropdown-item text-center text-muted py-3">
                                No notifications
                            </div>
                        </li>
                    @endforelse

                    <li>
                        <hr class="dropdown-divider my-0">
                    </li>
                    <li>
                        <a href=""
                            class="dropdown-item text-center text-primary fw-semibold py-2">
                            View all announcements
                        </a>
                    </li>
                </ul>
            </li>
            <!-- /Notification Dropdown -->

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            @php use Illuminate\Support\Str; @endphp

                            @auth
                                @php
                                    $profilePhoto = Auth::user()->profile_photo;

                                    if ($profilePhoto) {
                                        if (Str::startsWith($profilePhoto, ['http://', 'https://'])) {
                                            // External photo (Google login, etc.)
                                            $profilePhoto = $profilePhoto;
                                        } else {
                                            // Stored locally
                                            $profilePhoto = asset('storage/' . $profilePhoto);
                                        }
                                    } else {
                                        // No profile photo, fallback by role
                                        switch (Auth::user()->role) {
                                            case 'admin':
                                                $profilePhoto = asset(
                                                    'assetsDashboard/img/profile_pictures/admin_default_profile.jpg',
                                                );
                                                break;
                                            case 'teacher':
                                                $profilePhoto = asset(
                                                    'assetsDashboard/img/profile_pictures/teacher_default_profile.jpg',
                                                );
                                                break;
                                            case 'parent':
                                                $profilePhoto = asset(
                                                    'assetsDashboard/img/profile_pictures/parent_default_profile.jpg',
                                                );
                                                break;
                                            default:
                                                // generic fallback (teacher style)
                                                $profilePhoto = asset(
                                                    'assetsDashboard/img/profile_pictures/teacher_default_profile.jpg',
                                                );
                                                break;
                                        }
                                    }
                                @endphp

                                <img src="{{ $profilePhoto }}" alt="Profile Photo" class="w-px-40 h-auto rounded-circle" />
                            @else
                                {{-- Guest fallback --}}
                                <img src="{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                    alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
                            @endauth
                        </div>

                        @auth
                            <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                        @endauth
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    @auth
                                        <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                            class="w-px-40 h-auto rounded-circle" />
                                    @else
                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg') }}"
                                            alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
                                    @endauth
                                </div>
                                @auth
                                    <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                @endauth
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('account.settings') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); confirmLogout();">
                            <i class="bx bx-power-off me-2"></i>
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </ul>
            </li>
            <!-- /User Dropdown -->

        </ul>
    </div>
</nav>
<!-- / Navbar -->

<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="announcementContent" class="text-muted">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading announcement...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <small class="text-muted me-auto" id="announcementMeta"></small>
                <button type="button" class="btn btn-secondary rounded-pill px-4"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = new bootstrap.Modal(document.getElementById('announcementModal'));
        const content = document.getElementById('announcementContent');
        const meta = document.getElementById('announcementMeta');

        // Handle manual click from dropdown items
        document.querySelectorAll('.view-announcement').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;

                // Show loading spinner
                content.innerHTML = `
                <div class="text-center py-5">
                  <div class="spinner-border text-primary" role="status"></div>
                  <p class="mt-2">Loading announcement...</p>
                </div>`;
                meta.textContent = '';

                modal.show();

                // Fetch announcement details
                fetch(`/announcements/${id}/show-ajax`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('announcementModalLabel').textContent = data
                            .title;
                        content.innerHTML = data.body;
                        meta.textContent =
                            `Published: ${data.published} | By ${data.author}`;
                    })
                    .catch(err => {
                        content.innerHTML =
                            `<div class="alert alert-danger">Failed to load announcement.</div>`;
                    });
            });
        });

        // Auto-open modal if announcement_id is in URL (from push notification click)
        const urlParams = new URLSearchParams(window.location.search);
        const announcementId = urlParams.get('announcement_id');

        if (announcementId) {
            content.innerHTML = `
              <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading announcement...</p>
              </div>`;
            meta.textContent = '';

            modal.show();

            fetch(`/announcements/${announcementId}/show-ajax`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('announcementModalLabel').textContent = data.title;
                    content.innerHTML = data.body;
                    meta.textContent = `Published: ${data.published} | By ${data.author}`;

                    // ✅ Remove announcement_id from URL after modal is loaded
                    const newUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                })
                .catch(err => {
                    content.innerHTML =
                        `<div class="alert alert-danger">Failed to load announcement.</div>`;
                });
        }
    });
</script>
