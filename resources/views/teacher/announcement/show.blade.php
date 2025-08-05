@extends('layouts.main')

@section('content')
<div class="container">
    <h2>{{ $announcement->title }}</h2>
    <p class="text-muted">
        Published on: {{ \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y | h:i A') }}
    </p>

    <div class="mt-3 border rounded p-3 bg-light" style="white-space: pre-wrap;">
        {!! $announcement->body !!}
    </div>
</div>
@endsection
