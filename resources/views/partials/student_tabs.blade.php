<!-- Tabs -->
<ul class="nav nav-pills mb-3" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile">
            <i class="bx bx-user-pin me-1"></i>
            <span class="d-none d-sm-block">Profile</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#classes">
            <i class="bx bx-book-content me-1"></i>
            <span class="d-none d-sm-block">Classes &amp; Grades</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parents">
            <i class="bx bx-user me-1"></i>
            <span class="d-none d-sm-block">Parents</span>
        </button>
    </li>
</ul>

<div class="card shadow">
    <div class="card-body">
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Profile -->
            <div class="tab-pane fade show active" id="profile">
                <h5 class="fw-bold text-primary mb-3">Student Information</h5>

                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Learner's Reference No.(LRN):</div>
                    <div class="col-sm-8">{{ $student->student_lrn }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Date of Birth:</div>
                    <div class="col-sm-8">
                        {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Sex:</div>
                    <div class="col-sm-8">{{ ucfirst($student->student_sex) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Age:</div>
                    <div class="col-sm-8">
                        {{ \Carbon\Carbon::parse($student->student_dob)->age }} years old
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Place of Birth:</div>
                    <div class="col-sm-8">{{ $student->address->pob }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Current Address:</div>
                    <div class="col-sm-8">
                        {{ $student->address->house_no ?? 'N/A' }},
                        {{ $student->address->street_name ?? 'N/A' }},
                        {{ $student->address->barangay ?? 'N/A' }},
                        {{ $student->address->municipality_city ?? 'N/A' }},
                        {{ $student->address->province ?? 'N/A' }},
                        {{ $student->address->zip_code ?? 'N/A' }}
                    </div>
                </div>

                <!-- QR Code -->
                <h5 class="fw-bold text-primary mt-4 mb-3">QR Code</h5>
                <div class="text-center">
                    {!! QrCode::size(200)->generate(json_encode(['student_id' => $student->id])) !!}
                </div>
            </div>

            <!-- Classes & Grades -->
            <div class="tab-pane fade" id="classes">
                <h5 class="fw-bold text-primary mb-3">Classes and Grades</h5>

                @if ($classHistory->isEmpty())
                    <p class="text-muted">No classes found for this student.</p>
                @else
                    <div class="accordion" id="classHistoryAccordion">
                        @foreach ($classHistory as $index => $classItem)
                            @php
                                $schoolYear =
                                    \App\Models\SchoolYear::find($classItem->pivot->school_year_id)?->school_year ??
                                    'N/A';
                                $status = $classItem->pivot->enrollment_status;
                                $badgeClass = match ($status) {
                                    'enrolled' => 'bg-success',
                                    'archived' => 'bg-warning',
                                    default => 'bg-secondary',
                                };
                                $subjectsWithGrades = $gradesByClass[$classItem->id] ?? [];
                            @endphp

                            <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button
                                        class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}">
                                        <span>
                                            {{ $schoolYear }} —
                                            {{ $classItem->formatted_grade_level }} -
                                            {{ $classItem->section }}
                                        </span>
                                        <div>
                                            <span class="badge {{ $badgeClass }} ms-3">{{ ucfirst($status) }}</span>
                                            @if ($status === 'enrolled')
                                                <span class="badge bg-primary ms-2">Current Class</span>
                                            @endif
                                        </div>
                                        @php
                                            $adviser = $classItem->advisers
                                                ->where('pivot.school_year_id', $classItem->pivot->school_year_id)
                                                ->first();
                                        @endphp
                                        <span>
                                            @if ($adviser)
                                                <small class="text-muted ms-2">Adviser:
                                                    {{ $adviser->full_name }}</small>
                                            @endif
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        @if (empty($subjectsWithGrades))
                                            <div class="p-3 mb-3 rounded-3 bg-light text-center border">
                                                <p class="text-warning fw-bold mb-0">No grades found for this school
                                                    year.</p>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                                                <h5 class="fw-bold text-primary">Grades</h5>
                                                @if (request()->is('teacher/*'))
                                                    <!-- Teacher-only export button -->
                                                    <a href="{{ route('teacher.student.card', [
                                                        'student_id' => $student->id,
                                                        'school_year' => $classItem->pivot->school_year_id,
                                                    ]) }}"
                                                        target="_blank" class="btn btn-success btn-sm">
                                                        <i class="bx bx-printer"></i> Export
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr class="text-center">
                                                            <th>Subject</th>
                                                            <th>Q1</th>
                                                            <th>Q2</th>
                                                            <th>Q3</th>
                                                            <th>Q4</th>
                                                            <th>Final Grade</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($subjectsWithGrades as $item)
                                                            <tr>
                                                                <td>{{ $item['subject'] }}</td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 1)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 2)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 3)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 4)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($item['final_average'] !== null)
                                                                        <strong>{{ $item['final_average'] }}</strong>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($item['remarks'] === 'passed')
                                                                        <span
                                                                            class="badge bg-label-success fw-bold">Passed</span>
                                                                    @elseif($item['remarks'] === 'failed')
                                                                        <span
                                                                            class="badge bg-label-danger fw-bold">Failed</span>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <h5 class="fw-bold text-primary mt-3 mb-3">General Average</h5>
                                            @if (!empty($generalAverages[$classItem->id]))
                                                @php $ga = $generalAverages[$classItem->id]; @endphp
                                                <div
                                                    class="p-3 rounded-3 border bg-light d-flex justify-content-between">
                                                    <span class="fw-bold">General Average:</span>
                                                    <span>
                                                        <strong>{{ $ga['general_average'] }}</strong>
                                                        @if ($ga['remarks'] === 'passed')
                                                            <span class="badge bg-label-success ms-2">Passed</span>
                                                        @else
                                                            <span class="badge bg-label-danger ms-2">Failed</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @else
                                                <div class="p-3 rounded-3 border bg-light text-center">
                                                    <span class="text-muted">General Average not available (incomplete
                                                        final grades).</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Parents -->
            <div class="tab-pane fade" id="parents">
                <h5 class="fw-bold text-primary mb-3">Parents</h5>
                @forelse ($student->parents as $parent)
                    <div class="p-2 border rounded-3 mb-2">
                        <strong>{{ $parent->full_name }}</strong><br>
                        <small class="text-muted">{{ $parent->email }}</small>
                    </div>
                @empty
                    <p class="text-muted">No parents linked.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
