@extends('layouts.main')

@section('title', 'Reset Password')

@section('hideNavbar', true)
@section('hideWrapper', true)

@section('content')
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <!-- Reset Password Card -->
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
                    <h4 class="mb-2 text-center d-flex align-items-center justify-content-center">
                        Reset your password
                        <i class="bx bx-key ms-2" style="font-size: 1.5rem;"></i>
                    </h4>
                    <p class="mb-4 text-center">
                        Please enter your new password below to regain access to your account.
                    </p>

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ old('email', $request->email) }}" required autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3 form-password-toggle">
                            <label for="password" class="form-label fw-bold">New Password</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Enter new password" required>
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3 form-password-toggle">
                            <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Confirm new password" required>
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Alert message -->
                        <div id="passwordAlert" class="alert alert-danger d-none mt-3" role="alert"></div>

                        <!-- Submit -->
                        <div class="mb-3">
                            <button type="submit"
                                class="btn btn-primary d-inline-flex align-items-center justify-content-center d-grid w-100">
                                <i class="bx bx-refresh me-2"></i> Reset Password
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}"
                                class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100">
                                <i class="bx bx-chevrons-left me-2"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- / Reset Password Card -->
        </div>
    </div>
    <!-- / Content -->
@endsection

@push('styles')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/pages/page-auth.css') }}" />

    <!-- Custom Styles -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            background: url('{{ asset('assets/img/hero-bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
        }

        /* Dark overlay that covers full scroll height */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        /* Wrapper */
        .authentication-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            max-height: 200px;
            padding-bottom: 40px;
        }

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

        @media (max-width: 576px) {
            .authentication-wrapper {
                padding: 20px;
            }

            .login-logo-img {
                width: 120px;
                height: 120px;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Password Validation Script -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const alertBox = document.getElementById('passwordAlert');
            alertBox.classList.add('d-none');
            alertBox.innerHTML = '';

            const rules = [{
                    regex: /.{8,}/,
                    message: 'At least 8 characters long'
                },
                {
                    regex: /[A-Z]/,
                    message: 'At least one uppercase letter (A–Z)'
                },
                {
                    regex: /[a-z]/,
                    message: 'At least one lowercase letter (a–z)'
                },
                {
                    regex: /[0-9]/,
                    message: 'At least one number (0–9)'
                },
                {
                    regex: /[@$!%*#?&]/,
                    message: 'At least one special character (@$!%*#?&)'
                },
            ];

            const failed = rules.filter(r => !r.regex.test(password)).map(r => r.message);

            if (failed.length > 0) {
                e.preventDefault();
                alertBox.innerHTML = '<strong>Password must include:</strong><ul>' + failed.map(f =>
                    `<li>${f}</li>`).join('') + '</ul>';
                alertBox.classList.remove('d-none');
                return;
            }

            if (password !== confirm) {
                e.preventDefault();
                alertBox.innerHTML = '<strong>Passwords do not match.</strong>';
                alertBox.classList.remove('d-none');
                return;
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
@endpush
