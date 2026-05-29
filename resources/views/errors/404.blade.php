@extends('layouts.public')

@section('title', 'Page Not Found')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="section-kicker">404 Error</div>
                                <h1 class="h3 section-title mb-2">Page not found</h1>
                                <div class="text-muted">
                                    The page you’re looking for doesn’t exist or has been moved.
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a class="btn btn-primary" href="{{ route('home') }}">Back to Home</a>
                            <a class="btn btn-outline-primary" href="javascript:history.back()">Go Back</a>
                        </div>
                    </div>
                </div>
                <div class="text-center text-muted small mt-3">
                    If you think this is a mistake, please contact support.
                </div>
            </div>
        </div>
    </div>
@endsection

