@extends('layouts.public')

@section('title', 'Jobs')

@section('content')
    <div class="container py-4">
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M6.5 0a.5.5 0 0 0-.5.5V2H2.5A1.5 1.5 0 0 0 1 3.5v11A1.5 1.5 0 0 0 2.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-11A1.5 1.5 0 0 0 13.5 2H10V.5a.5.5 0 0 0-1 0V2H7V.5a.5.5 0 0 0-.5-.5zM2.5 3H13.5a.5.5 0 0 1 .5.5V5H2V3.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M2 6h12v8.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V6z"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 section-title">Job Vacancies</h1>
                <div class="page-subtitle">Apply only to available openings</div>
            </div>
        </div>

        @if($vacancies->count() === 0)
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="h5 mb-1">No vacancies available</div>
                        <div class="text-muted">Please check back later.</div>
                    </div>
                    <a class="btn btn-outline-primary" href="{{ route('contact') }}">Contact Us</a>
                </div>
            </div>
        @else
            <div class="row g-3">
                @foreach($vacancies as $vacancy)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h2 class="h5 mb-1">{{ $vacancy->title }}</h2>
                                <div class="text-muted">
                                    {{ $vacancy->location ? $vacancy->location : 'Location: Not specified' }}
                                    @if($vacancy->employment_type)
                                        · {{ $vacancy->employment_type }}
                                    @endif
                                </div>
                                @if($vacancy->posted_at)
                                    <div class="small text-muted mt-2">Posted: {{ $vacancy->posted_at->format('Y-m-d') }}</div>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('jobs.show', $vacancy) }}">Details</a>
                                <a class="btn btn-sm btn-primary" href="{{ route('jobs.vacancies.apply', $vacancy) }}">Apply</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
