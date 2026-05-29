@extends('layouts.public')

@section('title', $item->title)

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
            <div class="flex-grow-1">
                <h1 class="h3 section-title mb-1">{{ $item->title }}</h1>
                <div class="page-subtitle">Full details, previews, courses and ratings</div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills') }}">Back</a>
                <a class="btn btn-sm btn-outline-primary" href="#outline">Outline</a>
                <a class="btn btn-sm btn-primary" href="#enroll">Enrol</a>
            </div>
        </div>

        @php($isFree = ($item->is_free ?? false) || is_null($item->price) || (float) $item->price <= 0)
        @php($totalLessons = $allLessons->count())
        @php($previewCount = $previewLessons->count())
        @php($courseCount = $allLessons->where('is_preview', false)->count())

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($item->image_path))
                            <a href="{{ asset('storage/'.$item->image_path) }}" target="_blank" rel="noopener" class="d-block mb-3">
                                <img class="img-fluid rounded-4 w-100" src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
                            </a>
                        @endif

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($isFree)
                                <span class="badge text-bg-success">Free</span>
                            @else
                                <span class="badge text-bg-primary">{{ ($item->currency ?: 'NGN') === 'NGN' ? '₦' : ($item->currency.' ') }}{{ number_format((float) $item->price, 2) }}</span>
                            @endif
                            @if(!is_null($avgRating))
                                <span class="badge text-bg-dark">Rating: {{ $avgRating }}/5 ({{ $ratingsCount }})</span>
                            @else
                                <span class="badge text-bg-secondary">No ratings yet</span>
                            @endif
                            @if($hasAccess)
                                <span class="badge text-bg-success">Access: Unlocked</span>
                            @else
                                <span class="badge text-bg-warning text-dark">Access: Preview only</span>
                            @endif
                            @if($item->instructor)
                                <span class="badge text-bg-dark">Instructor: {{ $item->instructor->name }}</span>
                                @if(!empty($instructorRating) && !is_null($instructorRating['avg'] ?? null))
                                    <span class="badge text-bg-secondary">Instructor rating: {{ $instructorRating['avg'] }}/5 ({{ (int) ($instructorRating['count'] ?? 0) }})</span>
                                @endif
                            @endif
                            @if(!is_null($item->total_hours))
                                <span class="badge text-bg-light text-dark">{{ number_format((float)$item->total_hours, 1) }} hours</span>
                            @endif
                            <span class="badge text-bg-light text-dark">Lessons: {{ $totalLessons }}</span>
                            <span class="badge text-bg-light text-dark">Previews: {{ $previewCount }}</span>
                            <span class="badge text-bg-light text-dark">Courses: {{ $courseCount }}</span>
                        </div>

                        @if($item->description)
                            <div class="mb-4">
                                <div id="skillDescWrap" style="max-height: 360px; overflow: hidden;">
                                    {!! $item->description !!}
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" id="skillDescMoreBtn" type="button">Read more</button>
                                </div>
                            </div>
                        @else
                            <div class="text-muted mb-4">No description provided.</div>
                        @endif

                        @if(!empty($previewHero?->video_url))
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <div class="fw-semibold">
                                    Preview lesson:
                                    <a class="text-decoration-none" href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $previewHero->id]) }}">{{ $previewHero->title }}</a>
                                </div>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $previewHero->id]) }}">Open preview</a>
                            </div>
                            <div class="ratio ratio-16x9 mb-2">
                                <iframe src="{{ $previewHero->video_url }}" title="{{ $previewHero->title }}" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                            <div class="mb-4"></div>
                        @endif

                        <div id="outline" class="fw-semibold mb-2">Course outline</div>
                        @if($allLessons->count())
                            <div class="list-group mb-4">
                                @foreach($allLessons as $idx => $l)
                                    @php($locked = !$l->is_preview && !$hasAccess)
                                    @php($badgeTone = $l->is_preview ? 'success' : 'primary')
                                    @php($badgeText = $l->is_preview ? 'Preview' : 'Course')
                                    @php($hasVideo = !empty($l->video_url))
                                    @php($hasPdf = !empty($l->pdf_path))
                                    @php($hasImage = !empty($l->image_path))
                                    @if($locked)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2z"/>
                                                    <path d="M3 7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                                                </svg>
                                                <span>{{ $idx + 1 }}. {{ $l->title }}</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-2">
                                                @if($hasVideo)
                                                    <span class="badge text-bg-light text-dark">Video</span>
                                                @endif
                                                @if($hasPdf)
                                                    <span class="badge text-bg-light text-dark">PDF</span>
                                                @endif
                                                @if($hasImage)
                                                    <span class="badge text-bg-light text-dark">Image</span>
                                                @endif
                                                <span class="badge text-bg-{{ $badgeTone }}">{{ $badgeText }}</span>
                                                <span class="badge text-bg-secondary">Locked</span>
                                            </span>
                                        </div>
                                    @else
                                        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                           href="{{ route('digital-skills.lessons.show', ['item' => $item->id, 'lesson' => $l->id]) }}">
                                            <span class="d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M11 1a2 2 0 0 0-2 2v4h1V3a1 1 0 0 1 2 0v4h1V3a2 2 0 0 0-2-2z"/>
                                                    <path d="M3 7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                                                </svg>
                                                <span>{{ $idx + 1 }}. {{ $l->title }}</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-2">
                                                @if($hasVideo)
                                                    <span class="badge text-bg-light text-dark">Video</span>
                                                @endif
                                                @if($hasPdf)
                                                    <span class="badge text-bg-light text-dark">PDF</span>
                                                @endif
                                                @if($hasImage)
                                                    <span class="badge text-bg-light text-dark">Image</span>
                                                @endif
                                                <span class="badge text-bg-{{ $badgeTone }}">{{ $badgeText }}</span>
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted mb-4">No outline yet.</div>
                        @endif

                        <hr class="my-4">

                        <div class="fw-semibold mb-2">Ratings</div>

                        @if($hasAccess)
                            <form method="post" action="{{ route('digital-skills.rate', $item) }}" class="mb-3">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="rate_name">Name</label>
                                        <input class="form-control @error('r_name') is-invalid @enderror" id="rate_name" name="r_name" type="text" value="{{ old('r_name') }}" required>
                                        @error('r_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="rate_email">Email</label>
                                        <input class="form-control @error('r_email') is-invalid @enderror" id="rate_email" name="r_email" type="email" value="{{ old('r_email') }}" required>
                                        @error('r_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="rate_rating">Rating</label>
                                        <select class="form-select @error('r_rating') is-invalid @enderror" id="rate_rating" name="r_rating" required>
                                            @foreach([5,4,3,2,1] as $r)
                                                <option value="{{ $r }}" @selected((int)old('r_rating', 5) === $r)>{{ $r }}</option>
                                            @endforeach
                                        </select>
                                        @error('r_rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <label class="form-label" for="rate_comment">Comment (optional)</label>
                                        <input class="form-control @error('r_comment') is-invalid @enderror" id="rate_comment" name="r_comment" type="text" value="{{ old('r_comment') }}">
                                        @error('r_comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-outline-primary" type="submit">Submit rating</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="text-muted mb-3">Enrol to rate this course.</div>
                        @endif

                        @if($ratings->count())
                            <div class="list-group">
                                @foreach($ratings as $r)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="fw-semibold">{{ $r->name }}</div>
                                            <div class="badge text-bg-dark">{{ (int) $r->rating }}/5</div>
                                        </div>
                                        @if($r->comment)
                                            <div class="text-muted small mt-1">{{ $r->comment }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">No ratings yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card" id="enroll">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Enrol</h2>

                        @if($hasAccess)
                            <div class="alert alert-success mb-0">
                                <div class="fw-semibold">You have access</div>
                                <div class="small">Preview pages and courses are unlocked for this session.</div>
                            </div>
                        @else
                            @if($isFree)
                                <div class="alert alert-success">
                                    <div class="fw-semibold">This course is free</div>
                                    <div class="small">No payment is required.</div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <div class="fw-semibold">Payment required</div>
                                    <div class="small">After submit, you will continue to checkout.</div>
                                </div>
                            @endif

                            <form method="post" action="{{ route('digital-skills.enroll') }}">
                                @csrf
                                <input type="hidden" name="digital_skills_item_id" value="{{ $item->id }}">

                                <div class="mb-3">
                                    <label class="form-label" for="enroll_name">Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" id="enroll_name" name="name" type="text" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="enroll_email">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" id="enroll_email" name="email" type="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="enroll_phone">Phone (optional)</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" id="enroll_phone" name="phone" type="text" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="enroll_message">Message (optional)</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="enroll_message" name="message" rows="4">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-primary w-100" type="submit">{{ $isFree ? 'Submit enrollment' : 'Continue to checkout' }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var wrap = document.getElementById('skillDescWrap');
            var btn = document.getElementById('skillDescMoreBtn');
            if (!wrap || !btn) return;

            var expanded = false;

            function applyState() {
                if (expanded) {
                    wrap.style.maxHeight = 'none';
                    wrap.style.overflow = 'visible';
                    btn.textContent = 'Show less';
                } else {
                    wrap.style.maxHeight = '360px';
                    wrap.style.overflow = 'hidden';
                    btn.textContent = 'Read more';
                }
            }

            btn.addEventListener('click', function () {
                expanded = !expanded;
                applyState();
            });

            applyState();
        });
    </script>
@endsection
