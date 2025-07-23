<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default" data-template="vertical-menu-template-free">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Additional scripts can be stacked from views -->
    @stack('scripts')
</body>

</html>
