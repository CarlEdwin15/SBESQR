<div class="mb-3">
    <label for="title">Title</label>
    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control"
        required>
    @error('title')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="body">Body</label>

    <!-- Quill Visible Editor -->
    <div id="quill-editor" style="height: 200px;">{!! old('body', $announcement->body ?? '') !!}</div>

    <!-- Hidden input to store HTML content -->
    <input type="hidden" name="body" id="body">

    <!-- Client-side error display -->
    <div class="text-danger" id="body-error" style="display: none;"></div>

    <!-- Server-side error display -->
    @error('body')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 row">
    <div class="col">
        <label class="form-label">Recipients</label>
        <div>
            @foreach (['teacher' => 'Teachers', 'parent' => 'Parents', 'all' => 'All'] as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="recipients" id="recipients_{{ $value }}"
                        value="{{ $value }}"
                        {{ old('recipients', $announcement->recipients ?? 'all') === $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="recipients_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
        @error('recipients')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

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
            class="form-control" min="{{ now()->format('Y-m-d') }}">
        @error('effective_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date"
            value="{{ old('end_date', isset($announcement->end_date) ? \Carbon\Carbon::parse($announcement->end_date)->format('Y-m-d') : '') }}"
            class="form-control" min="{{ now()->format('Y-m-d') }}">
        @error('end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>



@push('scripts')
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Font = Quill.import('formats/font');
            Font.whitelist = ['sans-serif', 'serif', 'monospace'];
            Quill.register(Font, true);

            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write your announcement here...',
                modules: {
                    toolbar: [
                        [{
                            'font': Font.whitelist
                        }],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        ['bold', 'italic', 'underline'],
                        [{
                            'color': []
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['clean']
                    ]
                },
                formats: ['font', 'size', 'bold', 'italic', 'underline', 'list', 'color']
            });

            const form = document.getElementById('createAnnouncementForm');
            const bodyInput = document.getElementById('body');
            const errorDiv = document.getElementById('body-error');

            form.addEventListener('submit', function(e) {
                const htmlContent = quill.root.innerHTML.trim();
                bodyInput.value = htmlContent;

                if (quill.getLength() <= 1 || htmlContent === '<p><br></p>') {
                    e.preventDefault();
                    errorDiv.textContent = 'The Body field is required.';
                    errorDiv.style.display = 'block';
                    return;
                }

                errorDiv.style.display = 'none';
            });
        });
    </script>
@endpush


@push('styles')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
