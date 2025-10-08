@extends('layouts.main')

@section('hideNavbar', true)
@section('hideWrapper', true)
@section('title', 'Account Not Authorized')

@section('content')
    <!-- Error -->
    <div class="text-center mt-5">
        <h1 class="mb-2 text-black" style="line-height: 6rem; font-size: 6rem;">401</h1>
        <h3 class="text-dark mb-3">Access Not Authorized</h3>

        <p class="text-muted mb-4">
            Your Google account is not registered in our system.<br>
            Please ensure you are using an email associated with an <strong>Admin</strong>, <strong>Teacher</strong>, or
            <strong>Parent</strong> account.
        </p>

        <a href="{{ url('/') }}" class="btn btn-primary">Return to Home</a>

        <div class="mt-4">
            <img src="{{ asset('assetsDashboard/img/illustrations/not_authorized.png') }}" alt="Not Authorized Illustration"
                width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png"
                data-app-light-img="illustrations/page-misc-error-light.png">
        </div>
    </div>
    <!--/ Error -->
@endsection
