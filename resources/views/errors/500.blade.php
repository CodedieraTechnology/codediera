@extends('layouts.public')

@section('title', 'Server Error')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-10.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l1.002-4.705c.076-.34-.006-.545-.999-.42z"/>
                                    <circle cx="8" cy="4.5" r="1"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="section-kicker">Something went wrong</div>
                                <h1 class="h3 section-title mb-2">Server error</h1>
                                <div class="text-muted">
                                    We couldn’t complete your request. Please try again.
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a class="btn btn-primary" href="{{ route('home') }}">Back to Home</a>
                            <a class="btn btn-outline-primary" href="{{ url()->current() }}">Retry</a>
                        </div>
                    </div>
                </div>
                <div class="text-center text-muted small mt-3">
                    If the issue persists, contact support.
                </div>
            </div>
        </div>
    </div>
@endsection

