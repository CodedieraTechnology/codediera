@extends('layouts.public')

@section('title', 'Projects')

@section('content')
    <div class="container py-4">
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-4A.5.5 0 0 1 0 5.5v-4zM1 2v3h3V2H1zm6-.5A.5.5 0 0 1 7.5 1h8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 7 5.5v-4zM8 2v3h7V2H8zM0 9.5A.5.5 0 0 1 .5 9h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-4A.5.5 0 0 1 0 13.5v-4zM1 10v3h3v-3H1zm6-.5A.5.5 0 0 1 7.5 9h8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-4zM8 10v3h7v-3H8z"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 section-title">Our Projects</h1>
                <div class="page-subtitle">Some of our recent work</div>
            </div>
        </div>

        <div class="row g-3">
            @forelse($projects as $project)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 cd-project-card border-0">
                        <div class="card-img-container">
                            @if($project->image_path)
                                <img src="{{ asset('storage/'.$project->image_path) }}" class="card-img-top" alt="{{ $project->title }}" style="height:200px;object-fit:cover">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-laptop opacity-50" viewBox="0 0 16 16">
                                        <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a.5.5 0 0 1 0 1H0a.5.5 0 0 1 0-1z"/>
                                    </svg>
                                </div>
                            @endif
                            @if(!is_null($project->cost) && $project->cost > 0)
                                <span class="badge position-absolute top-0 end-0 m-3 text-bg-primary shadow-sm px-3 py-2 fw-semibold">₦{{ number_format((float)$project->cost, 2) }}</span>
                            @elseif(!is_null($project->cost) && (float)$project->cost === 0.0)
                                <span class="badge position-absolute top-0 end-0 m-3 text-bg-success shadow-sm px-3 py-2 fw-semibold">Free</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h2 class="h6 card-title mb-2">{{ $project->title }}</h2>
                            @if($project->description)
                                <div class="card-text text-muted mb-3 flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($project->description), 110) }}
                                </div>
                            @else
                                <div class="flex-grow-1"></div>
                            @endif
                            <div class="d-flex gap-2 mt-auto">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1 text-center" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#projectDetailModal" 
                                        data-project-title="{{ $project->title }}"
                                        data-project-desc="{{ $project->description }}"
                                        data-project-image="{{ $project->image_path ? asset('storage/'.$project->image_path) : '' }}"
                                        data-project-url="{{ $project->url }}"
                                        data-project-cost="{{ (!is_null($project->cost) && $project->cost > 0) ? '₦'.number_format((float)$project->cost, 2) : ((!is_null($project->cost) && (float)$project->cost === 0.0) ? 'Free' : '') }}"
                                        data-project-zip="{{ $project->zip_path ? asset('storage/'.$project->zip_path) : '' }}">
                                    View Details
                                </button>
                                @if($project->url)
                                    <a class="btn btn-sm btn-primary flex-grow-1 text-center" href="{{ $project->url }}" target="_blank" rel="noreferrer">
                                        Live Demo
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted">No projects added yet.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
