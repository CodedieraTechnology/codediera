@extends('admin.layout')

@section('title', 'Subscription: ' . ($inquiry->order_code ?: 'Service'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <div class="text-muted small">Service subscription</div>
            <h1 class="h3 mb-1">{{ $inquiry->order_code }}</h1>
            <div class="text-muted">{{ $inquiry->service?->title ?: 'Service' }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.service-subscriptions.index') }}">Back</a>
            <a class="btn btn-outline-primary" href="{{ route('services.checkout', $inquiry->order_code) }}" target="_blank" rel="noopener">Open checkout</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold mb-2">User</div>
                    <div class="row g-2 small">
                        <div class="col-12 col-md-6"><span class="text-muted">Name:</span> {{ $inquiry->name }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Email:</span> {{ $inquiry->email }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Phone:</span> {{ $inquiry->phone ?: '—' }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Company:</span> {{ $inquiry->company ?: '—' }}</div>
                        <div class="col-12"><span class="text-muted">Budget:</span> {{ $inquiry->budget ?: '—' }}</div>
                        <div class="col-12"><span class="text-muted">Message:</span> {{ $inquiry->message ?: '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Portal credentials</div>
                    <div class="row g-2 small">
                        <div class="col-12"><span class="text-muted">Service ID:</span> <span class="fw-semibold">{{ $inquiry->order_code }}</span></div>
                        <div class="col-12"><span class="text-muted">Access Key:</span> <span class="fw-semibold">{{ $inquiry->access_key ?: '—' }}</span></div>
                        <div class="col-12">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('service-portal.login') }}" target="_blank" rel="noopener">Open portal login</a>
                        </div>
                    </div>
                </div>
            </div>

            @php($meta = is_array($inquiry->meta ?? null) ? $inquiry->meta : [])
            @if(count($meta))
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Extra details</div>
                        <div class="row g-2 small">
                            @php($t = $meta['service_type'] ?? ($inquiry->service?->service_type ?? null))
                            @php($t = is_string($t) ? strtolower(trim($t)) : '')
                            @php($fields = is_array($meta['fields'] ?? null) ? $meta['fields'] : [])
                            <div class="col-12"><span class="text-muted">Type:</span> {{ $t ?: '—' }}</div>

                            @if(count($fields))
                                @foreach($fields as $k => $v)
                                    @php($label = ucwords(str_replace('_', ' ', (string) $k)))
                                    @if(is_array($v))
                                        <div class="col-12 col-md-6"><span class="text-muted">{{ $label }}:</span> {{ count($v) ? implode(', ', $v) : '—' }}</div>
                                    @elseif(is_bool($v))
                                        <div class="col-12 col-md-6"><span class="text-muted">{{ $label }}:</span> {{ $v ? 'Yes' : 'No' }}</div>
                                    @elseif(is_string($v) && \Illuminate\Support\Str::startsWith($v, 'service_inquiries/'))
                                        <div class="col-12 col-md-6">
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/' . $v) }}" target="_blank" rel="noopener">{{ $label }}</a>
                                        </div>
                                    @else
                                        <div class="col-12 col-md-6"><span class="text-muted">{{ $label }}:</span> {{ (string) $v !== '' ? $v : '—' }}</div>
                                    @endif
                                @endforeach
                            @endif

                            @if(!empty($meta['custom_fields']) && is_array($meta['custom_fields']))
                                <div class="col-12"><span class="text-muted">Custom fields:</span></div>
                                @foreach($meta['custom_fields'] as $k => $v)
                                    <div class="col-12 col-md-6"><span class="text-muted">{{ $k }}:</span> {{ $v }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Service progress</div>
                    <form method="post" action="{{ route('admin.service-subscriptions.progress', $inquiry) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="progress_percent">Progress %</label>
                                <input class="form-control" id="progress_percent" name="progress_percent" type="number" min="0" max="100" value="{{ old('progress_percent', $inquiry->progress_percent ?? 0) }}">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label" for="progress_note">Note</label>
                                <input class="form-control" id="progress_note" name="progress_note" type="text" value="{{ old('progress_note', $inquiry->progress_note ?? '') }}" placeholder="e.g. Designing UI screens">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Save progress</button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ (int)($inquiry->progress_percent ?? 0) }}%;" aria-valuenow="{{ (int)($inquiry->progress_percent ?? 0) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        @if($inquiry->progress_note)
                            <div class="text-muted small mt-2">{{ $inquiry->progress_note }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Subscription</div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge text-bg-dark">{{ $inquiry->payment_type }}</span>
                        <span class="badge {{ in_array($inquiry->payment_status, ['paid','free'], true) ? 'text-bg-success' : 'text-bg-warning' }}">{{ $inquiry->payment_status }}</span>
                        <span class="badge {{ $inquiry->status === 'active' ? 'text-bg-primary' : 'text-bg-secondary' }}">{{ $inquiry->status }}</span>
                    </div>
                    <div class="row g-2 small">
                        <div class="col-12 col-md-6"><span class="text-muted">Amount:</span> {{ is_null($inquiry->amount) ? '—' : ('₦' . number_format((float)$inquiry->amount, 2)) }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Paid at:</span> {{ $inquiry->paid_at?->format('Y-m-d H:i') ?: '—' }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Next renewal:</span> {{ $inquiry->next_renewal_at?->format('Y-m-d') ?: '—' }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Created:</span> {{ $inquiry->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Payments</div>
                    @if($inquiry->payments->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inquiry->payments as $p)
                                    <tr>
                                        <td>{{ $p->reference }}</td>
                                        <td class="text-end">
                                            @if(!is_null($p->amount))
                                                ₦{{ number_format((float)$p->amount, 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $p->status }}</td>
                                        <td class="text-muted">{{ $p->paid_at?->format('Y-m-d H:i') ?: $p->created_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">No payments yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
