@extends('layouts.main')

@section('hideNavbar', true)

@section('hideWrapper', true)

@section('title', 'Account Banned')

@section('content')
    <!-- Error -->
    <div class="bg-white min-vh-100 py-5 d-flex flex-column justify-content-between">
        <div class="text-center">
            <h1 class="mb-2 mx2 text-black" style="line-height: 6rem; font-size: 6rem;">403</h1>
            <h3 class="text-danger"><i class='bx bxs-user-x fs-1 align-middle me-1'></i> Account Banned</h3>
            <p>Your account has been permanently banned. If you believe this is a mistake, please contact the administrator.
            </p>
            <div class="mt-3">
                <img src="{{ asset('assetsDashboard/img/illustrations/user_banned.jpg') }}" alt="account banned"
                    width="500" class="img-fluid" />
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Back to Login</a>
        </div>

        <!-- Footer -->
        <footer class="text-center py-3 bg-white">
            <small class="text-muted">© {{ date('Y') }} SBESqr. All rights reserved.</small>
        </footer>
    </div>
    <!--/ Error -->
@endsection
