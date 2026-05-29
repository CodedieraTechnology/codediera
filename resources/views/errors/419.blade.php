@extends('layouts.public')

@section('title', 'Page Expired')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.93 6.588-4.29 4.29a.75.75 0 0 1-1.06 0L4.07 8.368a.75.75 0 1 1 1.06-1.06l2.02 2.02 3.76-3.76a.75.75 0 0 1 1.06 1.06z"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="section-kicker">419 Error</div>
                                <h1 class="h3 section-title mb-2">Page expired</h1>
                                <div class="text-muted">
                                    Your session has expired. Please go back and submit the form again.
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a class="btn btn-primary" href="javascript:history.back()">Go Back</a>
                            <a class="btn btn-outline-primary" href="{{ route('home') }}">Back to Home</a>
                        </div>
                    </div>
                </div>
                <div class="text-center text-muted small mt-3">
                    If you keep seeing this, refresh the page before submitting.
                </div>
            </div>
        </div>
    </div>
@endsection

