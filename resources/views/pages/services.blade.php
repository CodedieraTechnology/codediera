@extends('layouts.public')

@section('title', 'Services')

@section('content')
    <style>
        .cd-service-card {
            border-radius: 1.25rem !important;
            overflow: hidden;
            border: 1px solid var(--cd-border) !important;
            background: var(--cd-surface) !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04) !important;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease !important;
        }
        .cd-service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px rgba(15, 23, 42, 0.08) !important;
        }
        .cd-card-img-wrapper {
            position: relative;
            overflow: hidden;
            height: 190px;
            width: 100%;
            border-radius: 1.25rem 1.25rem 0 0;
        }
        .cd-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cd-service-card:hover .cd-card-img-wrapper img {
            transform: scale(1.06);
        }
        .cd-glass-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.4rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 2rem;
            z-index: 2;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(15, 23, 42, 0.65);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .cd-glass-badge.bg-free-badge {
            background: rgba(22, 163, 74, 0.85);
            border-color: rgba(255, 255, 255, 0.25);
        }
        .cd-service-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.08);
            color: var(--cd-primary);
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .cd-service-title {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 0;
            line-height: 1.4;
        }
        .cd-service-title a {
            color: var(--cd-heading) !important;
            transition: color 0.2s ease;
        }
        .cd-service-title a:hover {
            color: var(--cd-primary) !important;
        }
        .cd-service-description {
            font-size: 0.875rem;
            color: var(--cd-muted);
            line-height: 1.6;
            margin-top: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 4.8em; /* Exact height for 3 lines */
            margin-bottom: 1rem;
        }
        .cd-service-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--cd-border);
            display: flex;
            gap: 0.5rem;
        }
        .cd-service-footer .btn {
            border-radius: 2rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            font-size: 0.825rem;
            transition: all 0.3s ease;
            flex: 1;
        }
        .cd-service-footer .btn-primary {
            background-color: var(--cd-primary) !important;
            border-color: var(--cd-primary) !important;
        }
        .cd-service-footer .btn-primary:hover {
            opacity: 0.9;
        }
        .cd-badge-pill {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.25rem 0.65rem;
            border-radius: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .cd-badge-pill.bg-type {
            background-color: rgba(13, 110, 253, 0.08);
            color: var(--cd-primary);
            border: 1px solid rgba(13, 110, 253, 0.15);
        }
        .cd-badge-pill.bg-duration {
            background-color: rgba(100, 116, 139, 0.08);
            color: #475569;
            border: 1px solid rgba(100, 116, 139, 0.15);
        }
        .cd-badge-pill.bg-applink {
            background-color: rgba(16, 185, 129, 0.08);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.15);
        }
        .cd-badge-pill.payment-recurring {
            background-color: rgba(245, 158, 11, 0.08);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.15);
        }
        .cd-badge-pill.payment-custom {
            background-color: rgba(100, 116, 139, 0.08);
            color: #475569;
            border: 1px solid rgba(100, 116, 139, 0.15);
        }
        .cd-badge-pill.trial-badge {
            background-color: rgba(13, 110, 253, 0.08);
            color: var(--cd-primary);
            border: 1px solid rgba(13, 110, 253, 0.15);
        }

        /* Dark Theme Overrides */
        [data-theme="dark"] .cd-service-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
        }
        [data-theme="dark"] .cd-service-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45) !important;
        }
        [data-theme="dark"] .cd-service-icon-box {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }
        [data-theme="dark"] .cd-glass-badge {
            background: rgba(17, 24, 39, 0.75);
            border-color: rgba(255, 255, 255, 0.12);
        }
        [data-theme="dark"] .cd-badge-pill.bg-type {
            background-color: rgba(13, 110, 253, 0.15);
            color: #60a5fa;
            border-color: rgba(13, 110, 253, 0.25);
        }
        [data-theme="dark"] .cd-badge-pill.bg-duration {
            background-color: rgba(148, 163, 184, 0.15);
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.25);
        }
        [data-theme="dark"] .cd-badge-pill.bg-applink {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.25);
        }
        [data-theme="dark"] .cd-badge-pill.payment-recurring {
            background-color: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.25);
        }
        [data-theme="dark"] .cd-badge-pill.payment-custom {
            background-color: rgba(148, 163, 184, 0.15);
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.25);
        }
        [data-theme="dark"] .cd-badge-pill.trial-badge {
            background-color: rgba(13, 110, 253, 0.15);
            color: #60a5fa;
            border-color: rgba(13, 110, 253, 0.25);
        }
    </style>

    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">Please fix the errors and try again.</div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                </svg>
            </div>
            <div class="flex-grow-1">
                <h1 class="h3 section-title">Services</h1>
                <div class="page-subtitle">Services we render</div>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('service-portal.login') }}">My Service(s)</a>
        </div>

        <div class="row g-3">
            @forelse($services as $service)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 cd-service-card">
                        @php($cardImage = $service->approach_image_path ?: ($service->screenshot_path ?: ($service->images->first()?->image_path)))
                        @php($typeKey = $service->service_type ?: 'other')
                        @php($typeLabel = $serviceTypeNames[$typeKey] ?? \Illuminate\Support\Str::headline($typeKey))
                        @php($paymentType = $service->payment_type ?: 'one_time')
                        @php($schema = (isset($serviceTypeSchemas) && isset($serviceTypeSchemas[$typeKey]) && is_array($serviceTypeSchemas[$typeKey])) ? $serviceTypeSchemas[$typeKey] : [])
                        @php($schemaLabels = collect($schema)->filter(function ($f) { return is_array($f) && !empty($f['label']); })->pluck('label')->values())
                        @php($schemaCount = is_array($schema) ? count($schema) : 0)

                        <div class="cd-card-img-wrapper">
                            @if($cardImage)
                                <a href="{{ route('services.show', $service) }}">
                                    <img src="{{ asset('storage/'.$cardImage) }}" alt="{{ $service->title }}">
                                </a>
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--cd-primary), var(--cd-heading)); opacity: 0.85;">
                                    <span class="text-white fw-bold opacity-50" style="font-size: 1.5rem;">Codediera</span>
                                </div>
                            @endif

                            @if($service->is_free)
                                <span class="cd-glass-badge bg-free-badge">Free</span>
                            @elseif(!is_null($service->price))
                                <span class="cd-glass-badge">₦{{ number_format((float)$service->price, 2) }}</span>
                            @else
                                <span class="cd-glass-badge">Quote</span>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2.5">
                                <span class="cd-badge-pill bg-type">{{ $typeLabel }}</span>
                                @if($service->delivery_duration_value)
                                    <span class="cd-badge-pill bg-duration">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        </svg>
                                        {{ (int) $service->delivery_duration_value }} {{ $service->delivery_duration_unit ?: 'days' }}
                                    </span>
                                @endif
                                @if(!empty($service->download_url))
                                    <span class="cd-badge-pill bg-applink">App / Link</span>
                                @endif
                                @if(in_array($paymentType, ['monthly', 'yearly'], true) && ($service->grace_trial_enabled ?? true))
                                    <span class="cd-badge-pill trial-badge">3 days trial</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($service->icon)
                                    <div class="cd-service-icon-box">
                                        <span>{{ $service->icon }}</span>
                                    </div>
                                @endif
                                <h3 class="cd-service-title">
                                    <a class="text-decoration-none" href="{{ route('services.show', $service) }}">{{ $service->title }}</a>
                                </h3>
                            </div>

                            <div class="d-flex flex-wrap gap-1.5 mb-2.5">
                                @if($paymentType === 'monthly')
                                    <span class="cd-badge-pill payment-recurring">Monthly</span>
                                @elseif($paymentType === 'yearly')
                                    <span class="cd-badge-pill payment-recurring">Yearly</span>
                                @elseif($paymentType === 'custom')
                                    <span class="cd-badge-pill payment-custom">Custom plan</span>
                                @else
                                    <span class="cd-badge-pill bg-duration">One-time</span>
                                @endif
                                @php($contactFields = array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message']))))
                                @if($schemaCount)
                                    <span class="cd-badge-pill bg-duration">Setup fields: {{ $schemaCount }}</span>
                                @endif
                            </div>

                            @if($service->description)
                                <div class="cd-service-description">
                                    {{ strip_tags($service->description) }}
                                </div>
                            @endif

                            @if($schemaCount)
                                <div class="small fw-semibold mb-2 text-muted">Includes</div>
                                <div class="d-flex flex-wrap gap-1.5 mb-3">
                                    @foreach($schemaLabels->take(4) as $lbl)
                                        <span class="cd-badge-pill bg-duration" style="font-size: 0.65rem; text-transform: none; font-weight: normal; letter-spacing: 0;">{{ $lbl }}</span>
                                    @endforeach
                                    @if($schemaLabels->count() > 4)
                                        <span class="cd-badge-pill bg-duration" style="font-size: 0.65rem; text-transform: none; font-weight: normal; letter-spacing: 0;">+{{ $schemaLabels->count() - 4 }} more</span>
                                    @endif
                                </div>
                            @endif

                            <div class="cd-service-footer mt-auto d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('services.show', $service) }}">View details</a>
                                <button
                                    class="btn btn-sm btn-primary"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#serviceApplyModal"
                                    data-service-id="{{ $service->id }}"
                                    data-service-title="{{ $service->title }}"
                                    data-service-type="{{ $typeKey }}"
                                    data-service-type-schema="{{ $schemaCount ? base64_encode(json_encode($schema)) : '' }}"
                                    data-service-free="{{ $service->is_free ? 1 : 0 }}"
                                    data-service-fields="{{ implode(',', array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message'])))) }}"
                                >
                                    Request
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted">No services added yet.</div>
                </div>
            @endforelse
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="serviceApplyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="{{ route('services.apply') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Request <span id="serviceApplyTitle">Service</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-success d-none" id="serviceApplyFreeNotice">
                                <div class="fw-semibold">This service is free</div>
                                <div class="small">No payment is required. After submit, you will receive your Service ID for portal access.</div>
                            </div>
                            <div class="alert alert-info" id="serviceApplyPaymentNotice">
                                <div class="fw-semibold">Payment checkout</div>
                                <div class="small">After you submit this request, you will continue to checkout and receive your Service ID for portal access.</div>
                            </div>
                            <input type="hidden" name="service_id" id="serviceApplyServiceId" value="{{ old('service_id') }}">

                            <div class="mb-3">
                                <label class="form-label" for="serviceApplyName">Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="serviceApplyName" name="name" type="text" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="serviceApplyEmail">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="serviceApplyEmail" name="email" type="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-2">
                                <div class="col-12 col-md-6" data-field="phone">
                                    <div class="mb-3">
                                        <label class="form-label" for="serviceApplyPhone">Phone</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" id="serviceApplyPhone" name="phone" type="text" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6" data-field="company">
                                    <div class="mb-3">
                                        <label class="form-label" for="serviceApplyCompany">Company</label>
                                        <input class="form-control @error('company') is-invalid @enderror" id="serviceApplyCompany" name="company" type="text" value="{{ old('company') }}">
                                        @error('company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" data-field="budget">
                                <label class="form-label" for="serviceApplyBudget">Budget (optional)</label>
                                <input class="form-control @error('budget') is-invalid @enderror" id="serviceApplyBudget" name="budget" type="text" value="{{ old('budget') }}" placeholder="e.g. ₦200,000 - ₦500,000">
                                @error('budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0" data-field="message">
                                <label class="form-label" for="serviceApplyMessage">Project details</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="serviceApplyMessage" name="message" rows="4">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3 d-none" id="serviceApplyDynamicFields"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" id="serviceApplySubmitBtn" type="submit">Continue to checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('serviceApplyModal');
            if (!modalEl || !window.bootstrap) return;

            var titleEl = document.getElementById('serviceApplyTitle');
            var serviceIdEl = document.getElementById('serviceApplyServiceId');
            var fieldEls = modalEl.querySelectorAll('[data-field]');
            var dynamicFieldsEl = document.getElementById('serviceApplyDynamicFields');
            var paymentNoticeEl = document.getElementById('serviceApplyPaymentNotice');
            var freeNoticeEl = document.getElementById('serviceApplyFreeNotice');
            var submitBtnEl = document.getElementById('serviceApplySubmitBtn');

            modalEl.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                if (!trigger) {
                    var existingServiceId = serviceIdEl ? serviceIdEl.value : '';
                    if (existingServiceId) {
                        trigger = document.querySelector('[data-bs-target="#serviceApplyModal"][data-service-id="'+existingServiceId+'"]');
                    }
                }
                if (!trigger) {
                    if (fieldEls && fieldEls.length) {
                        fieldEls.forEach(function (el) {
                            el.classList.remove('d-none');
                        });
                    }
                    if (dynamicFieldsEl) {
                        dynamicFieldsEl.classList.add('d-none');
                        dynamicFieldsEl.innerHTML = '';
                    }
                    return;
                }
                var serviceId = trigger.getAttribute('data-service-id');
                var serviceTitle = trigger.getAttribute('data-service-title');
                var typeSchemaB64 = (trigger.getAttribute('data-service-type-schema') || '').trim();
                var isFree = trigger.getAttribute('data-service-free') === '1';
                var fieldsValue = trigger.getAttribute('data-service-fields') || '';
                var enabled = fieldsValue.split(',').map(function (v) { return v.trim(); }).filter(function (v) { return v.length; });
                if (serviceIdEl && serviceId) serviceIdEl.value = serviceId;
                if (titleEl && serviceTitle) titleEl.textContent = serviceTitle;
                if (paymentNoticeEl) paymentNoticeEl.classList.toggle('d-none', isFree);
                if (freeNoticeEl) freeNoticeEl.classList.toggle('d-none', !isFree);
                if (submitBtnEl) submitBtnEl.textContent = isFree ? 'Submit request' : 'Continue to checkout';
                if (fieldEls && fieldEls.length) {
                    fieldEls.forEach(function (el) {
                        var key = el.getAttribute('data-field');
                        if (!key) return;
                        var show = enabled.indexOf(key) !== -1;
                        el.classList.toggle('d-none', !show);
                    });
                }

                if (dynamicFieldsEl) {
                    dynamicFieldsEl.classList.add('d-none');
                    dynamicFieldsEl.innerHTML = '';

                    if (typeSchemaB64) {
                        try {
                            var schemaJson = atob(typeSchemaB64);
                            var schema = JSON.parse(schemaJson);
                            if (Array.isArray(schema) && schema.length) {
                                var frag = document.createDocumentFragment();

                                var header = document.createElement('div');
                                header.className = 'fw-semibold mb-2';
                                header.textContent = 'Additional information';
                                frag.appendChild(header);

                                schema.forEach(function (field) {
                                    if (!field || typeof field !== 'object') return;
                                    var key = (field.key || '').toString().trim();
                                    if (!key) return;

                                    var type = (field.type || 'text').toString().toLowerCase();
                                    var label = (field.label || key).toString();
                                    var required = !!field.required;
                                    var options = Array.isArray(field.options) ? field.options : [];
                                    var pricingMode = (field.pricing_mode || 'none').toString().toLowerCase();
                                    var fixedAmount = parseFloat(field.fixed_amount || 0) || 0;
                                    var percent = parseFloat(field.percent || 0) || 0;

                                    var wrap = document.createElement('div');
                                    wrap.className = 'mb-3';

                                    if (type === 'checkbox') {
                                        var checkWrap = document.createElement('div');
                                        checkWrap.className = 'form-check';

                                        var check = document.createElement('input');
                                        check.className = 'form-check-input';
                                        check.type = 'checkbox';
                                        check.id = 'meta_' + key;
                                        check.name = 'meta[' + key + ']';
                                        check.value = '1';
                                        if (required) check.required = true;

                                        var checkLabel = document.createElement('label');
                                        checkLabel.className = 'form-check-label';
                                        checkLabel.htmlFor = check.id;
                                        checkLabel.textContent = label;

                                        checkWrap.appendChild(check);
                                        checkWrap.appendChild(checkLabel);
                                        wrap.appendChild(checkWrap);
                                        frag.appendChild(wrap);
                                        return;
                                    }

                                    var lbl = document.createElement('label');
                                    lbl.className = 'form-label';
                                    lbl.htmlFor = 'meta_' + key;
                                    lbl.textContent = label;
                                    wrap.appendChild(lbl);

                                    var input;
                                    if (type === 'textarea') {
                                        input = document.createElement('textarea');
                                        input.rows = 3;
                                    } else if (type === 'select' || type === 'multi_select') {
                                        input = document.createElement('select');
                                        if (type === 'multi_select') {
                                            input.multiple = true;
                                        }
                                        var optEmpty = document.createElement('option');
                                        optEmpty.value = '';
                                        optEmpty.textContent = 'Select';
                                        if (type !== 'multi_select') input.appendChild(optEmpty);
                                        options.forEach(function (opt) {
                                            var o = document.createElement('option');
                                            o.value = opt;
                                            o.textContent = opt;
                                            input.appendChild(o);
                                        });
                                    } else if (type === 'priced_multi_select') {
                                        var box = document.createElement('div');
                                        box.className = 'border rounded-3 p-2';
                                        box.setAttribute('data-pricing-mode', pricingMode);
                                        box.setAttribute('data-fixed', String(fixedAmount));
                                        box.setAttribute('data-percent', String(percent));
                                        box.setAttribute('data-required', required ? '1' : '0');

                                        function recalcTotal() {
                                            var sum = 0;
                                            var checks = box.querySelectorAll('input[type="checkbox"][data-price]');
                                            checks.forEach(function (c) {
                                                if (!c.checked) return;
                                                var p = parseFloat(c.getAttribute('data-price') || '0') || 0;
                                                sum += p;
                                            });

                                            if (box.getAttribute('data-required') === '1' && checks.length) {
                                                var any = false;
                                                checks.forEach(function (c) { if (c.checked) any = true; });
                                                if (any) {
                                                    checks[0].removeAttribute('required');
                                                } else {
                                                    checks[0].setAttribute('required', 'required');
                                                }
                                            }

                                            var mode = (box.getAttribute('data-pricing-mode') || 'none').toLowerCase();
                                            var fixed = parseFloat(box.getAttribute('data-fixed') || '0') || 0;
                                            var pct = parseFloat(box.getAttribute('data-percent') || '0') || 0;
                                            if (pct < 0) pct = 0;
                                            if (pct > 100) pct = 100;

                                            var extra = 0;
                                            if (mode === 'fixed' || mode === 'fixed_percent') extra += fixed;
                                            if (mode === 'percent' || mode === 'fixed_percent') extra += (sum * (pct / 100));

                                            var total = sum + extra;
                                            if (total < 0) total = 0;

                                            var totalEl = box.querySelector('.priced-total');
                                            if (totalEl) {
                                                totalEl.textContent = '₦' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                            }
                                        }

                                        options.forEach(function (opt, idx) {
                                            var val = '';
                                            var optLabel = '';
                                            var price = 0;

                                            if (typeof opt === 'string') {
                                                val = opt;
                                                optLabel = opt;
                                            } else if (opt && typeof opt === 'object') {
                                                val = (opt.value || opt.label || '').toString();
                                                optLabel = (opt.label || val).toString();
                                                price = parseFloat(opt.price || 0) || 0;
                                            }
                                            val = val.trim();
                                            if (!val) return;

                                            var checkWrap = document.createElement('div');
                                            checkWrap.className = 'form-check';

                                            var check = document.createElement('input');
                                            check.className = 'form-check-input';
                                            check.type = 'checkbox';
                                            check.id = 'meta_' + key + '_' + idx;
                                            check.name = 'meta[' + key + '][]';
                                            check.value = val;
                                            check.setAttribute('data-price', String(price));
                                            if (required && idx === 0) check.required = true;

                                            var checkLabel = document.createElement('label');
                                            checkLabel.className = 'form-check-label';
                                            checkLabel.htmlFor = check.id;
                                            checkLabel.textContent = optLabel;

                                            if (price > 0) {
                                                var small = document.createElement('span');
                                                small.className = 'text-muted small';
                                                small.textContent = ' — ₦' + price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                                checkLabel.appendChild(small);
                                            }

                                            check.addEventListener('change', recalcTotal);

                                            checkWrap.appendChild(check);
                                            checkWrap.appendChild(checkLabel);
                                            box.appendChild(checkWrap);
                                        });

                                        var totalRow = document.createElement('div');
                                        totalRow.className = 'mt-2 small text-muted';
                                        totalRow.innerHTML = 'Total: <span class="fw-semibold priced-total">₦0.00</span>';
                                        box.appendChild(totalRow);

                                        recalcTotal();

                                        wrap.appendChild(box);
                                        frag.appendChild(wrap);
                                        return;
                                    } else if (type === 'image') {
                                        input = document.createElement('input');
                                        input.type = 'file';
                                        input.accept = 'image/*';
                                    } else {
                                        input = document.createElement('input');
                                        input.type = type === 'number' ? 'number' : 'text';
                                    }

                                    input.className = 'form-control';
                                    input.id = 'meta_' + key;
                                    if (type === 'image') {
                                        input.name = 'meta_files[' + key + ']';
                                    } else if (type === 'multi_select') {
                                        input.name = 'meta[' + key + '][]';
                                    } else {
                                        input.name = 'meta[' + key + ']';
                                    }
                                    if (required) input.required = true;

                                    wrap.appendChild(input);
                                    frag.appendChild(wrap);
                                });

                                dynamicFieldsEl.appendChild(frag);
                                dynamicFieldsEl.classList.remove('d-none');
                            }
                        } catch (e) {
                        }
                    }
                }
            });

            @if($errors->any())
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            @endif
        });
    </script>
@endsection
