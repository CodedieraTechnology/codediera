@extends('layouts.public')

@section('title', 'Digital Skills Checkout')

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h12a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H9.5l-1.2 1.6a.5.5 0 0 1-.8 0L6.3 12H3a2 2 0 0 1-2-2V3a1 1 0 0 1 1-1z"/>
                    <path d="M4.5 4.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1z"/>
                </svg>
            </div>
            <div class="flex-grow-1">
                <h1 class="h3 section-title mb-1">Checkout</h1>
                <div class="page-subtitle">{{ $enrollment->item?->title ?: 'Digital Skill' }}</div>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills.show', $enrollment->item) }}">Back</a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">Enrollment</div>
                        <div class="text-muted mb-3">Name: {{ $enrollment->name }} • Email: {{ $enrollment->email }}</div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge text-bg-dark">Payment: {{ $enrollment->payment_status }}</span>
                            @if(!is_null($enrollment->amount))
                                <span class="badge text-bg-primary">Amount: ₦{{ number_format((float)$enrollment->amount, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Payment</div>

                        @if($enrollment->payment_status === 'paid' || $enrollment->payment_status === 'free')
                            <div class="text-muted mb-3">Payment complete. Your enrollment is confirmed.</div>
                            <a class="btn btn-primary w-100" href="{{ route('digital-skills.show', $enrollment->item) }}">Go to course</a>
                        @else
                            <form method="post" action="{{ route('digital-skills.checkout.pay', $enrollment) }}">
                                @csrf
                                <button class="btn btn-primary w-100" type="submit">Pay Now</button>
                            </form>
                            <div class="text-muted small mt-2">This is a placeholder checkout. Connect your payment provider later.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
