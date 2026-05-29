@extends('admin.layout')

@section('title', 'Digital Skills')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Digital Skills</h1>
        <a class="btn btn-primary" href="{{ route('admin.digital-skills.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>Active</th>
                <th>Order</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->title }}</td>
                    <td>
                        @if(($row->is_free ?? false) || is_null($row->price) || (float)$row->price <= 0)
                            <span class="badge text-bg-success">Free</span>
                        @else
                            ₦{{ number_format((float)$row->price, 2) }}
                        @endif
                    </td>
                    <td>{{ $row->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $row->sort_order }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.digital-skills.edit', $row) }}" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
                                <path d="M13.752 4.396l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </a>
                        <a class="btn btn-sm btn-outline-success" href="{{ route('digital-skills.show', $row->id) }}#outline" target="_blank" rel="noopener" title="View lessons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752C8.154 2 9.282 1.8 10.612 1.935c1.234.124 2.503.523 3.388.893V13.5c-.885-.37-2.154-.769-3.388-.893-1.33-.134-2.458.063-3.112.752-.654-.689-1.782-.886-3.112-.752-1.234.124-2.503.523-3.388.893V2.828z"/>
                                <path d="M4 3.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </a>
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#addLessonModal"
                            data-action="{{ route('admin.digital-skills.lessons.store', $row) }}"
                            data-item-title="{{ $row->title }}"
                            title="Add lesson"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </button>
                        <form method="post" action="{{ route('admin.digital-skills.destroy', $row) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M5.5 5.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0V6zm2 .5a.5.5 0 0 1 .5-.5.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No items</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @push('modals')
        <div class="modal fade" id="addLessonModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="fw-semibold">Add lesson</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" id="addLessonForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="text-muted small mb-3" id="addLessonForText"></div>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modal_lesson_title">Lesson title</label>
                                    <input class="form-control" id="modal_lesson_title" name="title" type="text" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modal_lesson_video_url">Video link (optional)</label>
                                    <input class="form-control" id="modal_lesson_video_url" name="video_url" type="url" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="modal_lesson_brief_info">Brief info (optional)</label>
                                    <textarea class="form-control" id="modal_lesson_brief_info" name="brief_info" rows="3" placeholder="Short description about this lesson"></textarea>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modal_lesson_pdf">PDF (optional)</label>
                                    <input class="form-control" id="modal_lesson_pdf" name="pdf" type="file" accept="application/pdf">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modal_lesson_image">Image (optional)</label>
                                    <input class="form-control" id="modal_lesson_image" name="image" type="file" accept="image/*">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="modal_lesson_sort_order">Order (optional)</label>
                                    <input class="form-control" id="modal_lesson_sort_order" name="sort_order" type="number" min="0" step="1" placeholder="auto">
                                </div>
                                <div class="col-12 col-md-4 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" id="modal_lesson_is_active" name="is_active" type="checkbox" value="1" checked>
                                        <label class="form-check-label" for="modal_lesson_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('addLessonModal');
            var form = document.getElementById('addLessonForm');
            var forText = document.getElementById('addLessonForText');
            var titleInput = document.getElementById('modal_lesson_title');
            var videoInput = document.getElementById('modal_lesson_video_url');
            var briefInput = document.getElementById('modal_lesson_brief_info');
            var pdfInput = document.getElementById('modal_lesson_pdf');
            var imageInput = document.getElementById('modal_lesson_image');
            var sortInput = document.getElementById('modal_lesson_sort_order');
            var activeInput = document.getElementById('modal_lesson_is_active');

            if (!modal || !form) return;

            modal.addEventListener('show.bs.modal', function (event) {
                var btn = event.relatedTarget;
                if (!btn) return;

                var action = btn.getAttribute('data-action') || '';
                var itemTitle = btn.getAttribute('data-item-title') || '';
                form.setAttribute('action', action);
                if (forText) forText.textContent = itemTitle ? ('Digital Skill: ' + itemTitle) : '';

                if (titleInput) titleInput.value = '';
                if (videoInput) videoInput.value = '';
                if (briefInput) briefInput.value = '';
                if (pdfInput) pdfInput.value = '';
                if (imageInput) imageInput.value = '';
                if (sortInput) sortInput.value = '';
                if (activeInput) activeInput.checked = true;
            });
        });
    </script>
@endsection
