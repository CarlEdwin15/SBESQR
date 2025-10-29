{{-- Recipients --}}
<div class="mb-3 position-relative">
    <label for="edit-recipients" class="form-label fw-bold d-flex justify-content-between align-items-center">
        <span>Select Recipients</span>
        <button type="button" id="editOpenUserModal" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-people"></i> Choose from Users List
        </button>
    </label>

    <select id="edit-recipients" name="recipients[]" multiple required>
        @if (isset($announcement) && $announcement->recipients->count())
            @foreach ($announcement->recipients as $recipient)
                <option value="{{ $recipient->id }}" selected>
                    {{ $recipient->firstName }} {{ $recipient->lastName }} ({{ $recipient->email }})
                </option>
            @endforeach
        @endif
    </select>

    <small class="form-text text-muted">
        You can search by name/email or click "Choose from list" to select multiple users.
    </small>
</div>

<!-- User Selection Modal for Edit Form -->
<div class="modal fade" id="editUserSelectModal" tabindex="-1" aria-labelledby="editUserSelectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserSelectModalLabel">Select Recipients</h5>
            </div>

            <div class="modal-body">
                <div class="col ms-auto mb-2">
                    <input type="text" id="editUserSearch" class="form-control" placeholder="Search name/email...">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="col-auto">
                        <select id="editTableLength" class="form-select w-auto">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select id="editRoleFilter" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4" />

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <input type="checkbox" id="editSelectAllUsers" class="form-check-input me-2">
                        <label for="editSelectAllUsers" class="form-check-label">Select All</label>
                    </div>
                </div>

                <div id="editUserListContainer" class="border rounded p-2" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-muted text-center">Loading users...</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="editTableInfo" class="text-muted small"></div>
                    <nav>
                        <ul class="pagination pagination mb-0" id="editPagination"></ul>
                    </nav>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="editAddSelectedUsers" class="btn btn-primary">Add Selected</button>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="title">Subject</label>
    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control"
        required>
    @error('title')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

{{-- Body --}}
<div class="mb-3">
    <label for="body">Body</label>

    <!-- Quill Visible Editor -->
    <div id="edit-quill-editor" style="height: 200px;">{!! old('body', $announcement->body ?? '') !!}</div>

    <!-- Hidden input to store HTML content -->
    <input type="hidden" name="body" id="edit-body">

    <!-- Client-side error display -->
    <div class="text-danger" id="edit-body-error" style="display: none;"></div>

    <!-- Server-side error display -->
    @error('body')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 row">
    <div class="col">
        <label for="school_year_id">School Year</label>
        <select name="school_year_id" class="form-select">
            <option value="">None</option>
            @foreach ($schoolYears->filter(function ($year) {
        $now = now();
        $next = now()->copy()->addYear();
        return in_array($year->school_year, [$now->year . '-' . ($now->year + 1), $next->year . '-' . ($next->year + 1)]);
    }) as $year)
                <option value="{{ $year->id }}"
                    @if (isset($announcement)) {{ old('school_year_id', $announcement->school_year_id) == $year->id ? 'selected' : '' }}
                    @elseif (isset($defaultSchoolYear))
                        {{ old('school_year_id', $defaultSchoolYear->id) == $year->id ? 'selected' : '' }} @endif>
                    {{ $year->school_year }}
                </option>
            @endforeach
        </select>
        @error('school_year_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3 row">
    <div class="col">
        <label for="effective_date">Effective From</label>
        <input type="date" name="effective_date"
            value="{{ old('effective_date', isset($announcement->effective_date) ? \Carbon\Carbon::parse($announcement->effective_date)->format('Y-m-d') : '') }}"
            class="form-control" min="{{ now()->format('Y-m-d') }}" required>
        @error('effective_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date"
            value="{{ old('end_date', isset($announcement->end_date) ? \Carbon\Carbon::parse($announcement->end_date)->format('Y-m-d') : '') }}"
            class="form-control" min="{{ now()->format('Y-m-d') }}" required>
        @error('end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('styles')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-control {
            min-height: 42px;
            border-radius: 0.375rem;
            border-color: #ced4da;
            font-size: 0.95rem;
        }

        .ts-control input {
            padding: 4px !important;
        }

        .ts-dropdown .option {
            padding: 6px 10px;
        }

        #editUserListContainer .form-check:hover {
            background-color: #f8f9fa;
        }

        #editPagination .page-link {
            cursor: pointer;
        }
    </style>
@endpush
