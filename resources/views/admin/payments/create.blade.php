@csrf
<div class="mb-3">
    <label>School Year</label>
    <select name="school_year_id" class="form-control" required>
        @foreach($schoolYears as $year)
            <option value="{{ $year->id }}"
                {{ old('school_year_id', $defaultSchoolYearModel->id ?? null) == $year->id ? 'selected' : '' }}>
                {{ $year->school_year }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Class</label>
    <select name="class_id" class="form-control" required>
        @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ old('class_id', $payment->class_id ?? '') == $class->id ? 'selected' : '' }}>
                {{ $class->formatted_grade_level }} - {{ $class->section }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Student</label>
    <select name="student_id" class="form-control" required>
        @foreach($students as $student)
            <option value="{{ $student->id }}" {{ old('student_id', $payment->student_id ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->full_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Payment Name</label>
    <input type="text" name="payment_name" class="form-control" value="{{ old('payment_name', $payment->payment_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Amount Due</label>
    <input type="number" name="amount_due" class="form-control" step="0.01" value="{{ old('amount_due', $payment->amount_due ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Date Created</label>
    <input type="date" name="date_created" class="form-control" value="{{ old('date_created', $payment->date_created ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Due Date</label>
    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $payment->due_date ?? '') }}" required>
</div>

@if(isset($payment))
<div class="mb-3">
    <label>Amount Paid</label>
    <input type="number" name="amount_paid" class="form-control" step="0.01" value="{{ old('amount_paid', $payment->amount_paid) }}">
</div>

<div class="mb-3">
    <label>Date Paid</label>
    <input type="date" name="date_paid" class="form-control" value="{{ old('date_paid', $payment->date_paid) }}">
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control" required>
        @foreach(['unpaid', 'partial', 'paid'] as $status)
            <option value="{{ $status }}" {{ old('status', $payment->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</div>
@endif

<div class="mb-3">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks', $payment->remarks ?? '') }}</textarea>
</div>

<button class="btn btn-success" type="submit">Save</button>
