@extends('layouts.public')

@section('title', $service->title)

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
                <h1 class="h3 section-title mb-1">{{ $service->title }}</h1>
                <div class="page-subtitle">Full service details and how to apply</div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('services') }}">Back</a>
                <a class="btn btn-sm btn-primary" href="#apply">Request</a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        @php($heroImage = $service->approach_image_path ?: $service->screenshot_path)
                        @if($heroImage)
                            <a href="{{ asset('storage/'.$heroImage) }}" target="_blank" rel="noopener" class="d-block mb-3">
                                <img class="img-fluid rounded-4 w-100" src="{{ asset('storage/'.$heroImage) }}" alt="{{ $service->title }}">
                            </a>
                        @endif

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($service->is_free)
                                <span class="badge text-bg-success">Free</span>
                            @elseif(!is_null($service->price))
                                <span class="badge text-bg-primary">Cost: ₦{{ number_format((float)$service->price, 2) }}</span>
                            @else
                                <span class="badge text-bg-secondary">Cost: Custom</span>
                            @endif
                            @php($paymentType = $service->payment_type ?: 'one_time')
                            @if($paymentType === 'monthly')
                                <span class="badge text-bg-warning text-dark">Monthly</span>
                            @elseif($paymentType === 'yearly')
                                <span class="badge text-bg-warning text-dark">Yearly</span>
                            @elseif($paymentType === 'custom')
                                <span class="badge text-bg-dark">Custom plan</span>
                            @else
                                <span class="badge text-bg-dark">One-time</span>
                            @endif
                        </div>

                        @if($service->description)
                            <div class="mb-3">{!! $service->description !!}</div>
                        @else
                            <div class="text-muted mb-3">No description provided.</div>
                        @endif

                        @if($service->images->count())
                            <div class="row g-2 mb-3">
                                @foreach($service->images as $img)
                                    <div class="col-6 col-md-4">
                                        <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank" rel="noopener" class="d-block">
                                            <img class="img-fluid rounded-4 w-100" src="{{ asset('storage/'.$img->image_path) }}" alt="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="card border-0" style="background: rgba(0,0,0,0.02);">
                            <div class="card-body">
                                <div class="fw-semibold mb-1">How it works</div>
                                <ol class="mb-0">
                                    <li>Submit your request below with your project details.</li>
                                    <li>We review your request and contact you with a plan, timeline, and final cost.</li>
                                    <li>After confirmation, we start delivery and keep you updated.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="d-flex justify-content-end mb-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('service-portal.login') }}">My Service(s)</a>
                </div>
                <div class="card" id="apply">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Request this service</h2>

                        <form method="post" action="{{ route('services.apply') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            @php($enabled = array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message']))))

                            @if($service->is_free)
                                <div class="alert alert-success">
                                    <div class="fw-semibold">This service is free</div>
                                    <div class="small">No payment is required. After submit, you will receive your Service ID for portal access.</div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <div class="fw-semibold">Payment checkout</div>
                                    <div class="small">After you submit this request, you will continue to checkout and receive your Service ID for portal access.</div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(in_array('phone', $enabled, true) || in_array('company', $enabled, true))
                                <div class="row g-2">
                                    @if(in_array('phone', $enabled, true))
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="phone">Phone</label>
                                                <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if(in_array('company', $enabled, true))
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="company">Company</label>
                                                <input class="form-control @error('company') is-invalid @enderror" id="company" name="company" type="text" value="{{ old('company') }}">
                                                @error('company')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(in_array('budget', $enabled, true))
                                <div class="mb-3">
                                    <label class="form-label" for="budget">Budget (optional)</label>
                                    <input class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" type="text" value="{{ old('budget') }}" placeholder="e.g. ₦200,000 - ₦500,000">
                                    @error('budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if(in_array('message', $enabled, true))
                                <div class="mb-3">
                                    <label class="form-label" for="message">Project details</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if(isset($serviceTypeSchema) && is_array($serviceTypeSchema) && count($serviceTypeSchema))
                                <hr>
                                <div class="fw-semibold mb-2">Additional information ({{ $serviceTypeName ?? ($serviceTypeKey ?? 'Service') }})</div>

                                @foreach($serviceTypeSchema as $field)
                                    @if(!is_array($field) || empty($field['key']))
                                        @continue
                                    @endif

                                    @php($k = preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $field['key']))
                                    @php($k = trim((string) $k, '_'))
                                    @if($k === '')
                                        @continue
                                    @endif

                                    @php($label = (string)($field['label'] ?? $k))
                                    @php($type = strtolower(trim((string)($field['type'] ?? 'text'))))
                                    @php($required = !empty($field['required']))
                                    @php($options = is_array($field['options'] ?? null) ? $field['options'] : [])
                                    @php($pricingMode = strtolower(trim((string)($field['pricing_mode'] ?? 'none'))))
                                    @php($fixedAmount = (float)($field['fixed_amount'] ?? 0))
                                    @php($percent = (float)($field['percent'] ?? 0))

                                    @if($type === 'checkbox')
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}]" type="checkbox" value="1" @checked(old('meta.'.$k))>
                                                <label class="form-check-label" for="meta_{{ $k }}">{{ $label }}</label>
                                                @error('meta.'.$k)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @continue
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label" for="meta_{{ $k }}">{{ $label }}</label>

                                        @if($type === 'textarea')
                                            <textarea class="form-control @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}]" rows="3" @if($required) required @endif>{{ old('meta.'.$k) }}</textarea>
                                            @error('meta.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @elseif($type === 'number')
                                            <input class="form-control @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}]" type="number" value="{{ old('meta.'.$k) }}" @if($required) required @endif>
                                            @error('meta.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @elseif($type === 'select')
                                            <select class="form-select @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}]" @if($required) required @endif>
                                                <option value="">Select</option>
                                                @foreach($options as $opt)
                                                    @php($opt = (string) $opt)
                                                    <option value="{{ $opt }}" @selected(old('meta.'.$k) === $opt)>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @error('meta.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @elseif($type === 'multi_select')
                                            @php($oldArr = old('meta.'.$k, []))
                                            @php($oldArr = is_array($oldArr) ? $oldArr : [])
                                            <select class="form-select @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}][]" multiple @if($required) required @endif>
                                                @foreach($options as $opt)
                                                    @php($opt = (string) $opt)
                                                    <option value="{{ $opt }}" @selected(in_array($opt, $oldArr, true))>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @error('meta.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @elseif($type === 'priced_multi_select')
                                            @php($oldArr = old('meta.'.$k, []))
                                            @php($oldArr = is_array($oldArr) ? $oldArr : [])
                                            <div class="border rounded-3 p-2 priced-multi" data-pricing-mode="{{ $pricingMode }}" data-fixed="{{ $fixedAmount }}" data-percent="{{ $percent }}" data-required="{{ $required ? '1' : '0' }}">
                                                @foreach($options as $idx => $opt)
                                                    @php($val = is_array($opt) ? (string)($opt['value'] ?? $opt['label'] ?? '') : (string)$opt)
                                                    @php($val = trim($val))
                                                    @php($optLabel = is_array($opt) ? (string)($opt['label'] ?? $val) : $val)
                                                    @php($price = is_array($opt) ? (float)($opt['price'] ?? 0) : 0)
                                                    @if($val === '')
                                                        @continue
                                                    @endif
                                                    <div class="form-check">
                                                        <input class="form-check-input priced-opt" id="meta_{{ $k }}_{{ $idx }}" name="meta[{{ $k }}][]" type="checkbox" value="{{ $val }}" data-price="{{ $price }}" @checked(in_array($val, $oldArr, true)) @if($required && $idx === 0) required @endif>
                                                        <label class="form-check-label" for="meta_{{ $k }}_{{ $idx }}">
                                                            {{ $optLabel }}
                                                            @if($price > 0)
                                                                <span class="text-muted small"> — ₦{{ number_format($price, 2) }}</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <div class="mt-2 small text-muted">
                                                    Total: <span class="fw-semibold priced-total">₦0.00</span>
                                                </div>
                                            </div>
                                            @error('meta.'.$k)
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        @elseif($type === 'image')
                                            <input class="form-control @error('meta_files.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta_files[{{ $k }}]" type="file" accept="image/*" @if($required) required @endif>
                                            @error('meta_files.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <input class="form-control @error('meta.'.$k) is-invalid @enderror" id="meta_{{ $k }}" name="meta[{{ $k }}]" type="text" value="{{ old('meta.'.$k) }}" @if($required) required @endif>
                                            @error('meta.'.$k)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <hr>
                                <div class="fw-semibold mb-2">Additional information ({{ $serviceTypeName ?? ($serviceTypeKey ?? 'Service') }})</div>
                                <div class="text-muted small">No extra fields configured for this service type.</div>
                            @endif

                            <button class="btn btn-primary w-100" type="submit">{{ $service->is_free ? 'Submit request' : 'Continue to checkout' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function recalc(box) {
                var wrap = box.closest('.priced-multi');
                if (!wrap) return;
                var opts = wrap.querySelectorAll('.priced-opt');
                var required = wrap.getAttribute('data-required') === '1';
                var first = wrap.querySelector('.priced-opt');
                var sum = 0;
                opts.forEach(function (o) {
                    if (!o.checked) return;
                    var p = parseFloat(o.getAttribute('data-price') || '0') || 0;
                    sum += p;
                });

                if (required && first) {
                    var any = false;
                    opts.forEach(function (o) { if (o.checked) any = true; });
                    if (any) {
                        first.removeAttribute('required');
                    } else {
                        first.setAttribute('required', 'required');
                    }
                }

                var mode = (wrap.getAttribute('data-pricing-mode') || 'none').toLowerCase();
                var fixed = parseFloat(wrap.getAttribute('data-fixed') || '0') || 0;
                var percent = parseFloat(wrap.getAttribute('data-percent') || '0') || 0;
                if (percent < 0) percent = 0;
                if (percent > 100) percent = 100;

                var extra = 0;
                if (mode === 'fixed' || mode === 'fixed_percent') extra += fixed;
                if (mode === 'percent' || mode === 'fixed_percent') extra += (sum * (percent / 100));

                var total = sum + extra;
                if (total < 0) total = 0;

                var out = wrap.querySelector('.priced-total');
                if (out) {
                    out.textContent = '₦' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }

            document.querySelectorAll('.priced-multi').forEach(function (wrap) {
                wrap.querySelectorAll('.priced-opt').forEach(function (opt) {
                    opt.addEventListener('change', function () { recalc(opt); });
                });
                var first = wrap.querySelector('.priced-opt');
                if (first) recalc(first);
            });
        });
    </script>
@endsection
