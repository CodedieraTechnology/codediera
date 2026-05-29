@csrf
@php($service = $service ?? null)
<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $service?->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="service_type">Service Type</label>
    @php($st = old('service_type', $service?->service_type ?? ''))
    <select class="form-select" id="service_type" name="service_type">
        <option value="">Select type</option>
        @if(isset($serviceTypes) && $serviceTypes)
            @foreach($serviceTypes as $t)
                @php($schemaB64 = base64_encode(json_encode($t->schema ?? null)))
                <option value="{{ $t->key }}" data-schema="{{ $schemaB64 }}" @selected($st === $t->key)>{{ $t->name }}</option>
            @endforeach
        @else
            <option value="social_media_management" @selected($st === 'social_media_management')>Social media Management</option>
            <option value="inventory" @selected($st === 'inventory')>Inventory</option>
            <option value="school_portal" @selected($st === 'school_portal')>School Portal</option>
            <option value="payroll_attendant" @selected($st === 'payroll_attendant')>Payroll / Attendant</option>
            <option value="other" @selected($st === 'other')>Others</option>
        @endif
    </select>
    <div class="small mt-1">
        <a href="{{ route('admin.service-types.index') }}">Manage types</a>
    </div>
</div>
<div class="mb-3" id="serviceTypeSchemaPreviewWrap" style="display:none;">
    <div class="border rounded-3 p-3 bg-light">
        <div class="fw-semibold mb-1">End-user additional fields (from Service Type)</div>
        <div class="text-muted small mb-2">These fields appear on the public request form when this service type is selected.</div>
        <div id="serviceTypeSchemaPreview"></div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4" style="height: 124px;">{{ old('description', $service?->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="instructions">Instructions (shown after payment)</label>
    <textarea class="form-control" id="instructions" name="instructions" rows="4">{{ old('instructions', $service?->instructions ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="screenshot">Service Screenshot (optional)</label>
    <input class="form-control" id="screenshot" name="screenshot" type="file" accept="image/*">
    @if(!empty($service?->screenshot_path))
        <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$service->screenshot_path) }}" target="_blank" rel="noopener">Open Screenshot</a>
            <div class="form-check">
                <input class="form-check-input" id="remove_screenshot" name="remove_screenshot" type="checkbox" value="1">
                <label class="form-check-label" for="remove_screenshot">Remove</label>
            </div>
        </div>
    @endif
</div>
<div class="mb-3">
    <label class="form-label" for="approach_image">Approach Image (shows on services card)</label>
    <input class="form-control" id="approach_image" name="approach_image" type="file" accept="image/*">
    @if(!empty($service?->approach_image_path))
        <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$service->approach_image_path) }}" target="_blank" rel="noopener">Open Approach Image</a>
            <div class="form-check">
                <input class="form-check-input" id="remove_approach_image" name="remove_approach_image" type="checkbox" value="1">
                <label class="form-check-label" for="remove_approach_image">Remove</label>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-12 mb-3">
        <label class="form-label" for="download_url">Download Link / App Link (optional)</label>
        <input class="form-control" id="download_url" name="download_url" type="url" value="{{ old('download_url', $service->download_url ?? '') }}" placeholder="https://...">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="gallery_images">Gallery Images (optional)</label>
    <input class="form-control" id="gallery_images" name="gallery_images[]" type="file" accept="image/*" multiple>
    @if(!empty($service?->images) && $service->images->count())
        <div class="mt-2 row g-2">
            @foreach($service->images as $img)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card">
                        <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank" rel="noopener">
                            <img class="img-fluid rounded-4" src="{{ asset('storage/'.$img->image_path) }}" alt="">
                        </a>
                        <div class="card-body py-2">
                            <div class="form-check">
                                <input class="form-check-input" id="remove_gallery_{{ $img->id }}" name="remove_gallery_images[]" type="checkbox" value="{{ $img->id }}">
                                <label class="form-check-label small" for="remove_gallery_{{ $img->id }}">Remove</label>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="icon">Icon (optional)</label>
        <input class="form-control" id="icon" name="icon" type="text" list="serviceIconList" value="{{ old('icon', $service->icon ?? '') }}">
        <div class="mt-2 d-flex flex-wrap gap-2" id="serviceIconPicker">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="💻">💻</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🌐">🌐</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🛒">🛒</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="📱">📱</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🎨">🎨</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🧩">🧩</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🔒">🔒</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="⚙️">⚙️</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="📊">📊</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="☁️">☁️</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🧾">🧾</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-icon-pick="🧠">🧠</button>
        </div>
        <datalist id="serviceIconList">
            <option value="💻"></option>
            <option value="🌐"></option>
            <option value="🛒"></option>
            <option value="📱"></option>
            <option value="🎨"></option>
            <option value="🧩"></option>
            <option value="🔒"></option>
            <option value="⚙️"></option>
            <option value="📊"></option>
            <option value="☁️"></option>
        </datalist>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label" for="price">Price (optional)</label>
        <input class="form-control" id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $service->price ?? '') }}" placeholder="0.00">
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_free" name="is_free" type="checkbox" value="1" {{ old('is_free', ($service->is_free ?? false) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_free">Free</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="payment_type">Payment Type</label>
        <select class="form-select" id="payment_type" name="payment_type">
            @php($paymentType = old('payment_type', $service->payment_type ?? 'one_time'))
            <option value="one_time" @selected($paymentType === 'one_time')>One-time</option>
            <option value="monthly" @selected($paymentType === 'monthly')>Monthly</option>
            <option value="yearly" @selected($paymentType === 'yearly')>Yearly</option>
            <option value="custom" @selected($paymentType === 'custom')>Custom</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Request Form Fields (admin selects what user will fill)</label>
        @php($fields = old('inquiry_fields', $service->inquiry_fields ?? ['phone', 'company', 'budget', 'message']))
        <div class="d-flex flex-wrap gap-3">
            <div class="form-check">
                <input class="form-check-input" id="field_phone" name="inquiry_fields[]" type="checkbox" value="phone" @checked(in_array('phone', (array)$fields, true))>
                <label class="form-check-label" for="field_phone">📞 Phone</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" id="field_company" name="inquiry_fields[]" type="checkbox" value="company" @checked(in_array('company', (array)$fields, true))>
                <label class="form-check-label" for="field_company">🏢 Company</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" id="field_budget" name="inquiry_fields[]" type="checkbox" value="budget" @checked(in_array('budget', (array)$fields, true))>
                <label class="form-check-label" for="field_budget">💰 Budget</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" id="field_message" name="inquiry_fields[]" type="checkbox" value="message" @checked(in_array('message', (array)$fields, true))>
                <label class="form-check-label" for="field_message">📝 Message</label>
            </div>
        </div>
        <div class="text-muted small mt-1">Name and Email are always required.</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Delivery Duration (optional)</label>
        <div class="input-group">
            <input class="form-control" name="delivery_duration_value" type="number" min="1" value="{{ old('delivery_duration_value', $service->delivery_duration_value ?? '') }}" placeholder="e.g. 7">
            @php($du = old('delivery_duration_unit', $service->delivery_duration_unit ?? 'days'))
            <select class="form-select" name="delivery_duration_unit">
                <option value="days" @selected($du === 'days')>Days</option>
                <option value="weeks" @selected($du === 'weeks')>Weeks</option>
                <option value="months" @selected($du === 'months')>Months</option>
            </select>
        </div>
        <div class="text-muted small mt-1">Shown to users as an estimated delivery time.</div>
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="grace_trial_enabled" name="grace_trial_enabled" type="checkbox" value="1" {{ old('grace_trial_enabled', ($service->grace_trial_enabled ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="grace_trial_enabled">Enable 3 days free trial after expiration</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($service->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var freeEl = document.getElementById('is_free');
            var priceEl = document.getElementById('price');
            var paymentEl = document.getElementById('payment_type');
            if (!freeEl || !priceEl) return;

            function sync() {
                if (freeEl.checked) {
                    priceEl.value = '';
                    priceEl.setAttribute('disabled', 'disabled');
                    if (paymentEl) {
                        paymentEl.value = 'one_time';
                        paymentEl.setAttribute('disabled', 'disabled');
                    }
                } else {
                    priceEl.removeAttribute('disabled');
                    if (paymentEl) paymentEl.removeAttribute('disabled');
                }
            }

            freeEl.addEventListener('change', sync);
            sync();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('icon');
            var wrap = document.getElementById('serviceIconPicker');
            if (!input || !wrap) return;

            function setActive(value) {
                var btns = wrap.querySelectorAll('[data-icon-pick]');
                btns.forEach(function (btn) {
                    btn.classList.toggle('btn-primary', btn.getAttribute('data-icon-pick') === value);
                    btn.classList.toggle('btn-outline-secondary', btn.getAttribute('data-icon-pick') !== value);
                });
            }

            wrap.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('[data-icon-pick]') : null;
                if (!btn) return;
                var value = btn.getAttribute('data-icon-pick') || '';
                input.value = value;
                setActive(value);
            });

            input.addEventListener('input', function () {
                setActive(input.value);
            });

            setActive(input.value);
        });
    </script>
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

            function initEditor(selector) {
                var el = document.querySelector(selector);
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
            }

            initEditor('#description');
            initEditor('#instructions');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typeEl = document.getElementById('service_type');
            var wrap = document.getElementById('serviceTypeSchemaPreviewWrap');
            var out = document.getElementById('serviceTypeSchemaPreview');
            if (!typeEl || !wrap || !out) return;

            function render(schema) {
                out.innerHTML = '';
                if (!Array.isArray(schema) || !schema.length) {
                    wrap.style.display = 'none';
                    return;
                }

                var ul = document.createElement('ul');
                ul.className = 'list-group list-group-flush';

                schema.forEach(function (f) {
                    if (!f || typeof f !== 'object') return;
                    if (!f.key) return;

                    var li = document.createElement('li');
                    li.className = 'list-group-item bg-transparent px-0';

                    var label = (f.label || f.key).toString();
                    var type = (f.type || 'text').toString();
                    var req = !!f.required;

                    var head = document.createElement('div');
                    head.className = 'd-flex flex-wrap align-items-center gap-2';

                    var name = document.createElement('div');
                    name.className = 'fw-semibold';
                    name.textContent = label;

                    var meta = document.createElement('div');
                    meta.className = 'text-muted small';
                    meta.textContent = (type + (req ? ' • required' : '')).toLowerCase();

                    head.appendChild(name);
                    head.appendChild(meta);
                    li.appendChild(head);

                    if (type === 'priced_multi_select') {
                        var pricingMode = (f.pricing_mode || 'none').toString();
                        var fixedAmount = parseFloat(f.fixed_amount || 0) || 0;
                        var percent = parseFloat(f.percent || 0) || 0;

                        var p = document.createElement('div');
                        p.className = 'text-muted small mt-1';
                        p.textContent = 'Pricing: ' + pricingMode + (fixedAmount ? (' • fixed ₦' + fixedAmount) : '') + (percent ? (' • ' + percent + '%') : '');
                        li.appendChild(p);
                    }

                    var opts = Array.isArray(f.options) ? f.options : [];
                    if (opts.length) {
                        var chips = document.createElement('div');
                        chips.className = 'd-flex flex-wrap gap-2 mt-2';

                        opts.slice(0, 12).forEach(function (opt) {
                            var val = '';
                            var price = null;

                            if (typeof opt === 'string') {
                                val = opt;
                            } else if (opt && typeof opt === 'object') {
                                val = (opt.label || opt.value || '').toString();
                                if (opt.price !== undefined && opt.price !== null && opt.price !== '') {
                                    price = parseFloat(opt.price) || 0;
                                }
                            }

                            val = val.trim();
                            if (!val) return;

                            var chip = document.createElement('span');
                            chip.className = 'badge text-bg-light border';
                            chip.textContent = price !== null ? (val + ' (₦' + price + ')') : val;
                            chips.appendChild(chip);
                        });

                        if (opts.length > 12) {
                            var more = document.createElement('span');
                            more.className = 'badge text-bg-secondary';
                            more.textContent = '+' + (opts.length - 12) + ' more';
                            chips.appendChild(more);
                        }

                        li.appendChild(chips);
                    }

                    ul.appendChild(li);
                });

                out.appendChild(ul);
                wrap.style.display = '';
            }

            function parseSelected() {
                var opt = typeEl.options[typeEl.selectedIndex];
                var b64 = opt ? (opt.getAttribute('data-schema') || '') : '';
                if (!b64) {
                    wrap.style.display = 'none';
                    out.innerHTML = '';
                    return;
                }
                try {
                    var json = atob(b64);
                    var schema = JSON.parse(json);
                    render(schema);
                } catch (e) {
                    wrap.style.display = 'none';
                    out.innerHTML = '';
                }
            }

            typeEl.addEventListener('change', parseSelected);
            parseSelected();
        });
    </script>
@endsection
