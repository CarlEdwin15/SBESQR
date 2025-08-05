@extends('./layouts.main')

@section('title', 'Login')

@section('content')

    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="login-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="login-logo-img">
                        </a>
                    </div>
                    <!-- /Logo -->

                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    @if ($errors->any())
                        <div class="toast-container position-fixed top-0 end-0 p-4">
                            {{-- Toast for displaying errors --}}
                            <div class="toast show align-items-center bg-danger border-0" role="alert"
                                aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                        data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form id="formAuthentication" class="mb-0" action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Email Input --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" class="form-control" id="email" :value="old('email')"
                                    name="email" aria-describedby="email" placeholder="Enter your Email" required
                                    autofocus autocomplete="email" />
                            </div>
                        </div>

                        {{-- Password Input --}}
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold" for="password">Password</label>

                                {{-- Forgot Password --}}
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('password.request') }}">
                                        <small class="text-primary">{{ __('Forgot your password?') }}</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="Enter your Password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>


                        <p class="text-center text-info fw-bold mb-3">Or sign in with Google</p>

                        {{-- Google Login --}}
                        <div style="display: flex; justify-content: center; align-items: center; margin-top: 1rem;">
                            <a href="{{ route('google.login') }}" class="btn btn-light d-flex align-items-center mb-1"
                                style="gap: 10px; padding: 8px 16px;">
                                <img src="{{ asset('assetsDashboard/img/icons/brands/google.png') }}" alt="Google"
                                    style="height: 20px; width: 20px;">
                                <span class="fw-bold">Sign in with Google</span>
                            </a>
                        </div>

                        {{-- Facebook Login --}}
                        {{-- <div style="display: flex; justify-content: center; align-items: center; margin-top: 1rem;">
                            <a href="{{ route('facebook.login') }}" class="btn btn-light d-flex align-items-center"
                                style="gap: 10px; padding: 8px 16px;">
                                <img src="{{ asset('assetsDashboard/img/icons/brands/facebook1.png') }}" alt="Facebook"
                                    style="height: 20px; width: 20px;">
                                <span>Sign in with Facebook</span>
                            </a>
                        </div> --}}

                    </form>
                    {{-- /Form --}}

                </div>
            </div>
            <!-- /Register -->

        </div>
    </div>
    <!-- / Content -->

@endsection
@push('styles')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/pages/page-auth.css') }}" />

    <!-- Custom Styles -->
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

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
            box-shadow: 0 0 0 1000px white inset !important;
            -webkit-text-fill-color: #000 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
@endpush
