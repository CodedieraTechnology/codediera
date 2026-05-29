@csrf
<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
</div>
@php($instructors = $instructors ?? collect())
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <label class="form-label mb-0" for="instructor_user_id">Instructor</label>
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#createInstructorModal">Create instructor</button>
        </div>
        <select class="form-select" id="instructor_user_id" name="instructor_user_id">
            <option value="">None</option>
            @php($selInstructor = old('instructor_user_id', $item->instructor_user_id ?? ''))
            @foreach($instructors as $u)
                <option value="{{ $u->id }}" @selected((string)$selInstructor === (string)$u->id)>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="total_hours">Total Hours</label>
        <input class="form-control" id="total_hours" name="total_hours" type="number" min="0" step="0.1" value="{{ old('total_hours', $item->total_hours ?? '') }}" placeholder="e.g. 12.5">
    </div>
</div>

@push('modals')
    <div class="modal fade" id="createInstructorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="fw-semibold">Create Instructor</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="createInstructorError"></div>
                    <div class="mb-3">
                        <label class="form-label" for="new_instructor_name">Name</label>
                        <input class="form-control" id="new_instructor_name" type="text" autocomplete="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="new_instructor_email">Email</label>
                        <input class="form-control" id="new_instructor_email" type="email" autocomplete="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="new_instructor_password">Password</label>
                        <div class="input-group">
                            <input class="form-control" id="new_instructor_password" type="password" autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_instructor_password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="new_instructor_password_confirmation">Confirm password</label>
                        <div class="input-group">
                            <input class="form-control" id="new_instructor_password_confirmation" type="password" autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_instructor_password_confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="createInstructorBtn" type="button">Create</button>
                </div>
            </div>
        </div>
    </div>
@endpush
<div class="mb-3">
    <div class="fw-semibold mb-2">Course Preview</div>
    @php($existingPreview = ($item?->exists ?? false) ? $item->lessons()->where('is_preview', true)->where('is_active', true)->orderBy('sort_order')->first() : null)
    <div class="row g-2">
        <div class="col-12 col-md-6">
            <label class="form-label" for="preview_title">Preview lesson title</label>
            <input class="form-control" id="preview_title" name="preview_title" type="text" value="{{ old('preview_title', $existingPreview?->title ?? '') }}" placeholder="e.g. Introduction">
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="preview_link">Preview lesson link (YouTube)</label>
            <input class="form-control" id="preview_link" name="preview_link" type="url" value="{{ old('preview_link', $existingPreview?->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=...">
        </div>
    </div>
</div>
<div class="mb-3" id="lessons">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
        <label class="form-label mb-0">Lessons</label>
        <button class="btn btn-sm btn-outline-primary" id="addLessonRowBtn" type="button">Add lesson</button>
    </div>
    @php($existingLessons = ($item?->exists ?? false) ? $item->lessons()->where('is_preview', false)->orderBy('sort_order')->orderByDesc('id')->get() : collect())
    @php($oldLessons = old('lessons'))
    @php($lessonRows = is_array($oldLessons) ? $oldLessons : $existingLessons->map(function ($l) { return ['id' => $l->id, 'title' => $l->title, 'brief_info' => $l->brief_info, 'video_url' => $l->video_url, 'pdf_path' => $l->pdf_path, 'image_path' => $l->image_path, 'sort_order' => $l->sort_order, 'is_active' => $l->is_active]; })->all())
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" id="lessonsTable">
            <thead>
            <tr>
                <th style="min-width: 220px;">Title</th>
                <th style="min-width: 260px;">Brief info</th>
                <th style="min-width: 240px;">Video link (optional)</th>
                <th style="min-width: 220px;">PDF</th>
                <th style="min-width: 220px;">Image</th>
                <th style="width: 120px;">Order</th>
                <th style="width: 90px;" class="text-center">Active</th>
                <th style="width: 60px;"></th>
            </tr>
            </thead>
            <tbody id="lessonsTbody">
            @foreach($lessonRows as $idx => $row)
                @php($rowId = is_array($row) ? (string)($row['id'] ?? '') : '')
                @php($rowTitle = is_array($row) ? trim((string)($row['title'] ?? '')) : '')
                @php($rowBrief = is_array($row) ? (string)($row['brief_info'] ?? '') : '')
                @php($rowVideo = is_array($row) ? (string)($row['video_url'] ?? '') : '')
                @php($rowPdfPath = is_array($row) ? (string)($row['pdf_path'] ?? '') : '')
                @php($rowImagePath = is_array($row) ? (string)($row['image_path'] ?? '') : '')
                @php($rowSort = is_array($row) ? (string)($row['sort_order'] ?? '') : '')
                @php($rowActive = is_array($row) ? ($row['is_active'] ?? true) : true)
                <tr data-lesson-index="{{ $idx }}">
                    <td>
                        <input name="lessons[{{ $idx }}][id]" type="hidden" value="{{ $rowId }}">
                        <input class="form-control" name="lessons[{{ $idx }}][title]" type="text" value="{{ $rowTitle }}" required>
                    </td>
                    <td>
                        <textarea class="form-control" name="lessons[{{ $idx }}][brief_info]" rows="2" placeholder="Short description">{{ $rowBrief }}</textarea>
                    </td>
                    <td>
                        <input class="form-control" name="lessons[{{ $idx }}][video_url]" type="url" value="{{ $rowVideo }}" placeholder="https://www.youtube.com/watch?v=...">
                    </td>
                    <td>
                        @if($rowPdfPath)
                            <div class="small mb-1">
                                <a href="{{ asset('storage/' . $rowPdfPath) }}" target="_blank" rel="noopener">View current PDF</a>
                            </div>
                        @endif
                        <input class="form-control" name="lessons[{{ $idx }}][pdf]" type="file" accept="application/pdf">
                    </td>
                    <td>
                        @if($rowImagePath)
                            <div class="mb-1">
                                <img src="{{ asset('storage/' . $rowImagePath) }}" alt="Lesson image" style="max-width: 70px; height: auto;" class="rounded border">
                            </div>
                        @endif
                        <input class="form-control" name="lessons[{{ $idx }}][image]" type="file" accept="image/*">
                    </td>
                    <td>
                        <input class="form-control" name="lessons[{{ $idx }}][sort_order]" type="number" min="0" step="1" value="{{ $rowSort }}" placeholder="auto">
                    </td>
                    <td class="text-center">
                        <input class="form-check-input" name="lessons[{{ $idx }}][is_active]" type="checkbox" value="1" {{ $rowActive ? 'checked' : '' }}>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger removeLessonRowBtn" type="button">Remove</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Ratings</label>
    @php($ratingStats = ($item?->exists ?? false) ? \App\Models\DigitalSkillsRating::query()->where('digital_skills_item_id', $item->id)->selectRaw('COUNT(*) as c, AVG(rating) as a')->first() : null)
    @php($ratingsCount = $ratingStats ? (int)($ratingStats->c ?? 0) : 0)
    @php($avgRating = ($ratingsCount > 0 && $ratingStats && !is_null($ratingStats->a)) ? round((float)$ratingStats->a, 1) : null)
    @php($recentRatings = ($item?->exists ?? false) ? \App\Models\DigitalSkillsRating::query()->where('digital_skills_item_id', $item->id)->orderByDesc('id')->limit(10)->get() : collect())
    <div class="d-flex flex-wrap gap-2 mb-2">
        @if(!is_null($avgRating))
            <span class="badge text-bg-dark">Average: {{ $avgRating }}/5</span>
            <span class="badge text-bg-secondary">Count: {{ $ratingsCount }}</span>
        @else
            <span class="badge text-bg-secondary">No ratings yet</span>
        @endif
    </div>
    @if($recentRatings->count())
        <div class="list-group">
            @foreach($recentRatings as $r)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="fw-semibold">{{ $r->name }}</div>
                        <div class="badge text-bg-dark">{{ (int) $r->rating }}/5</div>
                    </div>
                    @if($r->comment)
                        <div class="text-muted small mt-1">{{ $r->comment }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
<div class="mb-3">
    <label class="form-label" for="image">Image (optional)</label>
    @if(!empty($item?->image_path))
        <div class="mb-2">
            <img id="digitalSkillCurrentImage" src="{{ asset('storage/' . $item->image_path) }}" alt="Skill image" style="max-width: 180px; height: auto;" class="rounded border">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" id="remove_image" name="remove_image" type="checkbox" value="1">
            <label class="form-check-label" for="remove_image">Remove image</label>
        </div>
    @endif
    <input class="form-control" id="image" name="image" type="file" accept="image/*">
    <div class="mt-2">
        <img id="digitalSkillImagePreview" alt="Preview" style="max-width: 180px; height: auto;" class="rounded border d-none">
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="price">Price (optional)</label>
        <input class="form-control" id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $item->price ?? '') }}" placeholder="0.00">
    </div>
    <div class="col-md-4 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_free" name="is_free" type="checkbox" value="1" {{ old('is_free', ($item->is_free ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_free">Free</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" step="1" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($item->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var createInstructorBtn = document.getElementById('createInstructorBtn');
            var instructorSelect = document.getElementById('instructor_user_id');
            var instructorErr = document.getElementById('createInstructorError');
            var instructorNameEl = document.getElementById('new_instructor_name');
            var instructorEmailEl = document.getElementById('new_instructor_email');
            var instructorPassEl = document.getElementById('new_instructor_password');
            var instructorPass2El = document.getElementById('new_instructor_password_confirmation');

            if (createInstructorBtn && instructorSelect && instructorErr && instructorNameEl && instructorEmailEl && instructorPassEl && instructorPass2El) {
                createInstructorBtn.addEventListener('click', async function () {
                    instructorErr.classList.add('d-none');
                    instructorErr.textContent = '';
                    createInstructorBtn.setAttribute('disabled', 'disabled');

                    try {
                        var res = await fetch("{{ route('admin.instructors.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            body: JSON.stringify({
                                name: instructorNameEl.value,
                                email: instructorEmailEl.value,
                                password: instructorPassEl.value,
                                password_confirmation: instructorPass2El.value,
                            }),
                        });

                        var payload = await res.json().catch(function () { return null; });
                        if (!res.ok) {
                            var msg = 'Unable to create instructor.';
                            if (payload && payload.errors) {
                                msg = Object.values(payload.errors).flat().join(' ');
                            } else if (payload && payload.message) {
                                msg = payload.message;
                            }
                            instructorErr.textContent = msg;
                            instructorErr.classList.remove('d-none');
                            return;
                        }

                        if (!payload || !payload.id) {
                            instructorErr.textContent = 'Unable to create instructor.';
                            instructorErr.classList.remove('d-none');
                            return;
                        }

                        var opt = document.createElement('option');
                        opt.value = String(payload.id);
                        opt.textContent = payload.name + ' (' + payload.email + ')';
                        instructorSelect.appendChild(opt);
                        instructorSelect.value = String(payload.id);

                        instructorNameEl.value = '';
                        instructorEmailEl.value = '';
                        instructorPassEl.value = '';
                        instructorPass2El.value = '';

                        if (window.bootstrap && window.bootstrap.Modal) {
                            var modalEl = document.getElementById('createInstructorModal');
                            var instance = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
                            instance.hide();
                        }
                    } finally {
                        createInstructorBtn.removeAttribute('disabled');
                    }
                });
            }

            var lessonsTbody = document.getElementById('lessonsTbody');
            var addLessonRowBtn = document.getElementById('addLessonRowBtn');

            function bindRemoveLessonButtons() {
                var btns = document.querySelectorAll('.removeLessonRowBtn');
                btns.forEach(function (btn) {
                    if (btn.dataset.bound === '1') return;
                    btn.dataset.bound = '1';
                    btn.addEventListener('click', function () {
                        var tr = btn.closest('tr');
                        if (tr) tr.remove();
                    });
                });
            }

            function nextLessonIndex() {
                if (!lessonsTbody) return 0;
                var rows = lessonsTbody.querySelectorAll('tr[data-lesson-index]');
                var max = -1;
                rows.forEach(function (r) {
                    var n = parseInt(r.getAttribute('data-lesson-index') || '0', 10);
                    if (!isNaN(n) && n > max) max = n;
                });
                return max + 1;
            }

            function addLessonRow() {
                if (!lessonsTbody) return;
                var idx = nextLessonIndex();
                var html = ''
                    + '<tr data-lesson-index="' + idx + '">'
                    + '<td><input name="lessons[' + idx + '][id]" type="hidden" value=""><input class="form-control" name="lessons[' + idx + '][title]" type="text" required></td>'
                    + '<td><textarea class="form-control" name="lessons[' + idx + '][brief_info]" rows="2" placeholder="Short description"></textarea></td>'
                    + '<td><input class=\"form-control\" name=\"lessons[' + idx + '][video_url]\" type=\"url\" placeholder=\"https://www.youtube.com/watch?v=...\"></td>'
                    + '<td><input class=\"form-control\" name=\"lessons[' + idx + '][pdf]\" type=\"file\" accept=\"application/pdf\"></td>'
                    + '<td><input class=\"form-control\" name=\"lessons[' + idx + '][image]\" type=\"file\" accept=\"image/*\"></td>'
                    + '<td><input class=\"form-control\" name=\"lessons[' + idx + '][sort_order]\" type=\"number\" min=\"0\" step=\"1\" placeholder=\"auto\"></td>'
                    + '<td class=\"text-center\"><input class=\"form-check-input\" name=\"lessons[' + idx + '][is_active]\" type=\"checkbox\" value=\"1\" checked></td>'
                    + '<td class=\"text-end\"><button class=\"btn btn-sm btn-outline-danger removeLessonRowBtn\" type=\"button\">Remove</button></td>'
                    + '</tr>';
                lessonsTbody.insertAdjacentHTML('beforeend', html);
                bindRemoveLessonButtons();
            }

            if (addLessonRowBtn) {
                addLessonRowBtn.addEventListener('click', addLessonRow);
            }
            bindRemoveLessonButtons();

            var freeEl = document.getElementById('is_free');
            var priceEl = document.getElementById('price');
            if (!freeEl || !priceEl) return;

            function sync() {
                if (freeEl.checked) {
                    priceEl.value = '';
                    priceEl.setAttribute('disabled', 'disabled');
                } else {
                    priceEl.removeAttribute('disabled');
                }
            }

            freeEl.addEventListener('change', sync);
            sync();

            document.querySelectorAll('.toggle-password').forEach(function (button) {
                button.addEventListener('click', function () {
                    var targetId = button.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    if (input) {
                        var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);
                        if (type === 'password') {
                            button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
                        } else {
                            button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.831.518l-1.02-1.02A8.951 8.951 0 0 1 8 1c5.279 0 9 5.148 9 5.5 0 .257-.461.875-1.042 1.486L13.359 11.24zM6 8a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"/><path d="M11.612 9.564 11.24 9.192c.224-.363.372-.78.372-1.228a4 4 0 1 0-7.828 1.18L2.23 7.585A8.997 8.997 0 0 1 8 2.25c4.717 0 8 4.75 8 5 0 .07-.154.385-.457.812l-.93-.93z"/><path d="M5.525 7.646 1.354 3.475a.5.5 0 1 0-.708.708l1.35 1.35C.851 6.586 0 8 0 8s3 5.5 8 5.5a9.06 9.06 0 0 0 4.14-.949l2.136 2.136a.5.5 0 0 0 .707-.707l-2.14-2.14L5.525 7.646zm2.463 3.65c-.328 0-.648-.067-.946-.188L8.71 9.44c.484.28 1.05.3 1.29.083l-.707-.707c-.453-.138-.813-.498-.951-.951l-.707-.707c-.014.24-.035.806.245 1.29.138.224.37.37.594.37zM4.77 7.07l-.768-.768C3.62 6.67 3.5 7 3.5 7.5c0 1.24 1.01 2.25 2.25 2.25.5 0 .83-.12.98-.182l-.666-.666c-.156.064-.325.098-.564.098a1.25 1.25 0 0 1-1.25-1.25c0-.239.034-.408.098-.564L4.77 7.07z"/></svg>`;
                        }
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.ClassicEditor) return;

            var uploadUrl = @json(route('admin.digital-skills.upload-media'));
            var csrfToken = @json(csrf_token());

            function makeUploadAdapter(loader) {
                var adapter = {
                    xhr: null,
                    upload: function () {
                        return loader.file.then(function (file) {
                            return new Promise(function (resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                adapter.xhr = xhr;
                                xhr.open('POST', uploadUrl, true);
                                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                                xhr.setRequestHeader('Accept', 'application/json');
                                xhr.responseType = 'json';
                                xhr.timeout = 120000;

                                xhr.addEventListener('error', function () { reject('Upload failed'); });
                                xhr.addEventListener('abort', function () { reject('Upload aborted'); });
                                xhr.addEventListener('timeout', function () { reject('Upload timeout'); });

                                xhr.addEventListener('load', function () {
                                    var resp = xhr.response;
                                    if (!resp || xhr.status < 200 || xhr.status >= 300) {
                                        reject('Upload failed');
                                        return;
                                    }
                                    if (!resp.url) {
                                        reject('Upload failed');
                                        return;
                                    }
                                    resolve({ default: resp.url });
                                });

                                var data = new FormData();
                                data.append('upload', file);
                                xhr.send(data);
                            });
                        });
                    },
                    abort: function () {
                        if (adapter.xhr) adapter.xhr.abort();
                    }
                };
                return adapter;
            }

            function uploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                    return makeUploadAdapter(loader);
                };
            }

            var mediaProviders = [
                {
                    name: 'youtube',
                    url: /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})(?:[&?].*)?$/i,
                    html: function (match) {
                        var id = match[1];
                        return '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">'
                            + '<iframe src="https://www.youtube.com/embed/' + id + '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>'
                            + '</div>';
                    }
                },
                {
                    name: 'vimeo',
                    url: /^(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(?:video\/)?(\d+)(?:\?.*)?$/i,
                    html: function (match) {
                        var id = match[1];
                        return '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">'
                            + '<iframe src="https://player.vimeo.com/video/' + id + '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>'
                            + '</div>';
                    }
                }
            ];

            function initEditor(selector, toolbar) {
                var el = document.querySelector(selector);
                if (!el) return;

                ClassicEditor
                    .create(el, {
                        extraPlugins: [uploadAdapterPlugin],
                        toolbar: toolbar,
                        mediaEmbed: {
                            previewsInData: true,
                            removeProviders: ['youtube', 'vimeo'],
                            providers: mediaProviders
                        }
                    })
                    .catch(function (error) { console.error(error); });
            }

            initEditor('#description', ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'imageUpload', 'mediaEmbed', 'undo', 'redo']);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('image');
            var preview = document.getElementById('digitalSkillImagePreview');
            if (!input || !preview) return;

            input.addEventListener('change', function () {
                var file = input.files && input.files[0] ? input.files[0] : null;
                if (!file) {
                    preview.classList.add('d-none');
                    preview.removeAttribute('src');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
