<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default" data-template="vertical-menu-template-free">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="SBESQR" />
    <link rel="manifest" href="{{ asset('assets/img/site.webmanifest') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://fontawesome.com/icons">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/fonts/boxicons.css') }}" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assetsDashboard/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assetsDashboard/js/config.js') }}"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tom Select CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            .swal2-container {
                z-index: 999999 !important;
            }

            .my-swal-container {
                /* For Log out sweet alert*/
                z-index: 10000;
                /* Or a sufficiently high value */
            }

            /* Ensure announcement content respects Quill styling */
            .quill-content {
                font-family: sans-serif;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            /* Quill alignment fixes */
            .quill-content .ql-align-center {
                text-align: center;
            }

            .quill-content .ql-align-right {
                text-align: right;
            }

            .quill-content .ql-align-justify {
                text-align: justify;
            }

            /* Responsive images inside announcements */
            .quill-content img {
                max-width: 100%;
                height: auto;
                display: block;
                margin: 10px auto;
            }

            .t-row {
                cursor: pointer;
            }

            .t-row:hover {
                background-color: #f1f1f1;
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

            /* Active announcement styling */
            .active-announcement {
                border-left: 4px solid #198754;
            }

            /* Tom Select styling */
            .ts-control {
                background-color: #e0f7fa;
                border-color: #42a5f5;
            }

            .ts-control .item {
                background-color: #4dd0e1;
                color: white;
                border-radius: 4px;
                padding: 3px 8px;
                margin-right: 4px;
            }

            .ts-dropdown .option.active {
                background-color: #e3f2fd;
                color: #1976d2;
            }

            /* Sticker container (relative to modal) */
            .swal2-popup {
                position: relative !important;
            }

            /* Announcement sticker image */
            .swal-announcement-sticker {
                position: absolute;
                top: -15px;
                /* pulls image outside modal */
                left: -25px;
                /* overlaps left edge */
                width: 100px;
                /* adjust to match your image */
                height: auto;
                z-index: 9999;
                pointer-events: none;
                /* prevents blocking clicks */
                filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.25));
            }
        </style>
    @endpush

    <!-- Additional custom styles can be stacked from views -->
    @stack('styles')
</head>

<body>
    @hasSection('hideWrapper')
        {{-- Wrapper disabled for pages like welcome --}}
        @yield('content')
    @else
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Layout container -->
                <div class="layout-page">
                    {{-- Only include navbar if child view does not disable it --}}
                    @hasSection('hideNavbar')
                        {{-- Navbar disabled --}}
                    @else
                        @include('partials.navbar')
                    @endif

                    @yield('content')

                    <!-- Overlay -->
                    <div class="layout-overlay layout-menu-toggle"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Core JS -->
    <!-- build:assetsDashboard/vendor/js/core.js -->
    <script src="{{ asset('assetsDashboard/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assetsDashboard/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assetsDashboard/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assetsDashboard/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assetsDashboard/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assetsDashboard/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assetsDashboard/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ab677fe211.js" crossorigin="anonymous"></script>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        // SweetAlert toast for success and error messages
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    customClass: {
                        container: 'my-swal-container'
                    }
                });
            @endif
        });
    </script>

    @if (!empty($announcementId))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const announcementId = {{ $announcementId }};
                // Call your existing JS function to open the modal
                if (typeof window.openAnnouncementModal === 'function') {
                    window.openAnnouncementModal(announcementId);
                }
            });
        </script>
    @endif

    <!-- Announcement Handling Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Checking for announcements...');

            // Check if user is logged in
            const isLoggedIn = @json(Auth::check());

            if (!isLoggedIn) {
                console.log('User not logged in, skipping announcement check');
                return;
            }

            // Get data from server
            const specificAnnouncementId = @json($announcementId ?? null);
            const activeAnnouncements = @json($activeAnnouncements ?? []);
            const showAnnouncementsOnLogin = @json(session('show_announcements_on_login') ?? false);

            // Asset URL for announcement sticker icon
            const announcementStickerUrl = "{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}";

            console.log('Announcement Data:', {
                specificId: specificAnnouncementId,
                activeCount: activeAnnouncements.length,
                showOnLogin: showAnnouncementsOnLogin,
                announcements: activeAnnouncements
            });

            // Sort announcements by created_at in descending order (newest first)
            function sortAnnouncements(announcements) {
                return [...announcements].sort((a, b) => {
                    const dateA = new Date(a.created_at || a.date_published);
                    const dateB = new Date(b.created_at || b.date_published);
                    return dateB - dateA; // Newest first
                });
            }

            // Function to show single announcement modal
            function showAnnouncementModal(announcementId) {
                console.log('Showing announcement modal for ID:', announcementId);

                fetch(`/announcements/${announcementId}/show-ajax`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch announcement details');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Announcement data loaded:', data);

                        Swal.fire({
                            title: data.title,
                            html: `
                    <div class="text-start">
                        <p><strong>Published:</strong> ${data.published}</p>
                        <p><strong>Author:</strong> ${data.author}</p>
                        <div class="border rounded p-3 mt-3 bg-light quill-content" style="max-height: 400px; overflow-y: auto;">
                            ${data.body}
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
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching announcement details:', error);
                        window.location.href = `/announcement/redirect/${announcementId}`;
                    });
            }

            // Function to show all active announcements in sequence
            function showAllActiveAnnouncements(announcements) {
                if (!announcements || announcements.length === 0) return;

                // Sort announcements: newest first (by created_at)
                const sortedAnnouncements = sortAnnouncements(announcements);
                console.log('Sorted announcements (newest first):', sortedAnnouncements);

                let currentIndex = 0;
                let swalInstance = null;

                function showCurrentAnnouncement() {
                    if (currentIndex >= sortedAnnouncements.length) {
                        // Clear the session via AJAX when all announcements are shown
                        fetch('/clear-announcement-session', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                type: 'login'
                            })
                        });
                        return;
                    }

                    const announcement = sortedAnnouncements[currentIndex];
                    const totalAnnouncements = sortedAnnouncements.length;
                    const isFirst = currentIndex === 0;
                    const isLast = currentIndex === totalAnnouncements - 1;

                    console.log(`Showing announcement ${currentIndex + 1} of ${totalAnnouncements}:`, announcement
                        .title);

                    swalInstance = Swal.fire({
                        title: announcement.title,
                        html: `
                        <img
                            src="${announcementStickerUrl}"
                            class="swal-announcement-sticker"
                            alt="Announcement Sticker"
                        />

                        <div class="text-start mt-0">
                            <div class="border rounded p-3 mt-3 bg-light quill-content"
                                style="max-height: 400px; overflow-y: auto;">
                                <p class="text-end" style="font-size: 1rem;">${announcement.date_published}</p>
                                ${announcement.body}
                                <p class="mt-2 text-start fw-bold" style="font-size: 1.3rem; color: 001BB7; font-family: 'Times New Roman', Times, serif;">— ${announcement.author_name}</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <p class="mb-0" style="font-size: 1rem;">Announcement ${currentIndex + 1} of ${totalAnnouncements}</p>

                            </div>
                        </div>
                    `,


                        showCloseButton: true,
                        showConfirmButton: true,
                        showDenyButton: !isFirst, // Show Previous button except for first
                        confirmButtonText: isLast ? 'Close' : 'Next',
                        denyButtonText: 'Previous',
                        reverseButtons: true,
                        width: '800px',
                        focusConfirm: false,
                        customClass: {
                            container: 'my-swal-container',
                            actions: 'swal2-actions-centered'
                        },
                        didOpen: () => {
                            // Add custom close button functionality
                            const closeButton = document.querySelector('.swal2-close');
                            if (closeButton) {
                                closeButton.addEventListener('click', () => {
                                    // Skip remaining announcements when X is clicked
                                    currentIndex = sortedAnnouncements.length;
                                    if (swalInstance) {
                                        swalInstance.close();
                                    }
                                    showCurrentAnnouncement();
                                });
                            }

                            // Style buttons to be centered
                            setTimeout(() => {
                                const actions = document.querySelector('.swal2-actions');
                                if (actions) {
                                    // Center all buttons
                                    actions.classList.add('d-flex', 'justify-content-center',
                                        'align-items-center', 'gap-3');
                                }
                            }, 10);
                        }
                    }).then((result) => {
                        if (result.isDenied) {
                            // Previous button clicked
                            if (currentIndex > 0) {
                                currentIndex--;
                                showCurrentAnnouncement();
                            }
                        } else if (result.isConfirmed) {
                            // Next button clicked
                            if (!isLast) {
                                currentIndex++;
                                showCurrentAnnouncement();
                            } else {
                                // Last announcement - Close button clicked
                                currentIndex = sortedAnnouncements.length;
                                showCurrentAnnouncement();
                            }
                        } else if (result.dismiss === Swal.DismissReason.close) {
                            // X button clicked
                            currentIndex = sortedAnnouncements.length;
                            showCurrentAnnouncement();
                        }
                    });
                }

                showCurrentAnnouncement();
            }

            // Priority 1: Show specific announcement if ID exists
            if (specificAnnouncementId) {
                console.log('Found specific announcement ID:', specificAnnouncementId);
                setTimeout(() => {
                    showAnnouncementModal(specificAnnouncementId);
                }, 500);
            }
            // Priority 2: Show all active announcements on login
            else if (activeAnnouncements.length > 0) {
                console.log('Active announcements found:', activeAnnouncements.length);

                // Check if announcements should be shown on login
                const cameFromLogin = document.referrer.includes('/login') ||
                    document.referrer.includes('/welcome') ||
                    window.location.pathname === '/home';

                if (cameFromLogin) {
                    // Add a small delay to ensure DOM is fully loaded
                    setTimeout(() => {
                        showAllActiveAnnouncements(activeAnnouncements);
                    }, 800);
                }
            }

            // Make functions globally available for navbar and other components
            window.showAnnouncementModal = showAnnouncementModal;
            window.showAllActiveAnnouncements = showAllActiveAnnouncements;
            window.openAnnouncementModal = showAnnouncementModal; // For backward compatibility
        });
    </script>

    <!-- Additional scripts can be stacked from views -->
    @stack('scripts')
</body>

</html>
