<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    @php
        use Illuminate\Support\Str;
        use Carbon\Carbon;
        use Illuminate\Support\Facades\DB;
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $startYear = $now->lt($cutoff) ? $year - 1 : $year;
        $schoolYear = $startYear . '-' . ($startYear + 1);

        // Filter announcements to show only ACTIVE or ARCHIVE status (not inactive)
        $oneWeekAgo = now()->subWeek();
        $recentNotifications = $notifications->filter(function ($notification) use ($oneWeekAgo) {
            // First, check if it's within the last week
            $isRecent = $notification->created_at->gte($oneWeekAgo);

            // Get the announcement status using the model's getStatus() method
            $status = $notification->getStatus();

            // Only show if recent AND (active or archive status)
            return $isRecent && in_array($status, ['active', 'archive']);
        });

        // Sort by newest first
        $recentNotifications = $recentNotifications->sortByDesc('created_at');

        // Count unread announcements using direct database query
        $unreadCount = 0;
        $user = Auth::user();
        if ($user) {
            $unreadCount = DB::table('announcement_user')
                ->join('announcements', 'announcement_user.announcement_id', '=', 'announcements.id')
                ->where('announcement_user.user_id', $user->id)
                ->whereNull('announcement_user.read_at')
                ->where(function($query) {
                    $query->where('announcements.status', 'active')
                        ->orWhere('announcements.status', 'archive');
                })
                ->where('announcements.date_published', '>=', now()->subWeek())
                ->count();
        }
    @endphp

    <div class="d-flex align-items-center ms-3">
        <h6 class="mb-0 d-none d-sm-block">Current School Year: {{ $schoolYear }}</h6>
        <h6 class="mb-0 d-block d-sm-none">Current SY: {{ $schoolYear }}</h6>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="position-relative" style="width: 40px; height: 40px;">
                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}"
                            alt="Announcements" class="img-fluid"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 15%; padding: 5px;">
                        <!-- REMOVED: Red notification count badge -->
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="notificationDropdown"
                    style="min-width: 320px; max-width: 320px; max-height: 400px; overflow-y: auto;"
                    id="announcement-dropdown">

                    <li class="px-3 pt-2">
                        <h6 class="mb-1 d-flex justify-content-between">
                            Recent Announcements
                            <!-- REMOVED: Unread total badge -->
                        </h6>
                    </li>

                    @forelse($recentNotifications as $notif)
                        @php
                            // Check if announcement is unread for current user
                            $isUnread = false;
                            if ($user && $notif->recipients) {
                                $recipient = $notif->recipients->find($user->id);
                                $isUnread = $recipient ? is_null($recipient->pivot->read_at) : false;
                            }

                            // Determine circle color based on announcement age
                            $createdAt = $notif->created_at;
                            $now = now();
                            $hoursDiff = $createdAt->diffInHours($now);

                            // Get announcement status
                            $status = $notif->getStatus();

                            // Determine circle color and title based on status and age
                            if ($status === 'active') {
                                if ($hoursDiff <= 24) {
                                    $circleColor = 'text-danger'; // Red for active & less than 24 hours
                                    $title = 'Active - New (Less than 24 hours)';
                                } elseif ($hoursDiff <= 72) {
                                    $circleColor = 'text-success'; // Green for active & 1-3 days
                                    $title = 'Active - Recent (1-3 days)';
                                } else {
                                    $circleColor = 'text-primary'; // Blue for active & 3-7 days
                                    $title = 'Active - Older (3-7 days)';
                                }
                            } else {
                                // archive status
                                $circleColor = 'text-warning'; // Gray for archived
                                $title = 'Archived';
                            }
                        @endphp
                        <li>
                            <a class="dropdown-item d-flex align-items-start gap-2 py-3 view-announcement announcement-item"
                                href="javascript:void(0);" data-id="{{ $notif->id }}"
                                data-unread="{{ $isUnread ? 'true' : 'false' }}">
                                <div class="rounded-circle d-flex justify-content-center align-items-center overflow-hidden"
                                    style="width:45px; height:45px;">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}"
                                        alt="Logo" class="img-fluid"
                                        style="width:100%; height:100%; object-fit:contain;">
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <strong class="d-block text-truncate"
                                        style="max-width: 180px;">{{ $notif->title }}</strong>
                                    <div class="text-muted small text-truncate" style="max-width: 180px;">
                                        {!! Str::limit(strip_tags($notif->body), 40) !!}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                        @if ($status === 'active')
                                            <span class="badge bg-success rounded-pill"
                                                style="font-size: 0.65rem;">Active</span>
                                        @elseif($status === 'archive')
                                            <span class="badge bg-warning rounded-pill"
                                                style="font-size: 0.65rem;">Archived</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- REMOVED: Circle icon for unread announcements -->
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="dropdown-item text-center text-muted py-3">
                                No recent active announcements
                            </div>
                        </li>
                    @endforelse

                    <li>
                        <hr class="dropdown-divider my-0">
                    </li>
                    <li>
                        <a href="{{ route('announcements.index') }}" class="dropdown-item text-center text-primary fw-semibold py-2">
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
                            @auth
                                @php
                                    $profilePhoto = Auth::user()->profile_photo;

                                    if ($profilePhoto) {
                                        if (Str::startsWith($profilePhoto, ['http://', 'https://'])) {
                                            $profilePhoto = $profilePhoto;
                                        } else {
                                            $profilePhoto = asset('public/uploads/' . $profilePhoto);
                                        }
                                    } else {
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
                                                $profilePhoto = asset(
                                                    'assetsDashboard/img/profile_pictures/teacher_default_profile.jpg',
                                                );
                                                break;
                                        }
                                    }
                                @endphp

                                <img src="{{ $profilePhoto }}" alt="Profile Photo" class="w-px-40 h-auto rounded-circle" />
                            @else
                                <img src="{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                    alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
                            @endauth
                        </div>

                        @auth
                            <span class="fw-semibold ms-2 d-none d-sm-block">{{ Auth::user()->firstName }}</span>
                        @endauth
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        @php
                            $role = Auth::user()->role ?? null;

                            switch ($role) {
                                case 'admin':
                                    $settingsRoute = route('admin.account.settings');
                                    break;
                                case 'teacher':
                                    $settingsRoute = route('teacher.account.settings');
                                    break;
                                case 'parent':
                                    $settingsRoute = route('parent.account.settings');
                                    break;
                                default:
                                    $settingsRoute = '#';
                                    break;
                            }
                        @endphp

                        <a class="dropdown-item" href="{{ $settingsRoute }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Account Settings</span>
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

<!-- Announcement Modal Script -->
<script>
    // Asset URL for announcement sticker icon
    const announcementStickerUrl = "{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}";

    document.addEventListener("DOMContentLoaded", function() {
        // Handle manual announcement click
        document.querySelectorAll('.view-announcement').forEach(item => {
            item.addEventListener('click', async function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const isUnread = this.getAttribute('data-unread') === 'true';

                try {
                    // Fetch announcement details
                    const response = await fetch(`/announcements/${id}/show-ajax`);

                    if (!response.ok) {
                        throw new Error('Failed to fetch announcement details');
                    }

                    const data = await response.json();

                    // Show SweetAlert2 modal with layout/main styling
                    Swal.fire({
                        title: data.title,
                        html: `
                            <img
                                src="${announcementStickerUrl}"
                                class="swal-announcement-sticker"
                                alt="Announcement Sticker"
                            />

                            <div class="text-start mt-0">
                                <div class="border rounded p-3 mt-3 bg-light quill-content"
                                    style="max-height: 400px; overflow-y: auto;">
                                    <p class="text-end" style="font-size: 1rem;">${data.published}</p>
                                    ${data.body}
                                    <p class="mt-2 text-start fw-bold" style="font-size: 1.3rem; color: #001BB7; font-family: 'Times New Roman', Times, serif;">— ${data.author}</p>
                                </div>
                            </div>
                        `,
                        showCloseButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Close',
                        width: '800px',
                        customClass: {
                            container: 'my-swal-container',
                            actions: 'swal2-actions-centered'
                        },
                        didOpen: () => {
                            // Ensure sticker positioning
                            const sticker = document.querySelector(
                                '.swal-announcement-sticker');
                            if (sticker) {
                                sticker.style.position = 'absolute';
                                sticker.style.top = '-15px';
                                sticker.style.left = '-25px';
                                sticker.style.width = '100px';
                                sticker.style.height = 'auto';
                                sticker.style.zIndex = '9999';
                                sticker.style.pointerEvents = 'none';
                                sticker.style.filter =
                                    'drop-shadow(0 4px 6px rgba(0, 0, 0, 0.25))';
                            }
                        }
                    }).then((result) => {
                        // After modal is closed, mark as read if it was unread
                        if (isUnread) {
                            markAnnouncementAsRead(id);
                        }
                    });

                    // Clear session storage when manually viewing announcements
                    sessionStorage.removeItem('announcements_shown_on_login');

                } catch (error) {
                    console.error('Error fetching announcement details:', error);
                    // Fallback: redirect if fetch fails
                    window.location.href = `/announcement/redirect/${id}`;
                }
            });
        });

        // Function to mark announcement as read
        async function markAnnouncementAsRead(announcementId) {
            try {
                const response = await fetch(`/announcements/${announcementId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    // Remove circle icon for this announcement
                    const circleIcon = document.querySelector(
                        `.announcement-circle[data-id="${announcementId}"]`);
                    if (circleIcon) {
                        circleIcon.remove();
                    }

                    // Remove data-unread attribute from the announcement item
                    const announcementItem = document.querySelector(
                        `.announcement-item[data-id="${announcementId}"]`);
                    if (announcementItem) {
                        announcementItem.setAttribute('data-unread', 'false');
                    }

                    // Update the unread count
                    updateUnreadCount();
                }
            } catch (error) {
                console.error('Error marking announcement as read:', error);
            }
        }

        // Function to update unread count - MODIFIED to not update UI since we removed badges
        async function updateUnreadCount() {
            try {
                const response = await fetch('/announcements/unread-count');
                if (response.ok) {
                    const data = await response.json();
                    // We're still fetching the count but not updating any UI elements
                    const unreadCount = data.count || 0;
                    // No UI updates since badges are removed
                }
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        }

        // Periodically update unread count (every 30 seconds) - Still runs but doesn't update UI
        setInterval(updateUnreadCount, 30000);
    });
</script>

<!-- Custom Styles for Navbar Announcements -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0066cc, #0099ff);
    }

    .announcement-body {
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Ensure SweetAlert2 modal has proper z-index */
    .swal2-container {
        z-index: 999999 !important;
    }

    .my-swal-container {
        z-index: 10000;
    }

    /* Quill content styling */
    .quill-content {
        font-family: sans-serif;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .quill-content .ql-align-center {
        text-align: center;
    }

    .quill-content .ql-align-right {
        text-align: right;
    }

    .quill-content .ql-align-justify {
        text-align: justify;
    }

    .quill-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px auto;
    }

    /* Quill font sizes */
    .ql-size-small {
        font-size: 0.75em;
    }

    .ql-size-large {
        font-size: 1.5em;
    }

    .ql-size-huge {
        font-size: 2.5em;
    }

    /* Quill font families */
    .ql-font-sans-serif {
        font-family: sans-serif;
    }

    .ql-font-serif {
        font-family: serif;
    }

    .ql-font-monospace {
        font-family: monospace;
    }

    /* Announcement sticker image positioning */
    .swal2-popup {
        position: relative !important;
    }

    .swal-announcement-sticker {
        position: absolute;
        top: -15px;
        left: -25px;
        width: 100px;
        height: auto;
        z-index: 9999;
        pointer-events: none;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.25));
    }

    /* Improved dropdown styling */
    .dropdown-menu[aria-labelledby="notificationDropdown"] {
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .dropdown-menu[aria-labelledby="notificationDropdown"] .dropdown-item:hover {
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    /* REMOVED: Circle icon animation for unread announcements */
</style>
