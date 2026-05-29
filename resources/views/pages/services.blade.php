@extends('layouts.public')

@section('title', 'Services')

@section('content')
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
                    <div class="card h-100 overflow-hidden cd-service-card">
                        <div class="card-body">
                            @php($cardImage = $service->approach_image_path ?: ($service->screenshot_path ?: ($service->images->first()?->image_path)))
                            @php($typeKey = $service->service_type ?: 'other')
                            @php($typeLabel = $serviceTypeNames[$typeKey] ?? \Illuminate\Support\Str::headline($typeKey))
                            @php($paymentType = $service->payment_type ?: 'one_time')
                            @php($schema = (isset($serviceTypeSchemas) && isset($serviceTypeSchemas[$typeKey]) && is_array($serviceTypeSchemas[$typeKey])) ? $serviceTypeSchemas[$typeKey] : [])
                            @php($schemaLabels = collect($schema)->filter(function ($f) { return is_array($f) && !empty($f['label']); })->pluck('label')->values())
                            @php($schemaCount = is_array($schema) ? count($schema) : 0)

                            @if($cardImage)
                                <a href="{{ route('services.show', $service) }}" class="d-block mb-3 text-decoration-none">
                                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
                                        <img class="w-100 h-100" src="{{ asset('storage/'.$cardImage) }}" alt="{{ $service->title }}" style="object-fit: cover;">
                                    </div>
                                </a>
                            @endif

                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <span class="badge text-bg-light text-dark">{{ $typeLabel }}</span>
                                @if($service->delivery_duration_value)
                                    <span class="badge text-bg-light text-dark">
                                        {{ (int) $service->delivery_duration_value }} {{ $service->delivery_duration_unit ?: 'days' }}
                                    </span>
                                @endif
                                @if(!empty($service->download_url))
                                    <span class="badge text-bg-success">App / Link</span>
                                @endif
                                @if(in_array($paymentType, ['monthly', 'yearly'], true) && ($service->grace_trial_enabled ?? true))
                                    <span class="badge text-bg-warning text-dark cd-trial-badge">3 days trial</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-start gap-2 mb-2">
                                <h2 class="h5 mb-0 flex-grow-1">
                                    <a class="text-decoration-none" href="{{ route('services.show', $service) }}">{{ $service->title }}</a>
                                </h2>
                                @if($service->is_free)
                                    <span class="badge text-bg-success">Free</span>
                                @elseif(!is_null($service->price))
                                    <span class="badge text-bg-primary">₦{{ number_format((float)$service->price, 2) }}</span>
                                @else
                                    <span class="badge text-bg-secondary">Quote</span>
                                @endif
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($paymentType === 'monthly')
                                    <span class="badge text-bg-dark">Monthly</span>
                                @elseif($paymentType === 'yearly')
                                    <span class="badge text-bg-dark">Yearly</span>
                                @elseif($paymentType === 'custom')
                                    <span class="badge text-bg-dark">Custom plan</span>
                                @else
                                    <span class="badge text-bg-dark">One-time</span>
                                @endif
                                @php($contactFields = array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message']))))
                                @if(count($contactFields))
                                    <span class="badge text-bg-light text-dark">Contact: {{ implode(', ', $contactFields) }}</span>
                                @endif
                                @if($schemaCount)
                                    <span class="badge text-bg-light text-dark">Setup fields: {{ $schemaCount }}</span>
                                @endif
                            </div>

                            @if($service->description)
                                <div class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 220) }}</div>
                            @endif

                            @if($schemaCount)
                                <div class="small fw-semibold mb-2">Includes</div>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($schemaLabels->take(6) as $lbl)
                                        <span class="badge text-bg-light text-dark">{{ $lbl }}</span>
                                    @endforeach
                                    @if($schemaLabels->count() > 6)
                                        <span class="badge text-bg-light text-dark">+{{ $schemaLabels->count() - 6 }} more</span>
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-outline-primary flex-grow-1" href="{{ route('services.show', $service) }}">View details</a>
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
