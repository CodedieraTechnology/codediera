@extends('layouts.public')

@section('title', 'Contact')

@section('content')
    <div class="container py-4">
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114V5.383zM14.247 12l-5.53-3.316L8 9.083l-.717-.399L1.753 12H14.247zM1 11.114l4.758-2.876L1 5.383v5.731z"/>
                </svg>
            </div>
            <div>
                <div class="section-kicker">Contact</div>
                <h1 class="h3 section-title">{{ $contactSettings?->heading ?? 'Contact Us' }}</h1>
                @if($contactSettings?->body)
                    <div class="page-subtitle">{{ $contactSettings->body }}</div>
                @else
                    <div class="page-subtitle">Send us a message and we’ll get back to you.</div>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card card-body">
                    <form method="post" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Your full name" autocomplete="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="subject">Subject</label>
                            <input class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" type="text" value="{{ old('subject') }}" placeholder="What is this about?" autocomplete="off">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="message">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Tell us what you need (project, support, partnership, etc.)" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card card-body mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 1 0 2.678 11.894z"/>
                                <path d="M7 7.5c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S5.448 6 6 6s1 .672 1 1.5zM10 7.5c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S8.448 6 9 6s1 .672 1 1.5z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="section-kicker">Contact Details</div>
                            <div class="text-muted">Reach us using the details below.</div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="vstack gap-2">
                        @if($contactSettings?->address)
                            <div class="d-flex align-items-start gap-2">
                                <span class="icon-badge" style="width:34px;height:34px;border-radius:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="fw-semibold">Address</div>
                                    <div class="text-muted">{{ $contactSettings->address }}</div>
                                </div>
                            </div>
                        @endif

                        @if($contactSettings?->phone)
                            <div class="d-flex align-items-start gap-2">
                                <span class="icon-badge" style="width:34px;height:34px;border-radius:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M3.654 1.328a.678.678 0 0 1 .58-.326h2.49c.329 0 .61.223.658.544l.547 3.284a.678.678 0 0 1-.193.605l-1.2 1.2a11.72 11.72 0 0 0 4.516 4.516l1.2-1.2a.678.678 0 0 1 .605-.193l3.284.547a.678.678 0 0 1 .544.658v2.49a.678.678 0 0 1-.326.58l-2.2 1.375a.678.678 0 0 1-.642.05C6.6 14.66 1.34 9.4.553 3.166a.678.678 0 0 1 .05-.642l1.375-2.2z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="fw-semibold">Phone</div>
                                    <a class="text-muted text-decoration-none" href="tel:{{ preg_replace('/\\s+/', '', $contactSettings->phone) }}">{{ $contactSettings->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($contactSettings?->email)
                            <div class="d-flex align-items-start gap-2">
                                <span class="icon-badge" style="width:34px;height:34px;border-radius:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2z"/>
                                        <path d="M0 4.697v7.104l5.803-3.558L0 4.697z"/>
                                        <path d="M6.761 8.83 0 12.97A2 2 0 0 0 2 14h12a2 2 0 0 0 2-1.03L9.239 8.83 8 9.586 6.761 8.83z"/>
                                        <path d="M16 4.697v7.104l-5.803-3.558L16 4.697z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="fw-semibold">Email</div>
                                    <a class="text-muted text-decoration-none" href="mailto:{{ $contactSettings->email }}">{{ $contactSettings->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if(!$contactSettings?->address && !$contactSettings?->phone && !$contactSettings?->email)
                            <div class="text-muted">Contact details will appear here once set from the admin panel.</div>
                        @endif
                    </div>
                </div>
                @if($contactSettings?->map_embed_url)
                    <div class="ratio ratio-16x9">
                        <iframe title="Map" src="{{ $contactSettings->map_embed_url }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
