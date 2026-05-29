@extends('layouts.public')

@section('title', 'Instructor Dashboard')

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1">Instructor Dashboard</h1>
                <div class="text-muted">Your courses and ratings</div>
            </div>
            <form method="post" action="{{ route('instructor.logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            @if(!is_null($avgRating))
                <span class="badge text-bg-dark">Average rating: {{ $avgRating }}/5</span>
                <span class="badge text-bg-secondary">Ratings: {{ $ratingsCount }}</span>
            @else
                <span class="badge text-bg-secondary">No ratings yet</span>
            @endif
            <span class="badge text-bg-light text-dark">Courses: {{ $items->count() }}</span>
        </div>

        <div class="row g-3">
            @forelse($items as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="fw-semibold">{{ $item->title }}</div>
                            <div class="text-muted small mt-1">
                                @if(!is_null($item->total_hours))
                                    {{ number_format((float)$item->total_hours, 1) }} hours
                                @else
                                    Hours: not set
                                @endif
                            </div>
                            <div class="mt-3">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills.show', $item) }}">View public page</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted">No courses assigned to you yet.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
