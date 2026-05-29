@extends('layouts.public')

@section('title', 'Digital Skills')

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">Please fix the errors and try again.</div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 2h12a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H9.5l-1.2 1.6a.5.5 0 0 1-.8 0L6.3 12H3a2 2 0 0 1-2-2V3a1 1 0 0 1 1-1zm0 1v7a1 1 0 0 0 1 1h3.55a.5.5 0 0 1 .4.2L8 12.333l1.05-1.133a.5.5 0 0 1 .4-.2H13a1 1 0 0 0 1-1V3H2z"/>
                    <path d="M4.5 4.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1z"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 section-title">{{ $cta?->heading ?? 'Digital Skills' }}</h1>
                @if($cta?->body)
                    <div class="page-subtitle">{{ $cta->body }}</div>
                @else
                    <div class="page-subtitle">Learn and grow with our digital skills programs.</div>
                @endif
            </div>
        </div>

        <div class="row g-3">
            @forelse($items as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        @if(!empty($item->image_path))
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h2 class="h5">{{ $item->title }}</h2>
                            @if($item->description)
                                <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit(trim(strip_tags($item->description)), 120) }}</p>
                            @endif
                            <div class="mt-2">
                                <a class="small text-decoration-none" href="{{ route('digital-skills.show', $item) }}">Read more</a>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @if(($item->is_free ?? false) || is_null($item->price) || (float)$item->price <= 0)
                                    <span class="badge text-bg-success">Free</span>
                                @else
                                    <span class="badge text-bg-primary">₦{{ number_format((float)$item->price, 2) }}</span>
                                @endif
                                <span class="badge text-bg-light text-dark">Courses: {{ (int) ($item->courses_count ?? 0) }}</span>
                                @if(!is_null($item->total_hours))
                                    <span class="badge text-bg-light text-dark">{{ number_format((float)$item->total_hours, 1) }} hours</span>
                                @endif
                                @if($item->instructor)
                                    <span class="badge text-bg-dark">{{ $item->instructor->name }}</span>
                                    @php($ir = $instructorRatings[$item->instructor_user_id] ?? null)
                                    @if($ir && !is_null($ir['avg']))
                                        <span class="badge text-bg-secondary">Instructor: {{ $ir['avg'] }}/5</span>
                                    @endif
                                @endif
                            </div>
                            <div class="mt-3 d-flex justify-content-end">
                                <a class="btn btn-sm btn-primary" href="{{ route('digital-skills.show', $item) }}">Enrol</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted">No items yet.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
