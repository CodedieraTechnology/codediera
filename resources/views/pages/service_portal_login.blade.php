@extends('layouts.public')

@section('title', 'Service Portal')

@section('content')
    <div class="container py-4">
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 section-title mb-1">Service Portal</h1>
                <div class="page-subtitle">Login to monitor your service, renew, and access downloads</div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <form method="post" action="{{ route('service-portal.login.submit') }}" class="card card-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="order_code">Service ID</label>
                        <input class="form-control @error('order_code') is-invalid @enderror" id="order_code" name="order_code" type="text" value="{{ old('order_code') }}" placeholder="Enter your Service ID" required>
                        @error('order_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="access_key">Access Key</label>
                        <input class="form-control @error('access_key') is-invalid @enderror" id="access_key" name="access_key" type="text" value="{{ old('access_key') }}" placeholder="Enter your access key" required>
                        @error('access_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
@endsection
