@extends('layouts.public')

@section('title', 'Service Unavailable')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H9.5l-1.2 1.6a.5.5 0 0 1-.8 0L6.3 12H4a2 2 0 0 1-2-2V2z"/>
                                    <path d="M5.5 4.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zM5 7a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1zm.5 3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="section-kicker">Please wait</div>
                                <h1 class="h3 section-title mb-2">Service temporarily unavailable</h1>
                                <div class="text-muted">
                                    The server is taking too long to respond or is under maintenance. Please try again.
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

