@extends('layouts.public')

@section('title', $vacancy->title)

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
                    <h1 class="h3 section-title">{{ $vacancy->title }}</h1>
                    <div class="page-subtitle">
                        @if($vacancy->location)
                            {{ $vacancy->location }}
                        @endif
                        @if($vacancy->employment_type)
                            {{ $vacancy->location ? ' · ' : '' }}{{ $vacancy->employment_type }}
                        @endif
                        @if($vacancy->posted_at)
                            {{ ($vacancy->location || $vacancy->employment_type) ? ' · ' : '' }}Posted: {{ $vacancy->posted_at->format('Y-m-d') }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('jobs.index') }}">Back</a>
                <a class="btn btn-primary" href="{{ route('jobs.vacancies.apply', $vacancy) }}">Apply</a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="card card-body">
                    @if($vacancy->description)
                        <div class="h5 mb-2">Description</div>
                        <div style="white-space: pre-wrap">{{ $vacancy->description }}</div>
                    @endif

                    @if($vacancy->responsibilities)
                        <hr>
                        <div class="h5 mb-2">Responsibilities</div>
                        <div style="white-space: pre-wrap">{{ $vacancy->responsibilities }}</div>
                    @endif

                    @if($vacancy->requirements)
                        <hr>
                        <div class="h5 mb-2">Requirements</div>
                        <div style="white-space: pre-wrap">{{ $vacancy->requirements }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card card-body">
                    <div class="h5 mb-2">Details</div>
                    @if($vacancy->salary)
                        <div class="mb-2"><strong>Salary:</strong> {{ $vacancy->salary }}</div>
                    @endif
                    @if($vacancy->location)
                        <div class="mb-2"><strong>Location:</strong> {{ $vacancy->location }}</div>
                    @endif
                    @if($vacancy->employment_type)
                        <div class="mb-2"><strong>Type:</strong> {{ $vacancy->employment_type }}</div>
                    @endif
                    <a class="btn btn-primary mt-2" href="{{ route('jobs.vacancies.apply', $vacancy) }}">Apply Now</a>
                </div>
            </div>
        </div>
    </div>
@endsection
