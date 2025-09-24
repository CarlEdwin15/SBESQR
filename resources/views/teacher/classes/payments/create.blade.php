@extends('layouts.main')

@section('hideNavbar', true)

@section('hideWrapper', true)

@section('content')
<h2>Create Payment for Class: {{ $class->formatted_grade_level }} - {{ $class->section }}</h2>

<form action="{{ route('teacher.payments.store', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Payment Name</label>
        <input type="text" name="payment_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Amount Due</label>
        <input type="number" name="amount_due" class="form-control" step="0.01" required>
    </div>
    <div class="form-group">
        <label>Due Date</label>
        <input type="date" name="due_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success mt-2">Create</button>
</form>
@endsection
