@csrf
<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $job->title ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="location">Location</label>
        <input class="form-control" id="location" name="location" type="text" value="{{ old('location', $job->location ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="employment_type">Employment Type</label>
        <input class="form-control" id="employment_type" name="employment_type" type="text" value="{{ old('employment_type', $job->employment_type ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="salary">Salary</label>
        <input class="form-control" id="salary" name="salary" type="text" value="{{ old('salary', $job->salary ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $job->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="requirements">Requirements</label>
    <textarea class="form-control" id="requirements" name="requirements" rows="5">{{ old('requirements', $job->requirements ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="responsibilities">Responsibilities</label>
    <textarea class="form-control" id="responsibilities" name="responsibilities" rows="5">{{ old('responsibilities', $job->responsibilities ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="posted_at">Posted At</label>
        <input class="form-control" id="posted_at" name="posted_at" type="date" value="{{ old('posted_at', ($job->posted_at ?? null) ? $job->posted_at->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $job->sort_order ?? 0) }}">
    </div>
    <div class="col-md-4 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($job->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ids = ['#description', '#requirements', '#responsibilities'];
            if (!window.ClassicEditor) return;

            var uploadUrl = @json(route('admin.services.upload-image'));
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

            ids.forEach(function (sel) {
                var el = document.querySelector(sel);
                if (!el) return;
                ClassicEditor
                    .create(el, {
                        extraPlugins: [uploadAdapterPlugin],
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'imageUpload', 'mediaEmbed', 'undo', 'redo'],
                        mediaEmbed: { previewsInData: true, removeProviders: ['youtube', 'vimeo'], providers: mediaProviders }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection
