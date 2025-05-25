<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style customizer-hide" dir="ltr"
    data-theme="theme-default" data-assetsDashboard-path="assetsDashboard/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Login') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assetsDashboard/img/favicon/logo.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/theme-default.css')}}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/css/demo.css')}}" />

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/pages/page-auth.css')}}" />
    <!-- Helpers -->
    <script src="{{ asset('assetsDashboard/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assetsDashboard/js/config.js')}}"></script>
    <style>
        body {
            background: url('assets/img/hero-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Add a dark overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            /* Adjust opacity (0.6 = 60% dark) */
            z-index: 1;
        }

        /* Ensure the login form stays above the overlay */
        .authentication-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 400px;
            z-index: 2;
            /* Ensures form is above the overlay */
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            /* Semi-transparent white for readability */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Center the logo container */
        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            max-height: 200px;
            padding-bottom: 40px;
        }

        /* Ensure the logo image is properly sized and centered */
        .login-logo-img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            display: block;
        }
    </style>
</head>

<body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="login-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/logo.png')}}" alt="Logo" class="login-logo-img">
                        </a>
                    </div>
                    <!-- /Logo -->

                    <h4 class="mb-2 text-center fw-bold">Welcome to SBESqr! 👋</h4>
                    <p class="mb-4 text-center">Please sign in to your account and start the adventure</p>

                    {{-- Form --}}
                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Email Input --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" :value="old('email')"
                                name="email" placeholder="Enter your Email" required autofocus autocomplete="email" />
                        </div>

                        {{-- Password Input --}}
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold" for="password">Password</label>

                                {{-- Forgot Password --}}
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('password.request') }}">
                                        <small>{{ __('Forgot your password?') }}</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="••••••••••••" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>

                    {{-- Register
                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="{{ route('register') }}">
                            <span>Create an account</span>
                        </a>
                    </p> --}}
                </div>
            </div>
            <!-- /Register -->

        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:jassetsDashboard/vendor/js/core.js -->
    <script src="{{ asset('assetsDashboard/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assetsDashboard/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assetsDashboard/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{ asset('assetsDashboard/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Livewire JS -->
    @livewireScripts

    <!-- Main JS -->
    <script src="{{ asset('assetsDashboard/js/main.js')}}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
