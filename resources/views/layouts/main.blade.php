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

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const htmlEl = document.documentElement;
            const toggles = document.querySelectorAll('.layout-menu-toggle');
            const overlay = document.querySelector('.layout-overlay');

            const DESKTOP_BREAKPOINT = 1200; // Sneat default

            function isDesktop() {
                return window.innerWidth >= DESKTOP_BREAKPOINT;
            }

            function closeMobileMenu() {
                htmlEl.classList.remove('layout-menu-expanded');
            }

            function toggleMenu() {
                if (isDesktop()) {
                    // ✅ Desktop toggle (collapse/expand sidebar)
                    htmlEl.classList.toggle('layout-menu-collapsed');
                    htmlEl.classList.remove('layout-menu-expanded');
                } else {
                    // ✅ Mobile toggle (overlay slide in/out)
                    htmlEl.classList.toggle('layout-menu-expanded');
                }
            }

            // Bind toggle button
            toggles.forEach(btn => btn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMenu();
            }));

            // Overlay click closes mobile menu
            overlay?.addEventListener('click', closeMobileMenu);

            // ESC closes mobile menu
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMobileMenu();
            });

            // Resize handler keeps state clean
            let rt;
            window.addEventListener('resize', function() {
                clearTimeout(rt);
                rt = setTimeout(function() {
                    if (isDesktop()) {
                        htmlEl.classList.remove('layout-menu-expanded');
                    } else {
                        htmlEl.classList.remove('layout-menu-collapsed');
                    }
                }, 150);
            });
        });
    </script> --}}

    {{-- @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error!',
                                html: `<ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            });
                        });
                    </script>
                @endif --}}

    <!-- Make table rows clickable -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".t-row").forEach(row => {
                row.addEventListener("click", function() {
                    window.location = this.dataset.href;
                });
            });
        });
    </script>

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
        // Handle navigation messages from service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', event => {
                if (event.data && event.data.type === 'NAVIGATE_TO_ANNOUNCEMENT') {
                    console.log('Received navigation request:', event.data.url);
                    window.location.href = event.data.url;
                }
            });
        }

        // Optional: Let service worker know we're ready
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(registration => {
                console.log('Service Worker is ready');
            });
        }
    </script>

    <script>
        // Create a SweetAlert mixin so we don’t repeat customClass everywhere
        const MySwal = Swal.mixin({
            customClass: {
                container: 'my-swal-container'
            }
        });

        document.getElementById('enablePush')?.addEventListener('click', async () => {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                MySwal.fire({
                    title: "Unsupported",
                    text: "🚫 Your browser does not support push notifications.",
                    icon: "error"
                });
                return;
            }

            try {
                // 1. Register service worker
                const reg = await navigator.serviceWorker.register('/sw.js');

                // 2. Check current permission
                if (Notification.permission === "denied") {
                    MySwal.fire({
                        title: "Notifications Blocked",
                        html: "🚫 You have blocked notifications.<br><br>To enable push, please allow notifications in your browser's site settings.",
                        icon: "warning"
                    });
                    return;
                }

                if (Notification.permission !== "granted") {
                    const permission = await Notification.requestPermission();
                    if (permission !== "granted") {
                        MySwal.fire({
                            title: "Permission Needed",
                            html: "❌ You must allow notifications in the popup to enable push.<br><br>If you don’t see the popup, check your browser's site settings.",
                            icon: "error"
                        });
                        return;
                    }
                }

                // 3. Get or create subscription
                let sub = await reg.pushManager.getSubscription();
                if (!sub) {
                    sub = await reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(
                            "{{ trim(env('VAPID_PUBLIC_KEY')) }}"),
                    });
                }

                // 5. Send subscription to backend
                const res = await fetch("{{ route('push.subscribe') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        endpoint: sub.endpoint,
                        expirationTime: sub.expirationTime,
                        keys: sub.toJSON().keys
                    }),
                });

                const data = await res.json();
                if (res.ok) {
                    // Save flag so we remember across reloads
                    localStorage.setItem("notificationsEnabled", "true");

                    MySwal.fire({
                        title: "Success!",
                        text: "✅ Notifications enabled successfully!",
                        icon: "success"
                    });

                    // display the announcement section
                    const section = document.getElementById('announcement-section');
                    if (section) {
                        section.style.display = 'block';
                    }

                    // display the announcement nav item
                    const navItem = document.getElementById('announcement-nav');
                    if (navItem) {
                        navItem.style.display = 'inline-block';
                    }

                } else {
                    MySwal.fire({
                        title: "Error",
                        text: "❌ Failed to save subscription: " + JSON.stringify(data),
                        icon: "error"
                    });
                }
            } catch (err) {
                console.error("Push registration failed:", err);
                MySwal.fire({
                    title: "Error",
                    text: "❌ Push registration failed. Check console for details.",
                    icon: "error"
                });
            }
        });

        // Helper: convert VAPID key from base64 to Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Show announcement section if user already subscribed before
        window.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem("notificationsEnabled") === "true") {
                const section = document.getElementById('announcement-section');
                if (section) {
                    section.style.display = 'block';
                }

                const navItem = document.getElementById('announcement-nav');
                if (navItem) {
                    navItem.style.display = 'inline-block';
                }
            }
        });
    </script>

    <!-- SweetAlert toast for success and error messages -->
    <script>
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
                // 👇 Call your existing JS function to open the modal
                openAnnouncementModal(announcementId);
            });
        </script>
    @endif

    {{-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script> --}}

    <!-- Additional scripts can be stacked from views -->
    @stack('scripts')

    {{-- </div>
    </div>
    </div> --}}
</body>

</html>
