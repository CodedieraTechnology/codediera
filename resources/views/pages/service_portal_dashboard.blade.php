@extends('layouts.public')

@section('title', 'My Service')

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                </svg>
            </div>
            <div class="flex-grow-1">
                <h1 class="h3 section-title mb-1">{{ $inquiry->service->title }}</h1>
                <div class="page-subtitle">Service ID: <span class="fw-semibold">{{ $inquiry->order_code }}</span></div>
            </div>
            <form method="post" action="{{ route('service-portal.logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                @if($inquiry->status === 'trial')
                    <div class="alert alert-warning">
                        <div class="fw-semibold">3 days free trial</div>
                        <div class="small">Your renewal date has passed. You are in a 3-day grace period. Renew to keep your service active.</div>
                    </div>
                @elseif($inquiry->payment_status === 'trialing' && $inquiry->next_renewal_at && $inquiry->next_renewal_at->isFuture())
                    <div class="alert alert-success">
                        <div class="fw-semibold">Free trial is active</div>
                        <div class="small">Trial ends on {{ $inquiry->next_renewal_at->format('Y-m-d') }}. After the free trial, your subscription will be debited.</div>
                    </div>
                @elseif($inquiry->status === 'expired')
                    <div class="alert alert-danger">
                        <div class="fw-semibold">Service expired</div>
                        <div class="small">Renewal is required to reactivate this service.</div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-{{ $inquiry->status === 'expired' ? 'danger' : 'success' }}">Access key: {{ $inquiry->status === 'expired' ? 'Expired' : 'Active' }}</span>
                            @if($inquiry->service?->delivery_duration_value)
                                <span class="badge text-bg-light text-dark">
                                    Duration: {{ $inquiry->service->delivery_duration_value }} {{ $inquiry->service->delivery_duration_unit ?: 'days' }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">Service progress</div>
                                <div class="small fw-semibold">{{ (int)($inquiry->progress_percent ?? 0) }}%</div>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ (int)($inquiry->progress_percent ?? 0) }}%;" aria-valuenow="{{ (int)($inquiry->progress_percent ?? 0) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($inquiry->progress_note)
                                <div class="text-muted small mt-2">{{ $inquiry->progress_note }}</div>
                            @endif
                        </div>

                        @if($inquiry->next_renewal_at)
                            <div class="mb-3">
                                <div class="text-muted small">{{ $inquiry->next_renewal_at->isPast() ? 'Expired on' : 'Next renewal' }}</div>
                                <div class="fw-semibold">{{ $inquiry->next_renewal_at->format('Y-m-d') }}</div>
                                @if($inquiry->next_renewal_at->isPast())
                                    <div class="text-muted small">Grace ends: {{ $inquiry->renewalGraceEndsAt()?->format('Y-m-d') }}</div>
                                @else
                                    <div class="text-muted small">You will be cut off on: {{ $inquiry->renewalGraceEndsAt()?->format('Y-m-d') }}</div>
                                @endif
                                @if($inquiry->grace_trial_enabled)
                                    <div class="text-muted small">Includes 3 days free trial after expiration before cut off.</div>
                                @endif
                            </div>
                        @else
                            <div class="mb-3 text-muted">No renewal required for this plan.</div>
                        @endif

                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-outline-primary" href="{{ route('services.checkout', $inquiry->order_code) }}">Open Checkout</a>
                            @if(in_array($inquiry->payment_type, ['monthly', 'yearly'], true))
                                <form method="post" action="{{ route('service-portal.renew') }}">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">{{ $inquiry->status === 'expired' ? 'Renew to reactivate' : 'Renew' }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if(in_array($inquiry->payment_status, ['paid', 'free'], true) && $inquiry->status !== 'expired')
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Download & Access</div>
                            @if($inquiry->service->download_url)
                                <a class="btn btn-primary" href="{{ $inquiry->service->download_url }}" target="_blank" rel="noopener">Download / Open App</a>
                            @else
                                <div class="text-muted mb-2">No download link provided yet.</div>
                            @endif
                            <div class="mt-3">
                                <div class="text-muted small">Access Key</div>
                                <div class="fw-semibold">{{ $inquiry->access_key }}</div>
                            </div>
                            @if($inquiry->service->instructions)
                                <hr>
                                <div class="fw-semibold mb-2">Instructions</div>
                                <div>{!! $inquiry->service->instructions !!}</div>
                            @endif
                        </div>
                    </div>
                @endif

                @php($meta = is_array($inquiry->meta ?? null) ? $inquiry->meta : [])
                @if(count($meta))
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Service Details</div>
                            @php($t = $meta['service_type'] ?? ($inquiry->service?->service_type ?? null))
                            @php($t = is_string($t) ? strtolower(trim($t)) : '')
                            @php($fields = is_array($meta['fields'] ?? null) ? $meta['fields'] : [])

                            @if(count($fields))
                                <div class="row g-2 small">
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
                                </div>
                            @else
                                <div class="text-muted small">No additional details.</div>
                            @endif

                            @if(!empty($meta['custom_fields']) && is_array($meta['custom_fields']))
                                <hr>
                                <div class="fw-semibold mb-2">Custom fields</div>
                                <div class="row g-2 small">
                                    @foreach($meta['custom_fields'] as $k => $v)
                                        <div class="col-12 col-md-6"><span class="text-muted">{{ $k }}:</span> {{ $v }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Your Details</div>
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
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Payments</div>
                        @if($inquiry->payments->count())
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th class="text-end">Amount</th>
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
                                            <td class="text-muted">{{ $p->paid_at?->format('Y-m-d') ?: $p->created_at?->format('Y-m-d') }}</td>
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
    </div>
@endsection
