@extends('layouts.public')

@section('title', 'Apply')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div class="page-head mb-0">
                <div class="icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M6.5 0a.5.5 0 0 0-.5.5V2H2.5A1.5 1.5 0 0 0 1 3.5v11A1.5 1.5 0 0 0 2.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-11A1.5 1.5 0 0 0 13.5 2H10V.5a.5.5 0 0 0-1 0V2H7V.5a.5.5 0 0 0-.5-.5zM2.5 3H13.5a.5.5 0 0 1 .5.5V5H2V3.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M2 6h12v8.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V6z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="h3 section-title">Apply</h1>
                    <div class="page-subtitle">{{ $vacancy->title }}</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('jobs.show', $vacancy) }}">Back</a>
            </div>
        </div>

        <form method="post" action="{{ route('jobs.vacancies.apply.submit', $vacancy) }}" enctype="multipart/form-data" class="card card-body">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="full_name">Full Name</label>
                    <input class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="phone">Phone</label>
                    <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-8 mb-3 d-flex align-items-end">
                    <div class="text-muted">Applying for: <strong>{{ $vacancy->title }}</strong></div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="cv">CV (PDF/DOC/DOCX)</label>
                <input class="form-control @error('cv') is-invalid @enderror" id="cv" name="cv" type="file" accept=".pdf,.doc,.docx">
                @error('cv')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="message">Message</label>
                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4">{{ old('message') }}</textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Submit Application</button>
        </form>
    </div>
@endsection
