@extends('admin.layout')

@section('title', 'Service Subscriptions')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Service Subscriptions</h1>
            <div class="text-muted">Subscribed / purchased users, status and expiration</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-12 col-lg-4">
                    <label class="form-label" for="q">Search</label>
                    <input class="form-control" id="q" name="q" value="{{ request('q') }}" placeholder="Service ID, name, email">
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label" for="payment_status">Payment</label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="">Paid/Free (default)</option>
                        @foreach(['pending' => 'Pending', 'paid' => 'Paid', 'free' => 'Free', 'quote_required' => 'Quote required'] as $k => $v)
                            <option value="{{ $k }}" @selected(request('payment_status') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label" for="payment_type">Plan</label>
                    <select class="form-select" id="payment_type" name="payment_type">
                        <option value="">Any</option>
                        @foreach(['one_time' => 'One-time', 'monthly' => 'Monthly', 'yearly' => 'Yearly', 'custom' => 'Custom'] as $k => $v)
                            <option value="{{ $k }}" @selected(request('payment_type') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Any</option>
                        @foreach(['new' => 'New', 'active' => 'Active', 'trial' => 'Trial', 'expired' => 'Expired', 'paused' => 'Paused', 'cancelled' => 'Cancelled'] as $k => $v)
                            <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.service-subscriptions.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Service</th>
                        <th>User</th>
                        <th>Service ID</th>
                        <th>Plan</th>
                        <th class="text-end">Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Expiration</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($inquiries as $inquiry)
                        @php
                            $isExpiring = $inquiry->next_renewal_at && $inquiry->next_renewal_at->isFuture() && $inquiry->next_renewal_at->diffInDays(now()) <= 7;
                            $isExpired = $inquiry->next_renewal_at && $inquiry->next_renewal_at->isPast();
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $inquiry->service?->title ?: 'Service' }}</div>
                                <div class="text-muted small">Created {{ $inquiry->created_at?->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $inquiry->name }}</div>
                                <div class="text-muted small">{{ $inquiry->email }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $inquiry->order_code }}</div>
                                <div class="text-muted small">
                                    Key: {{ $inquiry->access_key ? (substr($inquiry->access_key, 0, 6) . '…' . substr($inquiry->access_key, -4)) : '—' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-dark">
                                    {{ $inquiry->payment_type === 'monthly' ? 'Monthly' : ($inquiry->payment_type === 'yearly' ? 'Yearly' : ($inquiry->payment_type === 'custom' ? 'Custom' : 'One-time')) }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if(!is_null($inquiry->amount))
                                    ₦{{ number_format((float)$inquiry->amount, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @php($ps = $inquiry->payment_status ?: 'pending')
                                <span class="badge {{ in_array($ps, ['paid','free'], true) ? 'text-bg-success' : ($ps === 'quote_required' ? 'text-bg-secondary' : 'text-bg-warning') }}">
                                    {{ $ps }}
                                </span>
                                <div class="text-muted small">{{ $inquiry->paid_at ? 'Paid ' . $inquiry->paid_at->diffForHumans() : 'Not paid' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $inquiry->status === 'active' ? 'text-bg-primary' : 'text-bg-secondary' }}">{{ $inquiry->status }}</span>
                            </td>
                            <td>
                                @if($inquiry->next_renewal_at)
                                    <div class="fw-semibold">
                                        {{ $inquiry->next_renewal_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-muted small">
                                        @if($isExpired)
                                            Expired
                                        @elseif($isExpiring)
                                            Expiring soon
                                        @else
                                            {{ $inquiry->next_renewal_at->diffForHumans() }}
                                        @endif
                                    </div>
                                @else
                                    <div class="text-muted">—</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.service-subscriptions.show', $inquiry) }}">View</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('services.checkout', $inquiry->order_code) }}" target="_blank" rel="noopener">Checkout</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No subscriptions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $inquiries->links() }}
            </div>
        </div>
    </div>
@endsection
