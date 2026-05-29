@extends('layouts.public')

@section('title', 'Service Timeout')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 3.5a.5.5 0 0 1 .5.5v4.25l2.4 1.44a.5.5 0 1 1-.514.858l-2.65-1.59A.5.5 0 0 1 7.5 9V4a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="section-kicker">504 Error</div>
                                <h1 class="h3 section-title mb-2">Service timeout</h1>
                                <div class="text-muted">
                                    The server took too long to respond. Please retry.
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a class="btn btn-primary" href="{{ $retryUrl ?? url()->current() }}">Retry</a>
                            <a class="btn btn-outline-primary" href="{{ route('home') }}">Back to Home</a>
                        </div>
                    </div>
                </div>
                <div class="text-center text-muted small mt-3">
                    If this keeps happening, please contact support.
                </div>
            </div>
        </div>
    </div>
@endsection
