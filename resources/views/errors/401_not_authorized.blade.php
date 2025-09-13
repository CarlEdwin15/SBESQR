@extends('layouts.main')

@section('hideNavbar', true)

@section('hideWrapper', true)

@section('content')
    <!-- Error -->
    <div class="text-center mt-5">
        <h1 class="mb-2 mx2 text-black" style="line-height: 6rem;font-size: 6rem;">401</h1>
        <h3 class="text-dark">You are not Authorized</h3>
        <p>Your email is not registered as a teacher or parent.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
        <div class="mt-3">
            <img src="{{ asset('assetsDashboard/img/illustrations/not_authorized.png') }}" alt="page-misc-error-light"
                width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png"
                data-app-light-img="illustrations/page-misc-error-light.png" />
        </div>
    </div>
    <!--/ Error -->
@endsection
