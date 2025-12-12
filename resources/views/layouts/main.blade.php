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

    <!-- ... existing table rows clickable script ... -->

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

            console.log('Announcement Data:', {
                specificId: specificAnnouncementId,
                activeCount: activeAnnouncements.length,
                showOnLogin: showAnnouncementsOnLogin
            });

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
                                container: 'my-swal-container'
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

                let currentIndex = 0;

                function showNextAnnouncement() {
                    if (currentIndex >= announcements.length) {
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

                    const announcement = announcements[currentIndex];
                    const isLast = currentIndex === announcements.length - 1;

                    Swal.fire({
                        title: `📢 Announcement ${currentIndex + 1} of ${announcements.length}`,
                        html: `
                    <div class="text-start">
                        <h5 class="text-primary">${announcement.title}</h5>
                        <p><strong>Published:</strong> ${announcement.date_published}</p>
                        <p><strong>Author:</strong> ${announcement.author_name}</p>
                        <div class="border rounded p-3 mt-3 bg-light quill-content" style="max-height: 400px; overflow-y: auto;">
                            ${announcement.body}
                        </div>
                    </div>
                `,
                        showCloseButton: true,
                        showConfirmButton: true,
                        showDenyButton: !isLast,
                        confirmButtonText: isLast ? 'Close' : 'Next',
                        denyButtonText: 'Skip Remaining',
                        width: '800px',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isDenied) {
                            // Skip remaining announcements
                            currentIndex = announcements.length;
                            showNextAnnouncement();
                        } else {
                            // Continue to next announcement
                            currentIndex++;
                            showNextAnnouncement();
                        }
                    });
                }

                showNextAnnouncement();
            }

            // Priority 1: Show specific announcement if ID exists
            if (specificAnnouncementId) {
                console.log('Found specific announcement ID:', specificAnnouncementId);
                showAnnouncementModal(specificAnnouncementId);
            }
            // Priority 2: Show all active announcements on login
            else if (activeAnnouncements.length > 0) {
                console.log('Showing announcements on login:', activeAnnouncements.length);
                showAllActiveAnnouncements(activeAnnouncements);
            }

            // Make function globally available
            window.showAnnouncementModal = showAnnouncementModal;
            window.openAnnouncementModal = showAnnouncementModal; // For backward compatibility
        });
    </script>

    <script>
        // Add this to your existing JavaScript
        window.openAnnouncementModal = function(announcementId) {
            // Fetch announcement details
            fetch(`/announcements/${announcementId}/show-ajax`)
                .then(response => response.json())
                .then(data => {
                    // Create and show modal with announcement content
                    Swal.fire({
                        title: data.title,
                        html: `
                        <div class="text-start">
                            <p><strong>Published:</strong> ${data.published}</p>
                            <p><strong>Author:</strong> ${data.author}</p>
                            <div class="border rounded p-3 mt-3 bg-light">
                                ${data.body}
                            </div>
                        </div>
                    `,
                        showCloseButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Close',
                        width: '800px',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching announcement:', error);
                    // Fallback: redirect to announcement page
                    window.location.href = `/announcement/redirect/${announcementId}`;
                });
        };
    </script>

    @if (!empty($announcementId))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const announcementId = {{ $announcementId }};
                // 👇 Call your existing JS function to open the modal
                openAnnouncementModal(announcementId);
            });
        </script>
    @endif

    <!-- Additional scripts can be stacked from views -->
    @stack('scripts')
</body>

</html>
