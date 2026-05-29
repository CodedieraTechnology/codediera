@extends('layouts.public')

@section('title', 'Service Checkout')

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold">Payment error</div>
                <div class="small">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                </svg>
            </div>
            <div class="flex-grow-1">
                <h1 class="h3 section-title mb-1">Checkout</h1>
                <div class="page-subtitle">Service ID: <span class="fw-semibold">{{ $inquiry->order_code }}</span></div>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('services.show', $inquiry->service) }}">Back</a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">{{ $inquiry->service->title }}</div>
                        <div class="text-muted mb-3">{{ $inquiry->service->payment_type ?: 'one_time' }}</div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($inquiry->service->is_free)
                                <span class="badge text-bg-success">Free</span>
                            @elseif(!is_null($inquiry->amount))
                                <span class="badge text-bg-primary">Amount: ₦{{ number_format((float)$inquiry->amount, 2) }}</span>
                            @else
                                <span class="badge text-bg-secondary">Pricing: Quote required</span>
                            @endif

                            @if($inquiry->payment_status)
                                <span class="badge text-bg-dark">Status: {{ $inquiry->payment_status }}</span>
                            @endif
                        </div>

                        <div class="text-muted small">
                            Keep your Service ID safe. You will use it to access your private service portal for renewals and monitoring.
                        </div>
                        @if(in_array($inquiry->payment_type, ['monthly', 'yearly'], true) && ($inquiry->grace_trial_enabled ?? true))
                            <div class="text-muted small mt-2">Includes a {{ \App\Models\ServiceInquiry::RENEWAL_GRACE_DAYS }} days free trial. After the free trial, your subscription will be debited.</div>
                        @endif
                    </div>
                </div>

                @if($inquiry->payments->count())
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Payments</div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($inquiry->payments as $p)
                                        <tr>
                                            <td>{{ $p->reference }}</td>
                                            <td>
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
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Payment Checkout</div>

                        @if($inquiry->payment_status === 'quote_required')
                            <div class="text-muted mb-3">This service requires a quote. We will contact you with pricing and a payment link.</div>
                            <a class="btn btn-outline-primary w-100" href="{{ route('service-portal.login') }}">Open Service Portal</a>
                        @elseif(in_array($inquiry->payment_status, ['paid', 'free'], true))
                            <div class="text-muted mb-3">Payment complete. Access your portal to monitor and renew.</div>
                            <a class="btn btn-primary w-100" href="{{ route('service-portal.login') }}">Open Service Portal</a>
                        @elseif($inquiry->payment_status === 'trialing')
                            <div class="alert alert-warning">
                                <div class="fw-semibold">Free trial is active</div>
                                @if($inquiry->next_renewal_at)
                                    <div class="small">Trial ends on {{ $inquiry->next_renewal_at->format('Y-m-d') }}. After this date your subscription will be debited.</div>
                                @endif
                            </div>
                            @if(!$inquiry->paystack_subscription_code)
                                <form method="post" action="{{ route('services.checkout.pay', $inquiry->order_code) }}">
                                    @csrf
                                    @if(($inquiry->service?->payment_type ?: 'one_time') === 'custom' && !in_array($inquiry->payment_type, ['monthly', 'yearly'], true))
                                        <div class="mb-2">
                                            <label class="form-label" for="subscribe_plan">Plan</label>
                                            <select class="form-select" id="subscribe_plan" name="payment_type" required>
                                                <option value="monthly" @selected(old('payment_type') === 'monthly')>Monthly</option>
                                                <option value="yearly" @selected(old('payment_type') === 'yearly')>Yearly</option>
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="payment_type" value="{{ $inquiry->payment_type ?: 'monthly' }}">
                                    @endif
                                    <button class="btn btn-success w-100" type="submit">Subscribe with Paystack</button>
                                </form>
                                <div class="text-muted small mt-2">Your card will be debited automatically after the trial period ends.</div>
                                <hr>
                            @else
                                <div class="text-muted small mb-3">Paystack subscription is active. Your card will be charged automatically.</div>
                            @endif
                            <a class="btn btn-primary w-100" href="{{ route('service-portal.login') }}">Open Service Portal</a>
                        @else
                            @php($servicePlan = $inquiry->service?->payment_type ?: 'one_time')
                            @if(in_array($servicePlan, ['monthly', 'yearly', 'custom'], true) && ($inquiry->grace_trial_enabled ?? true))
                                <div class="alert alert-success">
                                    <div class="fw-semibold">Subscribe to start free trial</div>
                                    <div class="small">Start your free trial now. After the free trial, your subscription will be debited.</div>
                                </div>
                                <form method="post" action="{{ route('services.checkout.pay', $inquiry->order_code) }}">
                                    @csrf
                                    @if($servicePlan === 'custom')
                                        <div class="mb-2">
                                            <label class="form-label" for="trial_plan">Plan</label>
                                            <select class="form-select" id="trial_plan" name="payment_type" required>
                                                <option value="monthly" @selected(old('payment_type') === 'monthly')>Monthly</option>
                                                <option value="yearly" @selected(old('payment_type') === 'yearly')>Yearly</option>
                                            </select>
                                        </div>
                                    @endif
                                    <button class="btn btn-success w-100" type="submit">Paystack Subscribe & Start free trial</button>
                                </form>
                                <hr>
                                <form method="post" action="{{ route('services.checkout.trial', $inquiry->order_code) }}">
                                    @csrf
                                    @if($servicePlan === 'custom')
                                        <input type="hidden" name="payment_type" value="{{ old('payment_type', 'monthly') }}">
                                    @endif
                                    <button class="btn btn-outline-success w-100" type="submit">Start free trial (no card)</button>
                                </form>
                                <div class="text-muted small mt-2">You can subscribe with Paystack anytime during/after the trial.</div>
                                <hr>
                            @endif
                            <form method="post" action="{{ route('services.checkout.pay', $inquiry->order_code) }}">
                                @csrf
                                @if(($inquiry->service?->payment_type ?: 'one_time') === 'custom' && !in_array($servicePlan, ['monthly', 'yearly', 'custom'], true))
                                    <input type="hidden" name="payment_type" value="{{ old('payment_type', 'one_time') }}">
                                @endif
                                <button class="btn btn-primary w-100" type="submit">
                                    @if($inquiry->amount && (float)$inquiry->amount > 0)
                                        Pay with Paystack
                                    @else
                                        Activate
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Service Portal Access</div>
                        <div class="text-muted small mb-2">Use these credentials to login:</div>
                        <div class="small">
                            <div><span class="text-muted">Service ID:</span> <span class="fw-semibold">{{ $inquiry->order_code }}</span></div>
                            <div><span class="text-muted">Email:</span> <span class="fw-semibold">{{ $inquiry->email }}</span></div>
                            <div><span class="text-muted">Access Key:</span> <span class="fw-semibold">{{ $inquiry->access_key }}</span></div>
                            @if($inquiry->next_renewal_at)
                                <div class="mt-2"><span class="text-muted">Next renewal:</span> <span class="fw-semibold">{{ $inquiry->next_renewal_at->format('Y-m-d') }}</span></div>
                                <div><span class="text-muted">You will be cut off on:</span> <span class="fw-semibold">{{ $inquiry->renewalGraceEndsAt()?->format('Y-m-d') }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
