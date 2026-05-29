@csrf
<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $project->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="image">Image</label>
    <input class="form-control" id="image" name="image" type="file" accept="image/*">
    @if(!empty($project?->image_path))
        <div class="mt-2 d-flex align-items-center gap-3">
            <img src="{{ asset('storage/'.$project->image_path) }}" alt="" style="height:64px">
            <div class="form-check">
                <input class="form-check-input" id="remove_image" name="remove_image" type="checkbox" value="1">
                <label class="form-check-label" for="remove_image">Remove image</label>
            </div>
        </div>
    @endif
</div>
<div class="mb-3">
    <label class="form-label" for="zip">Project ZIP (optional)</label>
    <input class="form-control" id="zip" name="zip" type="file" accept=".zip,application/zip">
    @if(!empty($project?->zip_path))
        <div class="mt-2 d-flex flex-wrap align-items-center gap-3">
            <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/'.$project->zip_path) }}" download>Download ZIP</a>
            <div class="text-muted small">{{ basename($project->zip_path) }}</div>
            <div class="form-check">
                <input class="form-check-input" id="remove_zip" name="remove_zip" type="checkbox" value="1">
                <label class="form-check-label" for="remove_zip">Remove ZIP</label>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label" for="url">Project URL (optional)</label>
        <input class="form-control" id="url" name="url" type="text" value="{{ old('url', $project->url ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="cost">Project Cost (optional)</label>
        <input class="form-control" id="cost" name="cost" type="number" min="0" step="0.01" value="{{ old('cost', $project->cost ?? '') }}" placeholder="0.00">
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($project->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            var el = document.querySelector('#description');
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
    </script>
@endsection
