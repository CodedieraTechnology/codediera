@csrf
@php($slider = $slider ?? null)
<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $slider?->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="caption">Caption</label>
    <input class="form-control" id="caption" name="caption" type="text" value="{{ old('caption', $slider?->caption ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label" for="image">Image / Poster (optional)</label>
    <input class="form-control" id="image" name="image" type="file" accept="image/*">
    @if(!empty($slider?->image_path))
        <div class="mt-2 d-flex align-items-center gap-3">
            <img src="{{ asset('storage/'.$slider->image_path) }}" alt="" style="height:64px">
            <div class="form-check">
                <input class="form-check-input" id="remove_image" name="remove_image" type="checkbox" value="1">
                <label class="form-check-label" for="remove_image">Remove image</label>
            </div>
        </div>
    @endif
</div>
<div class="mb-3">
    <label class="form-label" for="video">Video (optional)</label>
    <input class="form-control" id="video" name="video" type="file" accept="video/mp4,video/webm,video/ogg,video/quicktime">
    <input type="hidden" name="video_upload_path" id="video_upload_path" value="">
    <div class="mt-2 d-flex gap-2 flex-wrap" id="sliderVideoTools" style="display:none;">
        <button class="btn btn-sm btn-outline-secondary" type="button" id="sliderVideoReduceBtn">Reduce video size</button>
        <button class="btn btn-sm btn-outline-success" type="button" id="sliderVideoUseReducedBtn" style="display:none;">Use reduced video</button>
        <a class="btn btn-sm btn-outline-primary" href="#" id="sliderVideoReducedDownload" style="display:none;" download>Download reduced video</a>
        <div class="text-muted small" id="sliderVideoSizeLabel" style="display:none;"></div>
        <div class="text-danger small" id="sliderVideoSizeError" style="display:none;"></div>
        <div class="text-muted small" id="sliderVideoReduceHint" style="display:none;"></div>
    </div>
    <div class="mt-3" id="sliderVideoPreviewWrap" style="display:none;">
        <div class="fw-semibold mb-2">Preview</div>
        <video id="sliderVideoPreview" controls playsinline preload="metadata" style="width: min(520px, 100%); max-height: 320px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.12); background: #000;"></video>
    </div>
    @if(!empty($slider?->video_path))
        <div class="mt-2 d-flex align-items-center gap-3">
            <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/'.$slider->video_path) }}" target="_blank" rel="noopener">View video</a>
            <div class="form-check">
                <input class="form-check-input" id="remove_video" name="remove_video" type="checkbox" value="1">
                <label class="form-check-label" for="remove_video">Remove video</label>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('video');
        var wrap = document.getElementById('sliderVideoPreviewWrap');
        var player = document.getElementById('sliderVideoPreview');
        var tools = document.getElementById('sliderVideoTools');
        var reduceBtn = document.getElementById('sliderVideoReduceBtn');
        var useReducedBtn = document.getElementById('sliderVideoUseReducedBtn');
        var reducedDownload = document.getElementById('sliderVideoReducedDownload');
        var sizeLabel = document.getElementById('sliderVideoSizeLabel');
        var sizeError = document.getElementById('sliderVideoSizeError');
        var reduceHint = document.getElementById('sliderVideoReduceHint');
        var uploadPathInput = document.getElementById('video_upload_path');
        if (!input || !wrap || !player) return;

        var form = input.closest ? input.closest('form') : null;
        var saveBtn = form ? form.querySelector('button[type="submit"]') : null;
        var uploading = false;

        var activeUrl = null;
        var reducedUrl = null;
        var reducedFile = null;
        var maxBytes = 0;

        function formatBytes(bytes) {
            if (!Number.isFinite(bytes)) return '';
            var units = ['B', 'KB', 'MB', 'GB'];
            var i = 0;
            var n = bytes;
            while (n >= 1024 && i < units.length - 1) {
                n = n / 1024;
                i++;
            }
            return n.toFixed(i === 0 ? 0 : 2) + ' ' + units[i];
        }

        function resetReduced() {
            if (reducedUrl) {
                try { URL.revokeObjectURL(reducedUrl); } catch (e) {}
                reducedUrl = null;
            }
            reducedFile = null;
            if (reducedDownload) {
                reducedDownload.style.display = 'none';
                reducedDownload.removeAttribute('href');
                reducedDownload.removeAttribute('download');
            }
            if (useReducedBtn) {
                useReducedBtn.style.display = 'none';
            }
        }

        function clearPreview() {
            if (activeUrl) {
                try { URL.revokeObjectURL(activeUrl); } catch (e) {}
                activeUrl = null;
            }
            player.removeAttribute('src');
            try { player.load(); } catch (e) {}
            wrap.style.display = 'none';
            if (tools) tools.style.display = 'none';
            if (sizeLabel) sizeLabel.style.display = 'none';
            if (sizeError) sizeError.style.display = 'none';
            if (reduceHint) reduceHint.style.display = 'none';
            resetReduced();
        }

        input.addEventListener('change', function () {
            var file = input.files && input.files[0] ? input.files[0] : null;
            if (!file) {
                clearPreview();
                return;
            }

            if (uploadPathInput) uploadPathInput.value = '';
            resetReduced();

            if (activeUrl) {
                try { URL.revokeObjectURL(activeUrl); } catch (e) {}
                activeUrl = null;
            }

            activeUrl = URL.createObjectURL(file);
            player.src = activeUrl;
            wrap.style.display = '';
            if (tools) tools.style.display = '';

            if (sizeLabel) {
                sizeLabel.textContent = 'Selected file size: ' + formatBytes(file.size) + ' (' + file.size + ' bytes)';
                sizeLabel.style.display = '';
            }

            if (sizeError) {
                if (maxBytes > 0 && file.size > maxBytes) {
                    sizeError.textContent = 'Selected file is ' + formatBytes(file.size) + ', which is above the server limit. Reduce/compress it before saving.';
                    sizeError.style.display = '';
                } else {
                    sizeError.style.display = 'none';
                }
            }
        });

        if (reduceBtn) {
            reduceBtn.addEventListener('click', async function () {
                var file = input.files && input.files[0] ? input.files[0] : null;
                if (!file) return;

                resetReduced();

                var canStream = typeof player.captureStream === 'function';
                var Recorder = window.MediaRecorder;
                if (!canStream || typeof Recorder === 'undefined') {
                    if (reduceHint) {
                        reduceHint.textContent = 'This browser cannot reduce videos automatically. Use a tool like HandBrake to compress, then upload again.';
                        reduceHint.style.display = '';
                    }
                    return;
                }

                var supportedTypes = [
                    'video/webm;codecs=vp9,opus',
                    'video/webm;codecs=vp8,opus',
                    'video/webm;codecs=vp9',
                    'video/webm;codecs=vp8',
                    'video/webm',
                ];
                var mimeType = '';
                for (var i = 0; i < supportedTypes.length; i++) {
                    if (Recorder.isTypeSupported(supportedTypes[i])) {
                        mimeType = supportedTypes[i];
                        break;
                    }
                }
                if (!mimeType) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Video reduction is not supported in this browser. Use HandBrake to compress, then upload again.';
                        reduceHint.style.display = '';
                    }
                    return;
                }

                if (reduceHint) {
                    reduceHint.textContent = 'Reducing video… keep this tab open until it finishes.';
                    reduceHint.style.display = '';
                }

                try { player.currentTime = 0; } catch (e) {}
                player.muted = true;

                var sourceStream = player.captureStream();
                var stream = sourceStream;
                try {
                    var videoTracks = sourceStream && typeof sourceStream.getVideoTracks === 'function' ? sourceStream.getVideoTracks() : [];
                    if (videoTracks && videoTracks.length) {
                        stream = new MediaStream(videoTracks);
                    }
                } catch (e) {}
                var chunks = [];
                var recorder = null;
                try {
                    recorder = new Recorder(stream, {
                        mimeType: mimeType,
                        videoBitsPerSecond: 900000,
                    });
                } catch (e) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Cannot reduce this video in the browser. Please compress with HandBrake and upload again.';
                        reduceHint.style.display = '';
                    }
                    return;
                }

                recorder.ondataavailable = function (ev) {
                    if (ev.data && ev.data.size) chunks.push(ev.data);
                };

                var stopPromise = new Promise(function (resolve) {
                    recorder.onstop = resolve;
                });

                try {
                    recorder.start(500);
                } catch (e) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Cannot reduce this video in the browser. Please compress with HandBrake and upload again.';
                        reduceHint.style.display = '';
                    }
                    return;
                }
                var playPromise = null;
                try { playPromise = player.play(); } catch (e) {}
                if (playPromise && typeof playPromise.catch === 'function') {
                    playPromise.catch(function () {});
                }

                var endedPromise = new Promise(function (resolve) {
                    var onEnd = function () {
                        player.removeEventListener('ended', onEnd);
                        resolve();
                    };
                    player.addEventListener('ended', onEnd);
                });

                await endedPromise;
                try { recorder.stop(); } catch (e) {}
                await stopPromise;

                var blob = new Blob(chunks, { type: mimeType.split(';')[0] || 'video/webm' });
                if (!blob.size) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Could not create a reduced video. Please compress with HandBrake and upload again.';
                        reduceHint.style.display = '';
                    }
                    return;
                }

                reducedUrl = URL.createObjectURL(blob);
                reducedFile = new File([blob], 'reduced-' + (file.name || 'video') + '.webm', { type: blob.type || 'video/webm' });
                if (reducedDownload) {
                    reducedDownload.href = reducedUrl;
                    reducedDownload.download = 'reduced-' + (file.name || 'video') + '.webm';
                    reducedDownload.style.display = '';
                }
                if (useReducedBtn) {
                    useReducedBtn.style.display = '';
                }

                if (reduceHint) {
                    reduceHint.textContent = 'Click "Use reduced video" to replace the selected file, then Save.';
                    reduceHint.style.display = '';
                }
            });
        }

        if (useReducedBtn) {
            useReducedBtn.addEventListener('click', function () {
                if (!reducedFile) return;
                try {
                    var dt = new DataTransfer();
                    dt.items.add(reducedFile);
                    input.files = dt.files;
                } catch (e) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Your browser does not allow auto-replacing the file. Download the reduced video and select it manually.';
                        reduceHint.style.display = '';
                    }
                    return;
                }

                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }

        async function uploadVideoInChunks(file) {
            if (!form) throw new Error('Missing form');
            var tokenInput = form.querySelector('input[name="_token"]');
            var csrf = tokenInput ? tokenInput.value : '';
            if (!csrf) throw new Error('Missing CSRF');

            var uploadId = (Date.now().toString(36) + Math.random().toString(36).slice(2)).replace(/\./g, '');
            var chunkSize = 1024 * 1024;
            var total = Math.ceil(file.size / chunkSize);
            var chunkUrl = '{{ route('admin.sliders.video-chunk') }}';
            var completeUrl = '{{ route('admin.sliders.video-complete') }}';

            for (var i = 0; i < total; i++) {
                var start = i * chunkSize;
                var end = Math.min(start + chunkSize, file.size);
                var blob = file.slice(start, end);
                var fd = new FormData();
                fd.append('_token', csrf);
                fd.append('upload_id', uploadId);
                fd.append('chunk_index', String(i));
                fd.append('total_chunks', String(total));
                fd.append('file_name', file.name || 'video');
                fd.append('chunk', blob, (file.name || 'video') + '.part');

                var res = await fetch(chunkUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
                if (!res.ok) throw new Error('Chunk upload failed');

                if (reduceHint) {
                    reduceHint.textContent = 'Uploading video… ' + Math.round(((i + 1) / total) * 100) + '%';
                    reduceHint.style.display = '';
                }
            }

            var doneFd = new FormData();
            doneFd.append('_token', csrf);
            doneFd.append('upload_id', uploadId);
            doneFd.append('total_chunks', String(total));
            doneFd.append('file_name', file.name || 'video');

            var doneRes = await fetch(completeUrl, { method: 'POST', body: doneFd, credentials: 'same-origin' });
            if (!doneRes.ok) throw new Error('Finalize upload failed');
            var json = await doneRes.json();
            if (!json || !json.video_upload_path) throw new Error('Finalize response missing path');
            return json.video_upload_path;
        }

        if (form) {
            form.addEventListener('submit', async function (e) {
                if (uploading) return;
                var file = input.files && input.files[0] ? input.files[0] : null;
                if (!file) return;
                if (uploadPathInput && uploadPathInput.value) return;

                e.preventDefault();
                uploading = true;
                if (saveBtn) saveBtn.disabled = true;
                if (reduceHint) {
                    reduceHint.textContent = 'Preparing upload…';
                    reduceHint.style.display = '';
                }

                try {
                    var path = await uploadVideoInChunks(file);
                    if (uploadPathInput) uploadPathInput.value = path;
                    input.value = '';
                    form.submit();
                } catch (err) {
                    if (reduceHint) {
                        reduceHint.textContent = 'Upload failed. Please try again.';
                        reduceHint.style.display = '';
                    }
                    uploading = false;
                    if (saveBtn) saveBtn.disabled = false;
                }
            }, true);
        }
    });
</script>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="button_text">Button Text</label>
        <input class="form-control" id="button_text" name="button_text" type="text" value="{{ old('button_text', $slider?->button_text ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="button_url">Button URL</label>
        <input class="form-control" id="button_url" name="button_url" type="text" value="{{ old('button_url', $slider?->button_url ?? '') }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $slider?->sort_order ?? 0) }}">
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($slider?->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
