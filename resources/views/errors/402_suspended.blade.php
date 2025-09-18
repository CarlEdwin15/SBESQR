@extends('layouts.main')

@section('hideNavbar', true)

@section('hideWrapper', true)

@section('title', 'Account Suspended')

@section('content')
    <!-- Error -->
    <div class="bg-white min-vh-100 py-5 d-flex flex-column justify-content-between">
        <div class="text-center">
            <h1 class="mb-2 mx2 text-black" style="line-height: 6rem; font-size: 6rem;">402</h1>
            <h3 class="text-warning"><i class='bx bx-pause-circle fs-2 align-middle'></i> Account Suspended</h3>
            <p>Your account has been temporarily suspended. Please reach out to support for assistance.</p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Back to Login</a>
            <div class="mt-1">
                <img src="{{ asset('assetsDashboard/img/illustrations/user_suspended.jpg') }}" alt="account suspended"
                    width="500" class="img-fluid" />
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center py-3 bg-white">
            <small class="text-muted">© {{ date('Y') }} SBESqr. All rights reserved.</small>
        </footer>
    </div>
    <!--/ Error -->
@endsection
