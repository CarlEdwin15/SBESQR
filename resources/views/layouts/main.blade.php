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

    <!-- Additional custom styles can be stacked from views -->
    @stack('styles')
</head>

<body>
    @yield('content')

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

    <script src="https://kit.fontawesome.com/ab677fe211.js" crossorigin="anonymous"></script>

    {{-- <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('✅ ServiceWorker registered:', registration);

                    // Verify service worker is active
                    if (registration.active) {
                        console.log('✅ ServiceWorker is active and running.');
                    }

                    // Ask permission for push
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            subscribeUserToPush(registration);
                        } else {
                            console.log('❌ Push notification permission denied.');
                        }
                    });
                })
                .catch(function(error) {
                    console.error('❌ ServiceWorker registration failed:', error);
                });
        }

        // Convert base64 public key to Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
        }

        // Subscribe user to push
        function subscribeUserToPush(registration) {
            const applicationServerKey = urlBase64ToUint8Array("{{ env('VAPID_PUBLIC_KEY') }}");

            registration.pushManager.getSubscription().then(function(existingSubscription) {
                    if (existingSubscription) {
                        // If the existing subscription uses a different key, unsubscribe first
                        return existingSubscription.unsubscribe().then(function(success) {
                            console.log("🔄 Old subscription removed:", success);
                            return null;
                        });
                    }
                    return null;
                }).then(function() {
                    // Create a fresh subscription
                    return registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: applicationServerKey
                    });
                }).then(function(subscription) {
                    console.log('📡 Push subscription:', subscription);

                    // Send subscription details to backend
                    return fetch("{{ route('push.subscribe') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            endpoint: subscription.endpoint,
                            keys: subscription.toJSON().keys,
                            contentEncoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0]
                        })
                    });
                }).then(response => response.json())
                .then(data => {
                    console.log('✅ Subscription saved:', data);
                })
                .catch(error => {
                    console.error("❌ Push subscription failed:", error);
                });
        }
    </script> --}}

    {{-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script> --}}

    <!-- Additional scripts can be stacked from views -->
    @stack('scripts')
</body>

</html>
