@extends('layouts.public')

@section('title', $lesson->title)

@section('content')
    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752C8.154 2.0 9.282 1.8 10.612 1.935c1.234.124 2.503.523 3.388.893V13.5c-.885-.37-2.154-.769-3.388-.893-1.33-.134-2.458.063-3.112.752-.654-.689-1.782-.886-3.112-.752-1.234.124-2.503.523-3.388.893V2.828z"/>
                    <path d="M4 3.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </div>
            <div class="flex-grow-1">
                <div class="text-muted small">{{ $item->title }}</div>
                <h1 class="h4 section-title mb-0">{{ $lesson->title }}</h1>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills.show', $item) }}">Back</a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($lesson->brief_info))
                            <div class="text-muted mb-3">{{ $lesson->brief_info }}</div>
                        @endif

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if(!empty($lesson->pdf_path))
                                <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" rel="noopener">Open PDF</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/' . $lesson->pdf_path) }}" download>Download PDF</a>
                            @endif
                            @if(!empty($lesson->video_url))
                                <a class="btn btn-sm btn-outline-primary" href="#video">Video</a>
                            @endif
                        </div>

                        @if(!empty($lesson->image_path))
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $lesson->image_path) }}" alt="{{ $lesson->title }}" class="img-fluid rounded border">
                            </div>
                        @endif

                        @php($v = trim((string) ($lesson->video_url ?? '')))
                        @if($v !== '')
                            <div id="video"></div>
                            @php($isFile = preg_match('/\\.(mp4|webm|ogg)(\\?.*)?$/i', $v) === 1)
                            @if($isFile)
                                <video class="w-100 mb-3" controls>
                                    <source src="{{ $v }}">
                                </video>
                            @else
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $v }}" title="{{ $lesson->title }}" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                        @endif

                        @if($lesson->content)
                            <div>{!! $lesson->content !!}</div>
                        @else
                            @if(empty($lesson->brief_info) && empty($lesson->pdf_path) && empty($lesson->image_path) && empty($lesson->video_url))
                                <div class="text-muted">No content provided.</div>
                            @endif
                        @endif

                        <div class="d-flex justify-content-between gap-2 mt-4">
                            @if(!empty($prevLesson))
                                <a class="btn btn-outline-primary" href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $prevLesson->id]) }}">Previous</a>
                            @else
                                <span></span>
                            @endif
                            @if(!empty($nextLesson))
                                <a class="btn btn-outline-primary" href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $nextLesson->id]) }}">Next</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Lessons</div>
                        <div class="list-group">
                            @foreach(($lessons ?? collect()) as $l)
                                @php($locked = !$l->is_preview && !$hasAccess)
                                @php($isCurrent = (int) $l->id === (int) $lesson->id)
                                @php($tone = $l->is_preview ? 'success' : 'primary')
                                @if($locked)
                                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $isCurrent ? 'active' : '' }}">
                                        <span class="d-flex align-items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2z"/>
                                                <path d="M3 7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                                            </svg>
                                            <span>{{ $l->title }}</span>
                                        </span>
                                        <span class="badge text-bg-secondary">Locked</span>
                                    </div>
                                @else
                                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isCurrent ? 'active' : '' }}"
                                       href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $l->id]) }}">
                                        <span class="d-flex align-items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M11 1a2 2 0 0 0-2 2v4h1V3a1 1 0 0 1 2 0v4h1V3a2 2 0 0 0-2-2z"/>
                                                <path d="M3 7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                                            </svg>
                                            <span>{{ $l->title }}</span>
                                        </span>
                                        <span class="badge text-bg-{{ $tone }}">{{ $l->is_preview ? 'Preview' : 'Course' }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
