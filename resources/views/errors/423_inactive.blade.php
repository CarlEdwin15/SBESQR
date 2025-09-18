@extends('layouts.main')

@section('hideNavbar', true)

@section('hideWrapper', true)

@section('title', 'Account Inactive')

@section('content')
    <!-- Error -->
    <div class="bg-white min-vh-100 py-5 d-flex flex-column justify-content-between">
        <div class="text-center">
            <h1 class="mb-2 mx2 text-black" style="line-height: 6rem; font-size: 6rem;">423</h1>
            <h3 class="text-info"><i class='bx bxs-shield-x fs-1 align-middle'></i> Account Not Activated</h3>
            <p>Your account is currently inactive. Please contact the administrator to activate it.</p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Back to Login</a>
            <div class="mt-4">
                <img src="{{ asset('assetsDashboard/img/illustrations/user_inactive.jpg') }}" alt="account inactive"
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
