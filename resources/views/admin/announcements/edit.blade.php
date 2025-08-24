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
    <div class="border rounded p-3 bg-light quill-content" id="edit-quill-editor">{!! old('body', $announcement->body ?? '') !!}</div>

    <!-- Hidden input to store HTML content -->
    <input type="hidden" name="body" id="edit-body">

    <!-- Client-side error display -->
    <div class="text-danger" id="edit-body-error" style="display: none;"></div>

    <!-- Server-side error display -->
    @error('body')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
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
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Font = Quill.import('formats/font');
            Font.whitelist = ['sans-serif', 'serif', 'monospace'];
            Quill.register(Font, true);

            const editQuill = new Quill('#edit-quill-editor', {
                theme: 'snow',
                placeholder: 'Write your announcement here...',
                modules: {
                    toolbar: {
                        container: [
                            [{
                                'font': Font.whitelist
                            }],
                            [{
                                'size': ['small', false, 'large', 'huge']
                            }],
                            ['bold', 'italic', 'underline'],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'align': []
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image'],
                            ['clean']
                        ],
                        handlers: {
                            image: function() {
                                const input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();

                                input.onchange = () => {
                                    const file = input.files[0];
                                    if (file) {
                                        const formData = new FormData();
                                        formData.append('image', file);

                                        fetch("{{ route('announcements.uploadImage') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: formData
                                            })
                                            .then(res => {
                                                if (!res.ok) throw res;
                                                return res.json();
                                            })
                                            .then(data => {
                                                if (data.url) {
                                                    const range = editQuill.getSelection();
                                                    editQuill.insertEmbed(range.index, 'image',
                                                        data.url);
                                                } else {
                                                    alert('Image upload failed');
                                                }
                                            })
                                            .catch(async (err) => {
                                                let msg = 'Image upload failed';
                                                if (err.json) {
                                                    const errorData = await err.json();
                                                    if (errorData.errors?.image) {
                                                        msg = errorData.errors.image.join(
                                                            ', ');
                                                    }
                                                }
                                                alert(msg);
                                            });
                                    }
                                };
                            }
                        }
                    }
                },
                formats: [
                    'font', 'size', 'bold', 'italic', 'underline',
                    'list', 'color', 'background',
                    'align', 'link', 'image'
                ]
            });

            const editForm = document.getElementById('editAnnouncementForm');
            const bodyInput = document.getElementById('edit-body');
            const errorDiv = document.getElementById('edit-body-error');

            editForm.addEventListener('submit', function(e) {
                const htmlContent = editQuill.root.innerHTML.trim();
                bodyInput.value = htmlContent;

                if (editQuill.getLength() <= 1 || htmlContent === '<p><br></p>') {
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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
