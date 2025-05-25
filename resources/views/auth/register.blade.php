<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style customizer-hide" dir="ltr"
    data-theme="theme-default" data-assetsDashboard-path="./assetsDashboard/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Register') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assetsDashboard/img/favicon/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="./assetsDashboard/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assetsDashboard/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assetsDashboard/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assetsDashboard/css/demo.css" />

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="./assetsDashboard/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="./assetsDashboard/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="./assetsDashboard/js/config.js"></script>
    <style>
        body {
            background: url('assets/img/hero-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 150vh;
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
            height: auto;
            width: 100%;
            max-width: 1500px;
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

        .input-group-row {
            display: flex;
            gap: 10px;
        }

        .input-group-row .form-control {
            flex: 2;
        }
    </style>
</head>

{{-- <body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center" style="padding-bottom: 40px">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2 d-flex align-items-center">
                            <img src="assets/img/logo.png" alt="Logo" width="120" height="120"
                                style="object-fit: contain;">
                            <span class="app-brand-text demo text-body fw-bolder"
                                style="padding-left: 10px; font-family:'Times New Roman', Times, serif">SBESqr</span>
                        </a>
                    </div>

                    <!-- /Logo -->
                    <h4 class="mb-2" style="font-weight: bold; text-align: center">Welcome to SBESqr! 👋</h4>


                    {{-- Form --}}
                    <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="input-group-row">
                            <!-- First Name Input -->
                            <div class="mb-3">
                                <label for="firstName" class="form-label" style="font-weight: bold">First Name</label>
                                <input type="firstName" class="form-control" id="firstName" :value="old('firstName')"
                                    name="firstName" placeholder="Enter your First Name" required autofocus
                                    autocomplete="firstName" />
                            </div>

                            <!-- Last Name Input -->
                            <div class="mb-3">
                                <label for="lastName" class="form-label" style="font-weight: bold">Last Name</label>
                                <input type="lastName" class="form-control" id="lastName" :value="old('lastName')"
                                    name="lastName" placeholder="Enter your Last Name" required autofocus
                                    autocomplete="lastName" />
                            </div>
                        </div>
                        <!-- Middle Initial Input -->
                        <div class="mb-3">
                            <label for="middleName" class="form-label" style="font-weight: bold">Middle
                                Initial</label>
                            <input type="middleName" class="form-control" id="middleName"
                                :value="old('middleName')" name="middleName"
                                placeholder="Enter your Middle Initial" required autofocus
                                autocomplete="middleName" />
                        </div>

                        <!-- Phone Number Input -->
                        <div class="mb-3">
                            <label for="phone" class="form-label" style="font-weight: bold">Phone Number</label>
                            <input type="phone" class="form-control" id="phone" :value="old('phone')"
                                name="phone" placeholder="Enter your Phone Number" required autofocus
                                autocomplete="phone" />
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label" style="font-weight: bold">Email</label>
                            <input type="email" class="form-control" id="email" :value="old('email')"
                                name="email" placeholder="Enter your Email" required autofocus
                                autocomplete="email" />
                        </div>


                        <!-- Address Input -->
                        <div class="mb-3">
                            <label for="address" class="form-label" style="font-weight: bold">Address</label>
                            <input type="address" class="form-control" id="address" :value="old('address')"
                                name="address" placeholder="Enter your Address" required autofocus
                                autocomplete="address" />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password" style="font-weight: bold">Password</label>
                            </div>

                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" required
                                    autocomplete="new-password" placeholder="Enter your Password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password_confirmation"
                                    style="font-weight: bold">Confirm Password</label>
                            </div>

                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm your Password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                        </div>
                    </form>

                    {{-- Sign in --}}
                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">
                            <span>Sign in</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assetsDashboard/vendor/js/core.js -->
    <script src="./assetsDashboard/vendor/libs/jquery/jquery.js"></script>
    <script src="./assetsDashboard/vendor/libs/popper/popper.js"></script>
    <script src="./assetsDashboard/vendor/js/bootstrap.js"></script>
    <script src="./assetsDashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assetsDashboard/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Livewire JS -->
    @livewireScripts

    <!-- Main JS -->
    <script src="./assetsDashboard/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body> --}}

</html>
