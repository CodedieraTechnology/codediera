@extends('layouts.public')

@section('title', 'About Us')

@section('content')
    <div class="container py-4">
        <!-- Page Header -->
        <div class="page-head">
            <div class="icon-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
            </div>
            <div>
                <div class="section-kicker">Who We Are</div>
                <h1 class="h3 section-title">About Us</h1>
                <div class="page-subtitle">Learn more about Codediera Technologies LTD, our vision, mission, and core values.</div>
            </div>
        </div>

        <!-- Main Intro Row -->
        <div class="row g-4 mb-4">
            <!-- Welcome Copy Card -->
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3 d-flex align-items-center gap-2">
                            <span class="text-primary">Welcome to</span> Codediera Technologies LTD
                        </h2>
                        
                        <p class="lead" style="font-size: 1.1rem; line-height: 1.7; color: var(--cd-text);">
                            Codediera Technologies LTD is a fast-growing Nigerian technology company established on <strong>20 March 2024</strong> with a mission to provide innovative digital solutions, software development services, and quality tech education for individuals, startups, businesses, and organizations.
                        </p>
                        
                        <p class="mt-3" style="line-height: 1.7; color: var(--cd-muted);">
                            We are passionate about technology, creativity, and digital transformation. Our goal is to empower people with modern technological skills while helping businesses grow through reliable and scalable digital solutions.
                        </p>
                        
                        <p class="mt-3" style="line-height: 1.7; color: var(--cd-muted);">
                            At Codediera Technologies LTD, we specialize in web development, software engineering, digital training, IT consultancy, and online learning systems. We provide practical and industry-focused training in technologies such as PHP, Laravel, JavaScript, Bootstrap, MySQL, Python, mobile app development, UI/UX design, and digital marketing.
                        </p>
                        
                        <p class="mt-3 mb-0" style="line-height: 1.7; color: var(--cd-muted);">
                            Our team consists of experienced and dedicated professionals who are committed to delivering excellence. Whether you want to learn a digital skill or need a reliable technology partner for your project, Codediera Technologies LTD is here to help you achieve your goals.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Sidebar / Quick Facts -->
            <div class="col-12 col-lg-4">
                <div class="card h-100" style="background: var(--cd-surface-strong); border: 1px solid var(--cd-border);">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 mb-3 text-uppercase tracking-wider small fw-bold text-muted">Company Details</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-3 mb-3">
                                    <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1z"/>
                                            <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h12z"/>
                                            <path d="M1.5 5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1zM1.5 8h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1zM1.5 11h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Registration Status</div>
                                        <span class="badge bg-success-subtle text-success">Active & Registered</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start gap-3 mb-3">
                                    <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">RC Number</div>
                                        <div class="text-muted font-monospace">RC 7457850</div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start gap-3 mb-3">
                                    <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 7a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a5.002 5.002 0 0 0-4.999 5h9.999A5.002 5.002 0 0 0 8 8z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Founded</div>
                                        <div class="text-muted">20 March 2024</div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start gap-3">
                                    <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Location</div>
                                        <div class="text-muted small">Opposite Owerri Municipal Council, IMSU junction, Owerri, Imo State, Nigeria.</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4 pt-3 border-top border-secondary-subtle">
                            <a href="{{ route('contact') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114V5.383zM14.247 12l-5.53-3.316L8 9.083l-.717-.399L1.753 12H14.247zM1 11.114l4.758-2.876L1 5.383v5.731z"/>
                                </svg>
                                Get In Touch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vision & Mission Rows -->
        <div class="row g-4 mb-4">
            <!-- Vision -->
            <div class="col-12 col-md-6">
                <div class="card h-100 card-body p-4" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.04) 0%, rgba(255, 255, 255, 0) 100%);">
                    <div class="d-flex align-items-start gap-3">
                        <span class="icon-badge text-primary" style="width:48px; height:48px; border-radius: 1rem; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="h4 mb-2">Our Vision</h2>
                            <p class="mb-0" style="line-height: 1.6; color: var(--cd-text);">
                                To be a leading technology hub in Africa, driving digital innovation, empowerment, and transformation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission -->
            <div class="col-12 col-md-6">
                <div class="card h-100 card-body p-4" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.04) 0%, rgba(255, 255, 255, 0) 100%);">
                    <div class="d-flex align-items-start gap-3">
                        <span class="icon-badge text-primary" style="width:48px; height:48px; border-radius: 1rem; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15.825.12a.5.5 0 0 0-.137-.136L12 .975 9.81.33a.5.5 0 0 0-.278 0L7.333 1.055 5.5 0h-.033a.5.5 0 0 0-.324.168L.103 5.215A.5.5 0 0 0 0 5.565V15.5a.5.5 0 0 0 .5.5h15a.5.5 0 0 0 .5-.5V.5a.5.5 0 0 0-.175-.38zM15 1.702v12.798H1V5.702l3.874-3.874.156.085a.5.5 0 0 0 .324.032l2.36-.786 2.012.604a.5.5 0 0 0 .278-.033L15 1.702z"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="h4 mb-2">Our Mission</h2>
                            <p class="mb-0" style="line-height: 1.6; color: var(--cd-text);">
                                To deliver state-of-the-art digital solutions and provide accessible, practical tech education that equips individuals and businesses for the global digital economy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-4 text-center">Why Choose Us?</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                🎓
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Expert Instructors & Developers</h3>
                                <div class="text-muted small">Learn from and work with active industry professionals who bring real-world experience.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                💻
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Practical & Hands-on Training</h3>
                                <div class="text-muted small">Our courses are fully practical, project-based, and aligned with standard company tech stacks.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                🚀
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Reliable & Scalable Solutions</h3>
                                <div class="text-muted small">We build software and applications that scale efficiently and grow seamlessly with your business.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                🤝
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Career Support & Mentorship</h3>
                                <div class="text-muted small">We guide you every step of the way, helping you transition from learning to earning in tech.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                🏛️
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Official Recognition</h3>
                                <div class="text-muted small">We are a registered corporate entity in Nigeria (RC 7457850) with a physical tech hub location.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-badge text-primary" style="width:36px; height:36px; flex-shrink: 0; background-color: rgba(13, 110, 253, 0.08);">
                                🛡️
                            </span>
                            <div>
                                <h3 class="h6 mb-1">Premium Customer Support</h3>
                                <div class="text-muted small">Get dedicated assistance and consultation for any business requirements or technical queries.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration & Address Callout -->
        <div class="card border border-primary-subtle" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.02) 0%, rgba(255, 255, 255, 0) 100%);">
            <div class="card-body p-4 text-center">
                <h2 class="h4 mb-3">Official Registration</h2>
                <p class="mb-3 mx-auto" style="max-width: 600px; color: var(--cd-text);">
                    Codediera Technologies LTD is officially registered with the <strong>Corporate Affairs Commission (CAC)</strong> of Nigeria (RC Number: <strong>7457850</strong>).
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <span class="badge bg-primary text-white py-2 px-3">RC 7457850</span>
                    <span class="badge bg-secondary text-white py-2 px-3">Established 20 March 2024</span>
                </div>
            </div>
        </div>
    </div>
@endsection
