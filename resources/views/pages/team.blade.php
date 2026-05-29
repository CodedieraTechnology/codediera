@extends('layouts.public')

@section('title', 'Team')

@section('content')
    <div class="container py-4">
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    <path d="M14 14s-1-4-6-4-6 4-6 4 1 2 6 2 6-2 6-2z"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 section-title">Our Team</h1>
                <div class="page-subtitle">Meet the people behind Codediera</div>
            </div>
        </div>

        <div class="row g-3">
            @forelse($team as $member)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div>
                                    @if($member->photo_path)
                                        <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" style="width:72px;height:72px;object-fit:cover;border-radius:36px">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:36px">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h2 class="h5 mb-1">{{ $member->name }}</h2>
                                    @if($member->role)
                                        <div class="text-muted">{{ $member->role }}</div>
                                    @endif
                                    @if($member->bio)
                                        <div class="mt-2">{{ $member->bio }}</div>
                                    @endif
                                    @if($member->social_links)
                                        <div class="mt-2 d-flex flex-wrap gap-2">
                                            @foreach($member->social_links as $key => $url)
                                                <a href="{{ $url }}" target="_blank" rel="noreferrer" class="badge text-bg-light text-decoration-none">{{ ucfirst($key) }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted">No team members added yet.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
