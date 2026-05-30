@extends('layouts.public')

@section('title', 'Home')

@section('content')
    @if($sliders->count() > 0)
        <!-- Hero Section with Carousel Background and Card Overlay -->
        <section class="hero-wrapper position-relative mb-5">
            <style>
                .hero-wrapper {
                    overflow: hidden;
                    border-radius: 0;
                }
                .hero-wrapper #homeCarousel {
                    height: 480px; /* Default height for mobile/tablet */
                }
                @media (max-width: 991.98px) {
                    .hero-overlay-container {
                        padding: 1rem;
                    }
                }
                @media (min-width: 992px) {
                    .hero-wrapper #homeCarousel {
                        height: calc(100vh - (var(--cd-nav-height) + var(--cd-topbar-height))); /* Full viewport height on desktop */
                    }
                }
                .hero-wrapper #homeCarousel .carousel-inner,
                .hero-wrapper #homeCarousel .carousel-item {
                    height: 100%;
                }
                .hero-overlay-container {
                    position: absolute;
                    inset: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 1.5rem;
                    z-index: 5;
                    background: rgba(15, 23, 42, 0.55); /* Soft dark overlay for better text readability */
                }
                .hero-overlay-card {
                    background: transparent !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                    max-width: 900px;
                    width: 100%;
                }
                /* Global overlay text colors (white text for readability on top of slide background) */
                .hero-overlay-card .section-kicker {
                    color: rgba(255, 255, 255, 0.75) !important;
                }
                .hero-overlay-card .section-title {
                    color: #ffffff !important;
                }
                .hero-overlay-card .text-muted {
                    color: rgba(255, 255, 255, 0.85) !important;
                }
                .hero-overlay-card .btn-outline-primary {
                    color: #ffffff !important;
                    border-color: #ffffff !important;
                }
                .hero-overlay-card .btn-outline-primary:hover {
                    background-color: #ffffff !important;
                    color: var(--cd-primary) !important;
                }
                /* Hide carousel-caption when overlaid to prevent double headings */
                .hero-wrapper .carousel-caption,
                .hero-wrapper .carousel-mobile-caption {
                    display: none !important;
                }
            </style>

            <!-- Sliding Carousel -->
            <div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner overflow-hidden">
                    @foreach($sliders as $index => $slide)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            @php($hasMedia = !empty($slide->video_path) || !empty($slide->image_path))
                            @if(!empty($slide->video_path))
                                <video class="d-block w-100" autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback" style="height: 100%; object-fit: cover;" @if(!empty($slide->image_path)) poster="{{ asset('storage/'.$slide->image_path) }}" @endif>
                                    <source src="{{ asset('storage/'.$slide->video_path) }}">
                                </video>
                            @elseif(!empty($slide->image_path))
                                <img src="{{ asset('storage/'.$slide->image_path) }}" class="d-block w-100" alt="{{ $slide->title }}" style="height: 100%; object-fit: cover;">
                            @endif

                            @if(!$hasMedia)
                                <div class="cd-carousel-fallback d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 100%;">
                                    <div class="text-center">
                                        <div class="h2 mb-0">{{ $slide->title }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Hero Card Overlayed on Top of the Slide -->
            <div class="hero-overlay-container">
                <div class="card hero-overlay-card">
                    <div class="card-body p-0">
                        <div class="row justify-content-center text-center">
                            <div class="col-12 col-lg-10">
                                <div class="section-kicker">{{ $siteSettings?->home_hero_kicker ?: 'What We Do' }}</div>
                                <h1 class="h3 h2-lg section-title mb-2">{{ $siteSettings?->home_hero_title ?: ($siteSettings?->site_name ?? config('app.name', 'Codediera')) }}</h1>
                                <div class="text-muted mb-3 mx-auto" style="max-width: 800px;">
                                    {{ $siteSettings?->home_hero_body ?: 'We build modern websites, web applications, and business systems that help you sell, manage customers, and automate work. We also deliver digital skills training and career support for students and professionals.' }}
                                </div>
                                <div class="d-flex justify-content-center gap-3">
                                    <a class="btn btn-primary" href="{{ route('services') }}">Explore Services</a>
                                    <a class="btn btn-outline-primary" href="{{ route('contact') }}">Talk to Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="container py-4">
        @if($sliders->count() == 0)
            <!-- Standard Hero Card if no sliders (Transparent Background) -->
            <section class="mb-5">
                <div class="card bg-transparent border-0 shadow-none">
                    <div class="card-body p-0">
                        <div class="row justify-content-center text-center">
                            <div class="col-12 col-lg-10">
                                <div class="section-kicker">{{ $siteSettings?->home_hero_kicker ?: 'What We Do' }}</div>
                                <h1 class="h3 h2-lg section-title mb-2">{{ $siteSettings?->home_hero_title ?: ($siteSettings?->site_name ?? config('app.name', 'Codediera')) }}</h1>
                                <div class="text-muted mb-3 mx-auto" style="max-width: 800px;">
                                    {{ $siteSettings?->home_hero_body ?: 'We build modern websites, web applications, and business systems that help you sell, manage customers, and automate work. We also deliver digital skills training and career support for students and professionals.' }}
                                </div>
                                <div class="d-flex justify-content-center gap-3">
                                    <a class="btn btn-primary" href="{{ route('services') }}">Explore Services</a>
                                    <a class="btn btn-outline-primary" href="{{ route('contact') }}">Talk to Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @php($apply = $ctas->get('apply_for_job'))
        @php($skills = $ctas->get('get_digital_skills'))

        <section class="mb-5">
            <div class="section-head">
                <div>
                    <div class="section-kicker">Quick Links</div>
                    <h2 class="h4 section-title mb-0">Start Here</h2>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="fw-semibold">Services</div>
                                    <div class="small text-muted">What we can build</div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('services') }}">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M6.5 0a.5.5 0 0 0-.5.5V2H2.5A1.5 1.5 0 0 0 1 3.5v11A1.5 1.5 0 0 0 2.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-11A1.5 1.5 0 0 0 13.5 2H10V.5a.5.5 0 0 0-1 0V2H7V.5a.5.5 0 0 0-.5-.5zM2.5 3H13.5a.5.5 0 0 1 .5.5V5H2V3.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M2 6h12v8.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $apply?->heading ?? 'Job' }}</div>
                                    <div class="small text-muted">See open roles</div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-primary" href="{{ route('jobs.apply') }}">{{ $apply?->button_text ?? 'Apply' }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M9.293 0H4.5A1.5 1.5 0 0 0 3 1.5v13A1.5 1.5 0 0 0 4.5 16h7a1.5 1.5 0 0 0 1.5-1.5V4.707a1 1 0 0 0-.293-.707L10 .293A1 1 0 0 0 9.293 0zM10 1.5 11.5 3H10a.5.5 0 0 1-.5-.5V1.5z"/>
                                        <path d="M4.5 9a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="fw-semibold">IT Intake Form</div>
                                    <div class="small text-muted">Submit your form</div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('it-intake') }}">Open Form</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M2 2h12a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H9.5l-1.2 1.6a.5.5 0 0 1-.8 0L6.3 12H3a2 2 0 0 1-2-2V3a1 1 0 0 1 1-1zm0 1v7a1 1 0 0 0 1 1h3.55a.5.5 0 0 1 .4.2L8 12.333l1.05-1.133a.5.5 0 0 1 .4-.2H13a1 1 0 0 0 1-1V3H2z"/>
                                        <path d="M4.5 4.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $skills?->heading ?? 'Digital Skills' }}</div>
                                    <div class="small text-muted">Learn and grow</div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('digital-skills') }}">{{ $skills?->button_text ?? 'View' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <div class="section-head">
                <div>
                    <div class="section-kicker">Services</div>
                    <h2 class="h4 section-title mb-0">What We Do</h2>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('services') }}">View all</a>
            </div>
            <style>
                #homeServicesScroller {
                    overflow-x: auto;
                    overflow-y: visible;
                    scroll-snap-type: x mandatory;
                    -webkit-overflow-scrolling: touch;
                    padding-bottom: 6px;
                }
                #homeServicesScroller .cd-hscroll-track {
                    display: flex;
                    gap: 1rem;
                    padding: 0.25rem 0.25rem 0.75rem;
                }
                #homeServicesScroller .cd-hscroll-item {
                    flex: 0 0 auto;
                    width: min(340px, 86vw);
                    scroll-snap-align: start;
                }
                @media (max-width: 575.98px) {
                    #homeServicesScroller .cd-hscroll-track {
                        gap: 0.75rem;
                        padding: 0.25rem 0.25rem 0.75rem;
                    }
                    #homeServicesScroller .cd-hscroll-item {
                        width: calc(100% - 0.5rem);
                    }
                    #homeServicesScroller .card-img-top {
                        height: 160px !important;
                    }
                }
                @media (min-width: 768px) {
                    #homeServicesScroller .cd-hscroll-item {
                        width: 360px;
                    }
                }
                @media (min-width: 1200px) {
                    #homeServicesScroller .cd-hscroll-item {
                        width: 380px;
                    }
                }

                /* Redesigned Services Card Styling */
                .cd-service-card {
                    border-radius: 1.25rem !important;
                    overflow: hidden;
                    border: 1px solid var(--cd-border) !important;
                    background: var(--cd-surface) !important;
                    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04) !important;
                    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease !important;
                }
                .cd-service-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 20px 35px rgba(15, 23, 42, 0.08) !important;
                }
                .cd-card-img-wrapper {
                    position: relative;
                    overflow: hidden;
                    height: 190px;
                    width: 100%;
                    border-radius: 1.25rem 1.25rem 0 0;
                }
                .cd-card-img-wrapper img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                }
                .cd-service-card:hover .cd-card-img-wrapper img {
                    transform: scale(1.06);
                }
                .cd-glass-badge {
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    padding: 0.4rem 0.85rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                    border-radius: 2rem;
                    z-index: 2;
                    backdrop-filter: blur(12px);
                    -webkit-backdrop-filter: blur(12px);
                    background: rgba(15, 23, 42, 0.65);
                    color: #ffffff;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }
                .cd-glass-badge.bg-free-badge {
                    background: rgba(22, 163, 74, 0.85);
                    border-color: rgba(255, 255, 255, 0.25);
                }
                .cd-service-icon-box {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 2.25rem;
                    height: 2.25rem;
                    border-radius: 50%;
                    background: rgba(13, 110, 253, 0.08);
                    color: var(--cd-primary);
                    font-size: 1.1rem;
                    flex-shrink: 0;
                }
                .cd-service-title {
                    font-size: 1.05rem;
                    font-weight: 600;
                    margin-bottom: 0;
                    line-height: 1.4;
                }
                .cd-service-title a {
                    color: var(--cd-heading) !important;
                    transition: color 0.2s ease;
                }
                .cd-service-title a:hover {
                    color: var(--cd-primary) !important;
                }
                .cd-service-description {
                    font-size: 0.875rem;
                    color: var(--cd-muted);
                    line-height: 1.6;
                    margin-top: 0.5rem;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    height: 4.8em; /* Exact height for 3 lines */
                }
                .cd-service-footer {
                    margin-top: 1.25rem;
                    padding-top: 1rem;
                    border-top: 1px solid var(--cd-border);
                }
                .cd-service-footer .btn-outline-primary {
                    border-radius: 2rem;
                    font-weight: 500;
                    padding: 0.35rem 1rem;
                    font-size: 0.825rem;
                    transition: all 0.3s ease;
                }
                .cd-service-card:hover .cd-service-footer .btn-outline-primary {
                    background-color: var(--cd-primary) !important;
                    border-color: var(--cd-primary) !important;
                    color: #ffffff !important;
                }
                .cd-badge-pill {
                    font-size: 0.7rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.03em;
                    padding: 0.25rem 0.65rem;
                    border-radius: 2rem;
                    display: inline-flex;
                    align-items: center;
                }
                .cd-badge-pill.payment-recurring {
                    background-color: rgba(245, 158, 11, 0.08);
                    color: #d97706;
                    border: 1px solid rgba(245, 158, 11, 0.15);
                }
                .cd-badge-pill.payment-custom {
                    background-color: rgba(100, 116, 139, 0.08);
                    color: #475569;
                    border: 1px solid rgba(100, 116, 139, 0.15);
                }
                .cd-badge-pill.trial-badge {
                    background-color: rgba(13, 110, 253, 0.08);
                    color: var(--cd-primary);
                    border: 1px solid rgba(13, 110, 253, 0.15);
                }

                /* Dark Theme Overrides */
                [data-theme="dark"] .cd-service-card {
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
                }
                [data-theme="dark"] .cd-service-card:hover {
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45) !important;
                }
                [data-theme="dark"] .cd-service-icon-box {
                    background: rgba(255, 255, 255, 0.08);
                    color: #ffffff;
                }
                [data-theme="dark"] .cd-glass-badge {
                    background: rgba(17, 24, 39, 0.75);
                    border-color: rgba(255, 255, 255, 0.12);
                }
                [data-theme="dark"] .cd-badge-pill.payment-recurring {
                    background-color: rgba(245, 158, 11, 0.15);
                    color: #fbbf24;
                    border-color: rgba(245, 158, 11, 0.25);
                }
                [data-theme="dark"] .cd-badge-pill.payment-custom {
                    background-color: rgba(148, 163, 184, 0.15);
                    color: #cbd5e1;
                    border-color: rgba(148, 163, 184, 0.25);
                }
                [data-theme="dark"] .cd-badge-pill.trial-badge {
                    background-color: rgba(13, 110, 253, 0.15);
                    color: #60a5fa;
                    border-color: rgba(13, 110, 253, 0.25);
                }

                /* Redesigned Projects Card Styling */
                .cd-project-card {
                    border-radius: 1.25rem !important;
                    overflow: hidden;
                    border: 1px solid var(--cd-border) !important;
                    background: var(--cd-surface) !important;
                    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04) !important;
                    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease !important;
                }
                .cd-project-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 20px 35px rgba(15, 23, 42, 0.08) !important;
                }
                .cd-project-img-wrapper {
                    position: relative;
                    overflow: hidden;
                    height: 200px;
                    width: 100%;
                    border-radius: 1.25rem 1.25rem 0 0;
                }
                .cd-project-img-wrapper img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                }
                .cd-project-card:hover .cd-project-img-wrapper img {
                    transform: scale(1.06);
                }
                .cd-project-tag-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.4rem;
                    margin-bottom: 0.75rem;
                }
                .cd-project-tag {
                    font-size: 0.7rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.03em;
                    padding: 0.2rem 0.55rem;
                    border-radius: 2rem;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.25rem;
                }
                .cd-project-tag.tag-live {
                    background-color: rgba(16, 185, 129, 0.08);
                    color: #059669;
                    border: 1px solid rgba(16, 185, 129, 0.15);
                }
                .cd-project-tag.tag-zip {
                    background-color: rgba(99, 102, 241, 0.08);
                    color: #4f46e5;
                    border: 1px solid rgba(99, 102, 241, 0.15);
                }
                .cd-project-tag.tag-commercial {
                    background-color: rgba(245, 158, 11, 0.08);
                    color: #d97706;
                    border: 1px solid rgba(245, 158, 11, 0.15);
                }
                .cd-project-tag.tag-free {
                    background-color: rgba(22, 163, 74, 0.08);
                    color: #15803d;
                    border: 1px solid rgba(22, 163, 74, 0.15);
                }
                .cd-project-tag.tag-new {
                    background-color: rgba(13, 110, 253, 0.08);
                    color: var(--cd-primary);
                    border: 1px solid rgba(13, 110, 253, 0.15);
                }
                .cd-project-title {
                    font-size: 1.05rem;
                    font-weight: 600;
                    margin-bottom: 0.5rem;
                    line-height: 1.4;
                    color: var(--cd-heading);
                }
                .cd-project-description {
                    font-size: 0.875rem;
                    color: var(--cd-muted);
                    line-height: 1.6;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    height: 4.8em; /* Exact height for 3 lines */
                    margin-bottom: 1.25rem;
                }
                .cd-project-footer {
                    margin-top: auto;
                    padding-top: 1rem;
                    border-top: 1px solid var(--cd-border);
                    display: flex;
                    gap: 0.5rem;
                }
                .cd-project-footer .btn {
                    border-radius: 2rem;
                    font-weight: 500;
                    font-size: 0.825rem;
                    padding: 0.4rem 1rem;
                    flex: 1;
                    transition: all 0.3s ease;
                }
                .cd-project-footer .btn-primary {
                    background-color: var(--cd-primary) !important;
                    border-color: var(--cd-primary) !important;
                }
                .cd-project-footer .btn-primary:hover {
                    opacity: 0.9;
                }

                /* Dark Theme Overrides for Projects */
                [data-theme="dark"] .cd-project-card {
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
                }
                [data-theme="dark"] .cd-project-card:hover {
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45) !important;
                }
                [data-theme="dark"] .cd-project-tag.tag-live {
                    background-color: rgba(16, 185, 129, 0.15);
                    color: #34d399;
                    border-color: rgba(16, 185, 129, 0.25);
                }
                [data-theme="dark"] .cd-project-tag.tag-zip {
                    background-color: rgba(99, 102, 241, 0.15);
                    color: #818cf8;
                    border-color: rgba(99, 102, 241, 0.25);
                }
                [data-theme="dark"] .cd-project-tag.tag-commercial {
                    background-color: rgba(245, 158, 11, 0.15);
                    color: #fbbf24;
                    border-color: rgba(245, 158, 11, 0.25);
                }
                [data-theme="dark"] .cd-project-tag.tag-free {
                    background-color: rgba(22, 163, 74, 0.15);
                    color: #4ade80;
                    border-color: rgba(22, 163, 74, 0.25);
                }
                [data-theme="dark"] .cd-project-tag.tag-new {
                    background-color: rgba(13, 110, 253, 0.15);
                    color: #60a5fa;
                    border-color: rgba(13, 110, 253, 0.25);
                }
            </style>

            @if($services->count())
                <div id="homeServicesScroller" aria-label="Services">
                    <div class="cd-hscroll-track">
                        @foreach($services as $service)
                            <div class="cd-hscroll-item">
                                <div class="card h-100 cd-service-card">
                                    @php($cardImage = $service->approach_image_path ?: ($service->screenshot_path ?: ($service->images->first()?->image_path)))
                                    
                                    <div class="cd-card-img-wrapper">
                                        @if($cardImage)
                                            <a href="{{ route('services.show', $service) }}">
                                                <img src="{{ asset('storage/'.$cardImage) }}" alt="{{ $service->title }}">
                                            </a>
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--cd-primary), var(--cd-heading)); opacity: 0.85;">
                                                <span class="text-white fw-bold opacity-50" style="font-size: 1.5rem;">Codediera</span>
                                            </div>
                                        @endif

                                        @if($service->is_free)
                                            <span class="cd-glass-badge bg-free-badge">Free</span>
                                        @elseif(!is_null($service->price))
                                            <span class="cd-glass-badge">₦{{ number_format((float)$service->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            @if($service->icon)
                                                <div class="cd-service-icon-box">
                                                    <span>{{ $service->icon }}</span>
                                                </div>
                                            @endif
                                            <h3 class="cd-service-title">
                                                <a class="text-decoration-none" href="{{ route('services.show', $service) }}">{{ $service->title }}</a>
                                            </h3>
                                        </div>

                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @php($paymentType = $service->payment_type ?: 'one_time')
                                            @if($paymentType === 'monthly')
                                                <span class="cd-badge-pill payment-recurring">Monthly</span>
                                            @elseif($paymentType === 'yearly')
                                                <span class="cd-badge-pill payment-recurring">Yearly</span>
                                            @elseif($paymentType === 'custom')
                                                <span class="cd-badge-pill payment-custom">Custom plan</span>
                                            @endif
                                            @if(in_array($paymentType, ['monthly', 'yearly'], true) && ($service->grace_trial_enabled ?? true))
                                                <span class="cd-badge-pill trial-badge">3-day trial</span>
                                            @endif
                                        </div>

                                        @if($service->description)
                                            <div class="cd-service-description">
                                                {{ strip_tags($service->description) }}
                                            </div>
                                        @endif

                                        <div class="cd-service-footer mt-auto d-flex justify-content-between align-items-center">
                                            <a class="text-decoration-none small fw-semibold" href="{{ route('services.show', $service) }}" style="color: var(--cd-primary);">Learn More &rarr;</a>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('services.show', $service) }}">View details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-muted">No services added yet.</div>
            @endif

            @push('modals')
                <div class="modal fade" id="serviceApplyModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post" action="{{ route('services.apply') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Request <span id="serviceApplyTitle">Service</span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-success d-none" id="serviceApplyFreeNotice">
                                        <div class="fw-semibold">This service is free</div>
                                        <div class="small">No payment is required. After submit, you will receive your Service ID for portal access.</div>
                                    </div>
                                    <div class="alert alert-info" id="serviceApplyPaymentNotice">
                                        <div class="fw-semibold">Payment checkout</div>
                                        <div class="small">After you submit this request, you will continue to checkout and receive your Service ID for portal access.</div>
                                    </div>
                                    <input type="hidden" name="service_id" id="serviceApplyServiceId" value="{{ old('service_id') }}">

                                    <div class="mb-3">
                                        <label class="form-label" for="serviceApplyName">Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" id="serviceApplyName" name="name" type="text" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="serviceApplyEmail">Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror" id="serviceApplyEmail" name="email" type="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-12 col-md-6" data-field="phone">
                                            <div class="mb-3">
                                                <label class="form-label" for="serviceApplyPhone">Phone</label>
                                                <input class="form-control @error('phone') is-invalid @enderror" id="serviceApplyPhone" name="phone" type="text" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6" data-field="company">
                                            <div class="mb-3">
                                                <label class="form-label" for="serviceApplyCompany">Company</label>
                                                <input class="form-control @error('company') is-invalid @enderror" id="serviceApplyCompany" name="company" type="text" value="{{ old('company') }}">
                                                @error('company')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3" data-field="budget">
                                        <label class="form-label" for="serviceApplyBudget">Budget (optional)</label>
                                        <input class="form-control @error('budget') is-invalid @enderror" id="serviceApplyBudget" name="budget" type="text" value="{{ old('budget') }}" placeholder="e.g. ₦200,000 - ₦500,000">
                                        @error('budget')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-0" data-field="message">
                                        <label class="form-label" for="serviceApplyMessage">Project details</label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="serviceApplyMessage" name="message" rows="4">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" id="serviceApplySubmitBtn" type="submit">Continue to checkout</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endpush

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var modalEl = document.getElementById('serviceApplyModal');
                    if (!modalEl || !window.bootstrap) return;

                    var titleEl = document.getElementById('serviceApplyTitle');
                    var serviceIdEl = document.getElementById('serviceApplyServiceId');
                    var fieldEls = modalEl.querySelectorAll('[data-field]');
                    var paymentNoticeEl = document.getElementById('serviceApplyPaymentNotice');
                    var freeNoticeEl = document.getElementById('serviceApplyFreeNotice');
                    var submitBtnEl = document.getElementById('serviceApplySubmitBtn');

                    modalEl.addEventListener('show.bs.modal', function (event) {
                        var trigger = event.relatedTarget;
                        if (!trigger) {
                            if (fieldEls && fieldEls.length) {
                                fieldEls.forEach(function (el) {
                                    el.classList.remove('d-none');
                                });
                            }
                            return;
                        }
                        var serviceId = trigger.getAttribute('data-service-id');
                        var serviceTitle = trigger.getAttribute('data-service-title');
                        var isFree = trigger.getAttribute('data-service-free') === '1';
                        var fieldsValue = trigger.getAttribute('data-service-fields') || '';
                        var enabled = fieldsValue.split(',').map(function (v) { return v.trim(); }).filter(function (v) { return v.length; });
                        if (serviceIdEl && serviceId) serviceIdEl.value = serviceId;
                        if (titleEl && serviceTitle) titleEl.textContent = serviceTitle;
                        if (paymentNoticeEl) paymentNoticeEl.classList.toggle('d-none', isFree);
                        if (freeNoticeEl) freeNoticeEl.classList.toggle('d-none', !isFree);
                        if (submitBtnEl) submitBtnEl.textContent = isFree ? 'Submit request' : 'Continue to checkout';
                        if (fieldEls && fieldEls.length) {
                            fieldEls.forEach(function (el) {
                                var key = el.getAttribute('data-field');
                                if (!key) return;
                                var show = enabled.indexOf(key) !== -1;
                                el.classList.toggle('d-none', !show);
                            });
                        }
                    });

                    @if($errors->any())
                        var modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    @endif
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var scroller = document.getElementById('homeServicesScroller');
                    if (!scroller) return;
                    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

                    var track = scroller.querySelector('.cd-hscroll-track');
                    if (!track) return;
                    var items = track.querySelectorAll('.cd-hscroll-item');
                    if (!items || items.length < 2) return;

                    var style = window.getComputedStyle(track);
                    var gap = parseFloat(style.columnGap || style.gap || '0') || 0;

                    function stepSize() {
                        var first = items[0];
                        if (!first) return 0;
                        var w = first.getBoundingClientRect().width;
                        return w + gap;
                    }

                    var paused = false;
                    var touchResumeId = null;

                    function nearEnd() {
                        return (scroller.scrollLeft + scroller.clientWidth) >= (scroller.scrollWidth - 2);
                    }

                    function tick() {
                        if (paused) return;
                        var step = stepSize();
                        if (!step) return;
                        if (nearEnd()) {
                            scroller.scrollTo({ left: 0, behavior: 'smooth' });
                            return;
                        }
                        scroller.scrollBy({ left: step, behavior: 'smooth' });
                    }

                    var intervalId = window.setInterval(tick, 3800);

                    scroller.addEventListener('mouseenter', function () { paused = true; });
                    scroller.addEventListener('mouseleave', function () { paused = false; });
                    scroller.addEventListener('focusin', function () { paused = true; });
                    scroller.addEventListener('focusout', function () { paused = false; });
                    scroller.addEventListener('touchstart', function () {
                        paused = true;
                        if (touchResumeId) window.clearTimeout(touchResumeId);
                    }, { passive: true });
                    scroller.addEventListener('touchend', function () {
                        if (touchResumeId) window.clearTimeout(touchResumeId);
                        touchResumeId = window.setTimeout(function () {
                            paused = false;
                        }, 3000);
                    }, { passive: true });

                    window.addEventListener('beforeunload', function () {
                        if (intervalId) window.clearInterval(intervalId);
                        if (touchResumeId) window.clearTimeout(touchResumeId);
                    });
                });
            </script>
        </section>

        <section class="mb-5">
            <div class="section-head">
                <div>
                    <div class="section-kicker">Projects</div>
                    <h2 class="h4 section-title mb-0">Recent Work</h2>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('projects') }}">View all</a>
            </div>
            <div class="row g-3">
                @forelse($projects as $index => $project)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 cd-project-card">
                            <div class="cd-project-img-wrapper">
                                @if($project->image_path)
                                    <img src="{{ asset('storage/'.$project->image_path) }}" alt="{{ $project->title }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--cd-heading), var(--cd-primary)); opacity: 0.85;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-laptop text-white opacity-40" viewBox="0 0 16 16">
                                            <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a.5.5 0 0 1 0 1H0a.5.5 0 0 1 0-1z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if(!is_null($project->cost) && $project->cost > 0)
                                    <span class="cd-glass-badge">₦{{ number_format((float)$project->cost, 2) }}</span>
                                @elseif(!is_null($project->cost) && (float)$project->cost === 0.0)
                                    <span class="cd-glass-badge bg-free-badge">Free</span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="cd-project-tag-container">
                                    @if($index < 2)
                                        <span class="cd-project-tag tag-new">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-lightning-charge-fill" viewBox="0 0 16 16">
                                                <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"/>
                                            </svg>
                                            Latest Release
                                        </span>
                                    @endif
                                    @if($project->url)
                                        <span class="cd-project-tag tag-live">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.424-1.148 1.02-1.43 1.704H7.5V1.077zM7.5 3.86h-2.148c-.07.313-.112.636-.125.968H7.5V3.86zm0 1.936H5.21c-.007.16-.01.32-.01.48s.003.32.01.48h2.28v-.96zM7.5 7.74H5.228c.013.332.054.655.125.968H7.5V7.74zm0 1.936H6.07c.282.685.76 1.28 1.43 1.704V9.676zM8.5 1.077v1.704h1.43c-.282-.685-.76-1.28-1.43-1.704zm1.43 2.783H8.5v.968h2.175c-.013-.332-.054-.655-.125-.968zm.745 1.936H8.5v.96h2.28c.007-.16.01-.32.01-.48s-.003-.32-.01-.48zM8.5 7.74v.968h1.43c.07-.313.112-.636.125-.968H8.5zm0 1.936v1.704c.67-.424 1.148-1.02 1.43-1.704H8.5zm-5.74-5.936H1.07A7.02 7.02 0 0 0 1 6.5c0 .385.03.762.09 1.127h1.67a8.107 8.107 0 0 1 0-2.254zm10.48 0c.056.365.09.742.09 1.127 0 .385-.03.762-.09 1.127h1.67a7.02 7.02 0 0 0 .09-1.127 7.02 7.02 0 0 0-.09-1.127h-1.67zM1.385 3.647A6.974 6.974 0 0 0 2.68 5.626h1.996a8.134 8.134 0 0 1-1.03-3.082L1.385 3.647zM3.647 1.385A6.974 6.974 0 0 0 5.626 2.68V1.077A7.042 7.042 0 0 0 3.647 1.385zm8.706 0a7.042 7.042 0 0 0-1.979-.308v1.603a6.974 6.974 0 0 0 1.295 1.295l.684-2.59zM12.353 3.647l.684 2.59h1.996c-.347-.75-.79-1.425-1.385-1.979A8.134 8.134 0 0 0 12.353 3.647zm.979 4.979a7.025 7.025 0 0 0-1.385 1.979l.684 2.59c.79-.554 1.295-1.229 1.385-1.979H13.33zm-1.03 3.082c.424-.67.76-1.345 1.03-2.079H11.27a8.134 8.134 0 0 1-1.03 2.079l1.996-.684zm-3.8 2.308c.75-.056 1.425-.347 1.979-.89l-1.295-1.295v2.185zm-3.082 0A7.042 7.042 0 0 0 7.5 14.923v-1.603a6.974 6.974 0 0 0-1.295-1.295l-.684 2.59zm-2.59-1.996l.684-2.59H2.68c.347.75.79 1.425 1.385 1.979zM1.385 12.353a7.025 7.025 0 0 0 1.385-1.979l-.684-2.59H1.07c.056.365.09.742.09 1.127a7.02 7.02 0 0 0-.09 1.127h.315z"/>
                                            </svg>
                                            Live Demo
                                        </span>
                                    @endif
                                    @if($project->zip_path)
                                        <span class="cd-project-tag tag-zip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-file-zip" viewBox="0 0 16 16">
                                                <path d="M6.5 7.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0z"/>
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 5h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zM5.5 7v-.5H6v.5h-.5zm.5 1h-.5v.5H6V8zm0 .5h.5V9H6v-.5z"/>
                                                <path d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                                            </svg>
                                            Source Code
                                        </span>
                                    @endif
                                    @if(!is_null($project->cost) && $project->cost > 0)
                                        <span class="cd-project-tag tag-commercial">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                                                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                                <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                                            </svg>
                                            Premium
                                        </span>
                                    @else
                                        <span class="cd-project-tag tag-free">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">
                                                <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068 1A1.5 1.5 0 0 0 3 2.5c0 .326.155.626.41.815C3.766 3.19 3.99 3.037 4.068 3.5zm2.41 0h1.042c-.078-.463-.302-.31-.658-.185a1.5 1.5 0 0 0-.384-.815zM9 2.5a1.5 1.5 0 0 0-1.068.815c.356-.125.58-.278.658.185h1.042A1.5 1.5 0 0 0 9 2.5zm2.932 1c.078-.463.302-.31.658-.185A1.5 1.5 0 0 0 13 2.5c0 .326-.155.626-.41.815-.356-.125-.58-.278-.658-.31zM15 7h-6v6h5.5a.5.5 0 0 0 .5-.5V7zm-7 6V7H2v5.5a.5.5 0 0 0 .5.5H8zM1 5h14V4H1v1z"/>
                                            </svg>
                                            Open Source
                                        </span>
                                    @endif
                                </div>

                                <h3 class="cd-project-title">{{ $project->title }}</h3>

                                @if($project->description)
                                    <div class="cd-project-description">
                                        {{ strip_tags($project->description) }}
                                    </div>
                                @endif

                                <div class="cd-project-footer mt-auto d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" 
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
                                        <a class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center gap-1.5" href="{{ $project->url }}" target="_blank" rel="noreferrer">
                                            <span>Live Demo</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M14 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h1.293L7.146 5.646a.5.5 0 0 0 .708.708L13 2.207V3.5a.5.5 0 0 0 1 0v-3z"/>
                                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7.5a.5.5 0 0 0-1 0V12a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5V2a.5.5 0 0 1 .5-.5h4.5a.5.5 0 0 0 0-1h-4.5z"/>
                                            </svg>
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
        </section>

        <section class="mb-5">
            <div class="section-head">
                <div>
                    <div class="section-kicker">Team</div>
                    <h2 class="h4 section-title mb-0">Meet the People</h2>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('team') }}">View all</a>
            </div>
            <div class="row g-3">
                @forelse($team as $member)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex gap-3">
                                    <div>
                                        @if($member->photo_path)
                                            <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:28px">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:28px">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="h6 mb-1">{{ $member->name }}</h3>
                                        @if($member->role)
                                            <div class="text-muted">{{ $member->role }}</div>
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
        </section>

        <section class="mb-5">
            <div class="section-head d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-kicker">Reviews</div>
                    <h2 class="h4 section-title mb-0">What Our Clients Say</h2>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                    Write a Review
                </button>
            </div>

            <style>
                #homeReviewsScroller {
                    overflow-x: auto;
                    overflow-y: visible;
                    scroll-snap-type: x mandatory;
                    -webkit-overflow-scrolling: touch;
                    padding-bottom: 6px;
                }
                #homeReviewsScroller .cd-hscroll-track {
                    display: flex;
                    gap: 1.25rem;
                    padding: 0.25rem 0.25rem 0.75rem;
                }
                #homeReviewsScroller .cd-hscroll-item {
                    flex: 0 0 auto;
                    width: min(380px, 86vw);
                    scroll-snap-align: start;
                }
                @media (max-width: 575.98px) {
                    #homeReviewsScroller .cd-hscroll-track {
                        gap: 0.75rem;
                        padding: 0.25rem 0.25rem 0.75rem;
                    }
                    #homeReviewsScroller .cd-hscroll-item {
                        width: calc(100% - 0.5rem);
                    }
                }
                .review-card {
                    background: var(--cd-surface-strong);
                    border: 1px solid var(--cd-border);
                    border-radius: 1.25rem;
                    padding: 1.5rem;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    box-shadow: var(--cd-shadow);
                }
                .review-card:hover {
                    transform: translateY(-4px);
                    box-shadow: var(--cd-card-shadow);
                }
                .review-avatar {
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-weight: 700;
                    font-size: 1.2rem;
                    flex-shrink: 0;
                }
                .star-rating .star {
                    color: #e4e5e9;
                    font-size: 1.1rem;
                }
                .star-rating .star.filled {
                    color: #ffc107;
                }
                
                /* Modal Star Rating CSS */
                .modal-star-rating {
                    display: inline-flex;
                    flex-direction: row-reverse;
                    justify-content: flex-end;
                    gap: 0.25rem;
                }
                .modal-star-rating input {
                    display: none;
                }
                .modal-star-rating label {
                    font-size: 2.25rem;
                    color: #e4e5e9;
                    cursor: pointer;
                    transition: color 0.15s ease-in-out;
                }
                .modal-star-rating label:hover,
                .modal-star-rating label:hover ~ label,
                .modal-star-rating input:checked ~ label {
                    color: #ffc107;
                }
            </style>

            @if($reviews->count() > 0 || (!empty($siteSettings?->google_places_api_key) && !empty($siteSettings?->google_place_id)))
                <div id="homeReviewsScroller" aria-label="Reviews">
                    <div class="cd-hscroll-track">
                        @foreach($reviews as $review)
                            <div class="cd-hscroll-item" data-review-id="{{ $review->id }}">
                                <div class="review-card h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="review-avatar" style="background: {{ $review->avatar_bg ?: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}">
                                                    {{ strtoupper(substr($review->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h4 class="h6 mb-0 fw-bold">{{ $review->name }}</h4>
                                                    <span class="text-muted small">{{ $review->reviewer_title ?: 'Google Reviewer' }}</span>
                                                </div>
                                            </div>
                                            <div class="google-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                                    <path fill="#4285F4" d="M46.5 24c0-1.61-.15-3.16-.42-4.69H24v8.89h12.66c-.55 2.85-2.17 5.27-4.59 6.88l7.14 5.53C43.34 36.42 46.5 30.82 46.5 24z"/>
                                                    <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z"/>
                                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.14-5.53c-1.97 1.32-4.5 2.11-8.75 2.11-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                                @endfor
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-0" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                            "{{ $review->review_text }}"
                                        </p>
                                    </div>
                                    @if($review->google_review_url)
                                        <div class="mt-3">
                                            <a href="{{ $review->google_review_url }}" target="_blank" rel="noopener noreferrer" class="small text-decoration-none d-inline-flex align-items-center gap-1">
                                                <span>View Google Review</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-muted py-3">No reviews submitted yet. Be the first to write a review!</div>
            @endif
        </section>

        @push('modals')
            <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0" style="border-radius: 1.25rem; background: var(--cd-surface);">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" id="writeReviewModalLabel">Write a Review</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="writeReviewForm" method="POST" action="{{ route('google-reviews.store') }}">
                            @csrf
                            <div class="modal-body">
                                <div id="reviewFormAlert" class="alert d-none"></div>

                                <div class="mb-3">
                                    <label for="reviewName" class="form-label fw-semibold">Your Name</label>
                                    <input type="text" class="form-control" id="reviewName" name="name" required placeholder="e.g. John Doe">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block fw-semibold">Rating</label>
                                    <div class="modal-star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="reviewText" class="form-label fw-semibold">Review Text</label>
                                    <textarea class="form-control" id="reviewText" name="review_text" rows="4" required placeholder="Share your experience working with us..."></textarea>
                                </div>

                                <div class="mb-0">
                                    <label for="reviewGoogleUrl" class="form-label fw-semibold">Google Review Link <span class="text-muted fw-normal">(optional)</span></label>
                                    <input type="url" class="form-control" id="reviewGoogleUrl" name="google_review_url" placeholder="https://g.page/r/...">
                                    <div class="form-text small text-muted">If you also posted this on Google, paste the link here.</div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary px-4" id="submitReviewBtn">
                                    <span class="spinner-border spinner-border-sm d-none me-1" id="submitReviewSpinner" role="status" aria-hidden="true"></span>
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endpush

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var scroller = document.getElementById('homeReviewsScroller');
                if (!scroller) return;
                if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

                var track = scroller.querySelector('.cd-hscroll-track');
                if (!track) return;

                var items = [];
                var intervalId = null;

                function updateScrollerItems() {
                    items = track.querySelectorAll('.cd-hscroll-item');
                    if (items.length >= 2 && !intervalId) {
                        intervalId = window.setInterval(tick, 4500);
                    } else if (items.length < 2 && intervalId) {
                        window.clearInterval(intervalId);
                        intervalId = null;
                    }
                }

                var style = window.getComputedStyle(track);

                function stepSize() {
                    var first = items[0];
                    if (!first) return 0;
                    var w = first.getBoundingClientRect().width;
                    var gap = parseFloat(style.columnGap || style.gap || '0') || 0;
                    return w + gap;
                }

                var paused = false;
                var touchResumeId = null;

                function nearEnd() {
                    return (scroller.scrollLeft + scroller.clientWidth) >= (scroller.scrollWidth - 12);
                }

                function tick() {
                    if (paused) return;
                    var step = stepSize();
                    if (!step) return;
                    if (nearEnd()) {
                        scroller.scrollTo({ left: 0, behavior: 'smooth' });
                        return;
                    }
                    scroller.scrollBy({ left: step, behavior: 'smooth' });
                }

                // Initial setup
                updateScrollerItems();

                // Expose a global callback to re-evaluate items when dynamic reviews are appended
                window.initReviewsAutoScroll = updateScrollerItems;

                scroller.addEventListener('mouseenter', function () { paused = true; });
                scroller.addEventListener('mouseleave', function () { paused = false; });
                scroller.addEventListener('focusin', function () { paused = true; });
                scroller.addEventListener('focusout', function () { paused = false; });
                scroller.addEventListener('touchstart', function () {
                    paused = true;
                    if (touchResumeId) window.clearTimeout(touchResumeId);
                }, { passive: true });
                scroller.addEventListener('touchend', function () {
                    if (touchResumeId) window.clearTimeout(touchResumeId);
                    touchResumeId = window.setTimeout(function () {
                        paused = false;
                    }, 3000);
                }, { passive: true });

                window.addEventListener('beforeunload', function () {
                    if (intervalId) window.clearInterval(intervalId);
                    if (touchResumeId) window.clearTimeout(touchResumeId);
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var form = document.getElementById('writeReviewForm');
                if (!form) return;

                var alertEl = document.getElementById('reviewFormAlert');
                var submitBtn = document.getElementById('submitReviewBtn');
                var spinner = document.getElementById('submitReviewSpinner');
                var scroller = document.getElementById('homeReviewsScroller');

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Clear errors
                    alertEl.className = 'alert d-none';
                    alertEl.textContent = '';

                    // Show loader
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    var formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            if (!response.ok) {
                                throw data;
                            }
                            return data;
                        });
                    })
                    .then(function (data) {
                        // Success!
                        alertEl.className = 'alert alert-success';
                        alertEl.textContent = data.message || 'Review submitted successfully!';

                        // Reset form
                        form.reset();

                        // Prepend/add to UI dynamically
                        if (data.review) {
                            var review = data.review;
                            var starsHtml = '';
                            for (var i = 1; i <= 5; i++) {
                                starsHtml += '<span class="star ' + (i <= review.rating ? 'filled' : '') + '">★</span>';
                            }

                            var linkHtml = '';
                            if (review.google_review_url) {
                                linkHtml = '<div class="mt-3">' +
                                    '<a href="' + review.google_review_url + '" target="_blank" rel="noopener noreferrer" class="small text-decoration-none d-inline-flex align-items-center gap-1">' +
                                    '<span>View Google Review</span>' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">' +
                                    '<path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>' +
                                    '<path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>' +
                                    '</svg>' +
                                    '</a>' +
                                    '</div>';
                            }

                            var nameInitial = review.name ? review.name.charAt(0).toUpperCase() : 'G';
                            var avatarBg = review.avatar_bg || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';

                            var cardHtml = 
                                '<div class="cd-hscroll-item" data-review-id="' + review.id + '">' +
                                '  <div class="review-card h-100 d-flex flex-column justify-content-between">' +
                                '    <div>' +
                                '      <div class="d-flex align-items-center justify-content-between mb-3">' +
                                '        <div class="d-flex align-items-center gap-3">' +
                                '          <div class="review-avatar" style="background: ' + avatarBg + '">' + nameInitial + '</div>' +
                                '          <div>' +
                                '            <h4 class="h6 mb-0 fw-bold">' + review.name + '</h4>' +
                                '            <span class="text-muted small">' + (review.reviewer_title || 'Google Reviewer') + '</span>' +
                                '          </div>' +
                                '        </div>' +
                                '        <div class="google-badge">' +
                                '          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">' +
                                '            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>' +
                                '            <path fill="#4285F4" d="M46.5 24c0-1.61-.15-3.16-.42-4.69H24v8.89h12.66c-.55 2.85-2.17 5.27-4.59 6.88l7.14 5.53C43.34 36.42 46.5 30.82 46.5 24z"/>' +
                                '            <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z"/>' +
                                '            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.14-5.53c-1.97 1.32-4.5 2.11-8.75 2.11-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>' +
                                '          </svg>' +
                                '        </div>' +
                                '      </div>' +
                                '      <div class="mb-2">' +
                                '        <span class="star-rating">' + starsHtml + '</span>' +
                                '      </div>' +
                                '      <p class="text-muted small mb-0" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">' +
                                '        "' + review.review_text + '"' +
                                '      </p>' +
                                '    </div>' +
                                    linkHtml +
                                '  </div>' +
                                '</div>';

                            if (scroller) {
                                var trackEl = scroller.querySelector('.cd-hscroll-track');
                                if (trackEl) {
                                    var tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = cardHtml;
                                    var newCard = tempDiv.firstChild;
                                    trackEl.insertBefore(newCard, trackEl.firstChild);
                                    scroller.scrollTo({ left: 0, behavior: 'smooth' });

                                    if (window.initReviewsAutoScroll) {
                                        window.initReviewsAutoScroll();
                                    }
                                }
                            } else {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1500);
                            }
                        }

                        setTimeout(function () {
                            var modal = bootstrap.Modal.getInstance(document.getElementById('writeReviewModal'));
                            if (modal) {
                                modal.hide();
                            }
                            alertEl.className = 'alert d-none';
                            alertEl.textContent = '';
                        }, 2000);
                    })
                    .catch(function (error) {
                        alertEl.className = 'alert alert-danger';
                        if (error && error.errors) {
                            var errorMessages = [];
                            for (var key in error.errors) {
                                if (error.errors.hasOwnProperty(key)) {
                                    errorMessages.push(error.errors[key].join(' '));
                                }
                            }
                            alertEl.textContent = errorMessages.join(' ');
                        } else {
                            alertEl.textContent = (error && error.message) || 'Something went wrong. Please try again.';
                        }
                    })
                    .finally(function () {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    });
                });
            });
        </script>

        @if(!empty($siteSettings?->google_places_api_key) && !empty($siteSettings?->google_place_id))
            <script>
                function initGoogleReviews() {
                    var scroller = document.getElementById('homeReviewsScroller');
                    if (!scroller) return;
                    var track = scroller.querySelector('.cd-hscroll-track');
                    if (!track) return;

                    var service = new google.maps.places.PlacesService(document.createElement('div'));

                    service.getDetails({
                        placeId: '{{ $siteSettings->google_place_id }}',
                        fields: ['reviews', 'url']
                    }, function(place, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK && place && place.reviews) {
                            place.reviews.forEach(function(review) {
                                var starsHtml = '';
                                for (var i = 1; i <= 5; i++) {
                                    starsHtml += '<span class="star ' + (i <= review.rating ? 'filled' : '') + '">★</span>';
                                }

                                var profilePhoto = review.profile_photo_url || '';
                                var avatarHtml = '';
                                if (profilePhoto) {
                                    avatarHtml = '<img src="' + profilePhoto + '" alt="' + review.author_name + '" class="review-avatar" style="object-fit:cover;width:48px;height:48px;border-radius:50%;">';
                                } else {
                                    var nameInitial = review.author_name ? review.author_name.charAt(0).toUpperCase() : 'G';
                                    var gradients = [
                                        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                        'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
                                        'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
                                        'linear-gradient(135deg, #ff8008 0%, #ffc837 100%)',
                                        'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                                        'linear-gradient(135deg, #fc00ff 0%, #00dbde 100%)',
                                        'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                                        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
                                    ];
                                    var avatarBg = gradients[Math.floor(Math.random() * gradients.length)];
                                    avatarHtml = '<div class="review-avatar" style="background: ' + avatarBg + '">' + nameInitial + '</div>';
                                }

                                var googleUrl = review.author_url || place.url || 'https://www.google.com/maps/place/?q=place_id:{{ $siteSettings->google_place_id }}';
                                var reviewTimeText = review.relative_time_description || 'Google Reviewer';

                                var cardHtml = 
                                    '<div class="cd-hscroll-item google-places-review">' +
                                    '  <div class="review-card h-100 d-flex flex-column justify-content-between">' +
                                    '    <div>' +
                                    '      <div class="d-flex align-items-center justify-content-between mb-3">' +
                                    '        <div class="d-flex align-items-center gap-3">' +
                                    '          ' + avatarHtml +
                                    '          <div>' +
                                    '            <h4 class="h6 mb-0 fw-bold">' + review.author_name + '</h4>' +
                                    '            <span class="text-muted small">' + reviewTimeText + '</span>' +
                                    '          </div>' +
                                    '        </div>' +
                                    '        <div class="google-badge">' +
                                    '          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">' +
                                    '            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>' +
                                    '            <path fill="#4285F4" d="M46.5 24c0-1.61-.15-3.16-.42-4.69H24v8.89h12.66c-.55 2.85-2.17 5.27-4.59 6.88l7.14 5.53C43.34 36.42 46.5 30.82 46.5 24z"/>' +
                                    '            <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z"/>' +
                                    '            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.14-5.53c-1.97 1.32-4.5 2.11-8.75 2.11-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>' +
                                    '          </svg>' +
                                    '        </div>' +
                                    '      </div>' +
                                    '      <div class="mb-2">' +
                                    '        <span class="star-rating">' + starsHtml + '</span>' +
                                    '      </div>' +
                                    '      <p class="text-muted small mb-0" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">' +
                                    '        "' + review.text + '"' +
                                    '      </p>' +
                                    '    </div>' +
                                    '    <div class="mt-3">' +
                                    '      <a href="' + googleUrl + '" target="_blank" rel="noopener noreferrer" class="small text-decoration-none d-inline-flex align-items-center gap-1">' +
                                    '        <span>View Google Review</span>' +
                                    '        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">' +
                                    '          <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>' +
                                    '          <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>' +
                                    '        </svg>' +
                                    '      </a>' +
                                    '    </div>' +
                                    '  </div>' +
                                    '</div>';

                                var tempDiv = document.createElement('div');
                                tempDiv.innerHTML = cardHtml;
                                var newCard = tempDiv.firstChild;
                                track.appendChild(newCard);
                            });

                            if (window.initReviewsAutoScroll) {
                                window.initReviewsAutoScroll();
                            }
                        }
                    });
                }
            </script>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ urlencode($siteSettings->google_places_api_key) }}&libraries=places&callback=initGoogleReviews"></script>
        @endif

        <section class="mb-4">
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="section-kicker mb-1">Contact</div>
                        <h2 class="h5 mb-1">{{ $contactSettings?->heading ?? 'Contact Us' }}</h2>
                        <div class="text-muted">Questions or ideas? Let’s talk.</div>
                    </div>
                    <a class="btn btn-primary" href="{{ route('contact') }}">Contact Us</a>
                </div>
            </div>
        </section>
    </div>
@endsection
