@extends('layouts.main')

@section('title', 'Forgot Password')

@section('hideNavbar', true)
@section('hideWrapper', true)

@section('content')
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <!-- Forgot Password Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="login-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="login-logo-img">
                        </a>
                    </div>
                    <!-- /Logo -->

                    <!-- Title -->
                    <h4 class="mb-2 text-center d-flex align-items-center justify-content-center">Forgot your
                        password?
                        <i class="d-flex align-items-center bx bx-lock me-2" style="font-size: 1.5rem;"></i>
                    </h4>
                    <p class="mb-4 text-center">Enter your email address below and we'll send you a link to reset your
                        password.</p>

                    <!-- Success Message -->
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form id="forgotPasswordForm" class="mb-3" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@example.com" required autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit"
                                class="btn btn-primary d-inline-flex align-items-center justify-content-center d-grid w-100">
                                <i class="bx bx-mail-send me-2"></i> Send Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}"
                            class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100">
                            <i class="bx bx-chevrons-left me-2"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
            <!-- / Forgot Password Card -->
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

        /* Ensure the form stays above the overlay */
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
