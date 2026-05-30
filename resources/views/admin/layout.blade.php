<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('cd_theme');
                var theme = stored ? stored : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cd-admin-sidebar-width: 284px;
            --cd-admin-topbar-height: 64px;
            --cd-bg: #f6f7fb;
            --cd-surface: rgba(255, 255, 255, 0.96);
            --cd-surface-strong: rgba(255, 255, 255, 0.9);
            --cd-text: rgba(15, 23, 42, 0.92);
            --cd-muted: rgba(15, 23, 42, 0.65);
            --cd-muted-2: rgba(15, 23, 42, 0.55);
            --cd-border: rgba(15, 23, 42, 0.08);
            --cd-hover: rgba(15, 23, 42, 0.06);
            --cd-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --cd-card-shadow: 0 12px 32px rgba(15, 23, 42, 0.14);
            --cd-loader-bg: rgba(246, 247, 251, 0.82);
            --cd-spinner-border: rgba(15, 23, 42, 0.12);
            --bs-body-bg: var(--cd-bg);
            --bs-body-color: var(--cd-text);
            --bs-secondary-color: var(--cd-muted);
            --bs-tertiary-color: var(--cd-muted-2);
            --bs-border-color: var(--cd-border);
            --bs-card-bg: var(--cd-surface-strong);
            --bs-link-color: #0d6efd;
            --bs-link-hover-color: #0d6efd;
            color-scheme: light;
        }
        [data-theme="dark"] {
            --cd-bg: #0b1220;
            --cd-surface: rgba(17, 24, 39, 0.86);
            --cd-surface-strong: rgba(17, 24, 39, 0.92);
            --cd-text: #ffffff;
            --cd-muted: rgba(255, 255, 255, 0.72);
            --cd-muted-2: rgba(255, 255, 255, 0.6);
            --cd-border: rgba(255, 255, 255, 0.12);
            --cd-hover: rgba(255, 255, 255, 0.08);
            --cd-shadow: 0 12px 36px rgba(0, 0, 0, 0.45);
            --cd-card-shadow: 0 16px 44px rgba(0, 0, 0, 0.45);
            --cd-loader-bg: rgba(11, 18, 32, 0.78);
            --cd-spinner-border: rgba(255, 255, 255, 0.18);
            --bs-body-bg: var(--cd-bg);
            --bs-body-color: var(--cd-text);
            --bs-secondary-color: var(--cd-muted);
            --bs-tertiary-color: var(--cd-muted-2);
            --bs-border-color: var(--cd-border);
            --bs-card-bg: var(--cd-surface-strong);
            color-scheme: dark;
        }
        body {
            background: var(--cd-bg);
            color: var(--cd-text);
        }
        @if($siteSettings?->logo_path)
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('storage/'.$siteSettings->logo_path) }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: min(70vmin, 520px) auto;
            opacity: 0.05;
            filter: grayscale(1);
            pointer-events: none;
            z-index: 0;
        }
        @endif
        .cd-admin-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
        }
        .cd-admin-topbar {
            height: var(--cd-admin-topbar-height);
        }
        .cd-admin-sidebar {
            width: var(--cd-admin-sidebar-width);
            min-width: var(--cd-admin-sidebar-width);
            background: var(--cd-surface);
            border-right: 1px solid var(--cd-border);
            backdrop-filter: blur(10px);
        }
        .cd-admin-sidebar-inner {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .cd-admin-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: var(--cd-text);
        }
        .cd-admin-brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
            flex: 0 0 auto;
        }
        .cd-admin-brand-title {
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }
        .cd-admin-brand-subtitle {
            font-size: 0.8rem;
            color: var(--cd-muted);
        }
        .cd-admin-nav-section {
            margin-top: 0.25rem;
        }
        .cd-admin-nav-label {
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--cd-muted-2);
            margin: 0.75rem 0 0.35rem;
            padding-left: 0.4rem;
        }
        .cd-admin-nav-link,
        .cd-admin-nav-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 0.75rem;
            border-radius: 1rem;
            text-decoration: none;
            color: var(--cd-text);
        }
        .cd-admin-nav-link:hover,
        .cd-admin-nav-toggle:hover {
            background: var(--cd-hover);
            color: var(--cd-text);
        }
        .cd-admin-nav-link.active {
            background: rgba(13, 110, 253, 0.12);
            color: #0b5ed7;
            font-weight: 700;
        }
        .cd-admin-nav-icon {
            width: 18px;
            height: 18px;
            opacity: 0.9;
            flex: 0 0 auto;
        }
        .cd-admin-nav-caret {
            margin-left: auto;
            opacity: 0.7;
        }
        .cd-admin-subnav {
            padding-left: 0.25rem;
            margin-top: 0.35rem;
        }
        .cd-admin-subnav .cd-admin-nav-link {
            padding-left: 2.15rem;
        }
        .cd-admin-content {
            flex: 1 1 auto;
            min-width: 0;
        }
        .cd-admin-main {
            position: relative;
            z-index: 1;
            padding: 1.25rem;
        }
        @media (max-width: 991.98px) {
            .cd-admin-wrap {
                display: block;
                min-height: auto;
            }
            .cd-admin-main {
                padding-top: calc(var(--cd-admin-topbar-height) + 1rem);
            }
        }
        .card, .btn, .form-control, .form-select, .alert, .dropdown-menu {
            border-radius: 1rem;
        }
        .card {
            border: 0;
            box-shadow: var(--cd-card-shadow);
        }
        .btn {
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .global-loader {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: var(--cd-loader-bg);
            backdrop-filter: blur(10px);
            z-index: 2000;
        }
        .modal-backdrop {
            z-index: 2090;
        }
        .modal {
            z-index: 2100;
        }
        .global-loader.is-active {
            display: flex;
        }
        .global-loader-card {
            width: min(520px, 100%);
        }
        .global-loader-spinner {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 4px solid var(--cd-spinner-border);
            border-top-color: #0d6efd;
            animation: cd-spin 0.9s linear infinite;
        }
        .cd-theme-toggle {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .cd-theme-toggle svg {
            width: 16px;
            height: 16px;
        }
        .cd-theme-toggle .cd-theme-icon {
            display: none;
        }
        [data-theme="light"] .cd-theme-toggle .cd-theme-icon-moon {
            display: block;
        }
        [data-theme="dark"] .cd-theme-toggle .cd-theme-icon-sun {
            display: block;
        }
        [data-theme="dark"] .text-dark {
            color: var(--bs-body-color) !important;
        }
        @keyframes cd-spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
@php
    $adminContentOpen = request()->routeIs('admin.sliders.*')
        || request()->routeIs('admin.services.*')
        || request()->routeIs('admin.projects.*')
        || request()->routeIs('admin.team.*')
        || request()->routeIs('admin.ctas.*')
        || request()->routeIs('admin.digital-skills.*')
        || request()->routeIs('admin.google-reviews.*');
    $adminOperationsOpen = request()->routeIs('admin.jobs.*')
        || request()->routeIs('admin.job-applications.*')
        || request()->routeIs('admin.it-intakes.*')
        || request()->routeIs('admin.contact-messages.*')
        || request()->routeIs('admin.service-subscriptions.*');
    $adminSettingsOpen = request()->routeIs('admin.site-settings.*')
        || request()->routeIs('admin.contact-settings.*')
        || request()->routeIs('admin.mail-settings.*')
        || request()->routeIs('admin.payment-settings.*')
        || request()->routeIs('admin.ai-settings.*');
@endphp

<nav class="navbar navbar-dark bg-dark fixed-top shadow-sm d-lg-none cd-admin-topbar">
    <div class="container-fluid">
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">Admin</a>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-light btn-sm cd-theme-toggle" type="button" data-cd-theme-toggle aria-label="Toggle theme">
                <svg class="cd-theme-icon cd-theme-icon-sun" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                    <path d="M8 0a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0zm0 14a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V14.5A.5.5 0 0 1 8 14zm8-6a.5.5 0 0 1-.5.5h-1.25a.5.5 0 0 1 0-1H15.5A.5.5 0 0 1 16 8zM2 8a.5.5 0 0 1-.5.5H.25a.5.5 0 0 1 0-1H1.5A.5.5 0 0 1 2 8zm11.657-5.657a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM4.641 11.359a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM13.657 13.657a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707zM4.641 4.641a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707z"/>
                </svg>
                <svg class="cd-theme-icon cd-theme-icon-moon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M6 .278a.5.5 0 0 1 .587.81A6.5 6.5 0 1 0 14.912 9.41a.5.5 0 0 1 .81.587A7.5 7.5 0 1 1 6 .278z"/>
                </svg>
            </button>
            <form method="post" action="{{ route('admin.logout') }}" class="d-flex">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarMobileLabel">Admin Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-0">
        <div class="cd-admin-nav-section">
            <div class="cd-admin-nav-label">Overview</div>
            <a class="cd-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 3.293 1 10.293V15h4v-4h6v4h4v-4.707L8 3.293z"/>
                    <path d="M7.293 1.5a1 1 0 0 1 1.414 0l6.5 6.5a1 1 0 0 1-1.414 1.414L8 3.621 2.207 9.414A1 1 0 0 1 .793 8L7.293 1.5z"/>
                </svg>
                Dashboard
            </a>
        </div>

        <div class="cd-admin-nav-section">
            <div class="cd-admin-nav-label">Website</div>
            <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminContentOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminMobileContent" aria-expanded="{{ $adminContentOpen ? 'true' : 'false' }}" aria-controls="adminMobileContent">
                <span class="cd-admin-nav-link w-100">
                    <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-4A.5.5 0 0 1 0 5.5v-4zM1 2v3h3V2H1zm6-.5A.5.5 0 0 1 7.5 1h8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 7 5.5v-4zM8 2v3h7V2H8z"/>
                        <path d="M1 9.5A.5.5 0 0 1 1.5 9h13a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-13A.5.5 0 0 1 1 14.5v-5zM2 10v4h12v-4H2z"/>
                    </svg>
                    Content
                    <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                    </svg>
                </span>
            </button>
            <div class="collapse {{ $adminContentOpen ? 'show' : '' }}" id="adminMobileContent">
                <div class="cd-admin-subnav">
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">Slider</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">Services</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Projects</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}" href="{{ route('admin.team.index') }}">Team</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.ctas.*') ? 'active' : '' }}" href="{{ route('admin.ctas.index') }}">Home CTAs</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.digital-skills.*') ? 'active' : '' }}" href="{{ route('admin.digital-skills.index') }}">Digital Skills</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.digital-skill-enrollments.*') ? 'active' : '' }}" href="{{ route('admin.digital-skill-enrollments.index') }}">Skill Enrollments</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.google-reviews.*') ? 'active' : '' }}" href="{{ route('admin.google-reviews.index') }}">Google Reviews</a>
                </div>
            </div>

            <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminSettingsOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminMobileSettings" aria-expanded="{{ $adminSettingsOpen ? 'true' : 'false' }}" aria-controls="adminMobileSettings">
                <span class="cd-admin-nav-link w-100">
                    <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M9.667.864a1 1 0 0 0-1.334 0l-.4.356a1 1 0 0 1-1.228.095l-.5-.284a1 1 0 0 0-1.366.366l-.3.52a1 1 0 0 1-1.135.47l-.58-.156a1 1 0 0 0-1.22.707l-.148.58a1 1 0 0 1-.84.74l-.59.078a1 1 0 0 0-.863 1.012l.06.595a1 1 0 0 1-.41 1.01l-.49.34a1 1 0 0 0-.267 1.387l.284.5a1 1 0 0 1-.095 1.228l-.356.4a1 1 0 0 0 0 1.334l.356.4a1 1 0 0 1 .095 1.228l-.284.5a1 1 0 0 0 .267 1.387l.49.34a1 1 0 0 1 .41 1.01l-.06.595a1 1 0 0 0 .863 1.012l.59.078a1 1 0 0 1 .84.74l.148.58a1 1 0 0 0 1.22.707l.58-.156a1 1 0 0 1 1.135.47l.3.52a1 1 0 0 0 1.366.366l.5-.284a1 1 0 0 1 1.228.095l.4.356a1 1 0 0 0 1.334 0l.4-.356a1 1 0 0 1 1.228-.095l.5.284a1 1 0 0 0 1.366-.366l.3-.52a1 1 0 0 1 1.135-.47l.58.156a1 1 0 0 0 1.22-.707l.148-.58a1 1 0 0 1 .84-.74l.59-.078a1 1 0 0 0 .863-1.012l-.06-.595a1 1 0 0 1 .41-1.01l.49-.34a1 1 0 0 0 .267-1.387l-.284-.5a1 1 0 0 1 .095-1.228l.356-.4a1 1 0 0 0 0-1.334l-.356-.4a1 1 0 0 1-.095-1.228l.284-.5a1 1 0 0 0-.267-1.387l-.49-.34a1 1 0 0 1-.41-1.01l.06-.595a1 1 0 0 0-.863-1.012l-.59-.078a1 1 0 0 1-.84-.74l-.148-.58a1 1 0 0 0-1.22-.707l-.58.156a1 1 0 0 1-1.135-.47l-.3-.52a1 1 0 0 0-1.366-.366l-.5.284a1 1 0 0 1-1.228-.095l-.4-.356z"/>
                        <path d="M8 10.5A2.5 2.5 0 1 1 8 5.5a2.5 2.5 0 0 1 0 5z"/>
                    </svg>
                    Settings
                    <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                    </svg>
                </span>
            </button>
            <div class="collapse {{ $adminSettingsOpen ? 'show' : '' }}" id="adminMobileSettings">
                <div class="cd-admin-subnav">
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}" href="{{ route('admin.site-settings.edit') }}">Site Settings</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.contact-settings.*') ? 'active' : '' }}" href="{{ route('admin.contact-settings.edit') }}">Contact Settings</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.mail-settings.*') ? 'active' : '' }}" href="{{ route('admin.mail-settings.edit') }}">Email Settings</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}" href="{{ route('admin.payment-settings.edit') }}">Payment Settings</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.ai-settings.*') ? 'active' : '' }}" href="{{ route('admin.ai-settings.index') }}">AI Settings</a>
                </div>
            </div>
        </div>

        <div class="cd-admin-nav-section">
            <div class="cd-admin-nav-label">Operations</div>
            <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminOperationsOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminMobileOperations" aria-expanded="{{ $adminOperationsOpen ? 'true' : 'false' }}" aria-controls="adminMobileOperations">
                <span class="cd-admin-nav-link w-100">
                    <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M6 0a2 2 0 0 0-2 2v1H2.5A1.5 1.5 0 0 0 1 4.5v9A2.5 2.5 0 0 0 3.5 16h9A2.5 2.5 0 0 0 15 13.5v-9A1.5 1.5 0 0 0 13.5 3H12V2a2 2 0 0 0-2-2H6zm5 3V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v1h6z"/>
                        <path d="M2 6h12v7.5A1.5 1.5 0 0 1 12.5 15h-9A1.5 1.5 0 0 1 2 13.5V6z"/>
                    </svg>
                    Manage
                    <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                    </svg>
                </span>
            </button>
            <div class="collapse {{ $adminOperationsOpen ? 'show' : '' }}" id="adminMobileOperations">
                <div class="cd-admin-subnav">
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">Jobs</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}" href="{{ route('admin.job-applications.index') }}">Job Applications</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.service-subscriptions.*') ? 'active' : '' }}" href="{{ route('admin.service-subscriptions.index') }}">Service Subscriptions</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.it-intakes.*') ? 'active' : '' }}" href="{{ route('admin.it-intakes.index') }}">IT Intake</a>
                    <a class="cd-admin-nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}" href="{{ route('admin.contact-messages.index') }}">Contact Messages</a>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-outline-dark w-100" type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>

<div class="cd-admin-wrap">
    <aside class="cd-admin-sidebar d-none d-lg-block">
        <div class="cd-admin-sidebar-inner">
            <a class="cd-admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="cd-admin-brand-mark" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.65-1.2 1.52-1.2 2.46 0 1.616 1.33 2.933 2.95 3.222l.24.04c.37.06.64.37.64.74 0 .41-.33.74-.74.74H5.5a.5.5 0 0 0 0 1h.75a.75.75 0 0 1 0 1.5H5.5a.5.5 0 0 0 0 1h.75c.41 0 .74.33.74.74 0 .37-.27.68-.64.74l-.24.04C4.49 13.067 3.16 14.384 3.16 16h9.68c0-1.616-1.33-2.933-2.95-3.222l-.24-.04a.75.75 0 0 1 .1-1.493H10.5a.5.5 0 0 0 0-1h-.75a.75.75 0 0 1 0-1.5h.75a.5.5 0 0 0 0-1h-.75a.75.75 0 0 1 0-1.5h.75c.41 0 .74-.33.74-.74 0-.37-.27-.68-.64-.74l-.24-.04C8.74 6.733 7.41 5.416 7.41 3.8c0-.94-.434-1.81-1.2-2.46A5.53 5.53 0 0 0 8 0z"/>
                    </svg>
                </span>
                <span>
                    <div class="cd-admin-brand-title">Codediera</div>
                    <div class="cd-admin-brand-subtitle">Admin Panel</div>
                </span>
            </a>
            <button class="btn btn-outline-secondary btn-sm cd-theme-toggle" type="button" data-cd-theme-toggle aria-label="Toggle theme">
                <svg class="cd-theme-icon cd-theme-icon-sun" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                    <path d="M8 0a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0zm0 14a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V14.5A.5.5 0 0 1 8 14zm8-6a.5.5 0 0 1-.5.5h-1.25a.5.5 0 0 1 0-1H15.5A.5.5 0 0 1 16 8zM2 8a.5.5 0 0 1-.5.5H.25a.5.5 0 0 1 0-1H1.5A.5.5 0 0 1 2 8zm11.657-5.657a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM4.641 11.359a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM13.657 13.657a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707zM4.641 4.641a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707z"/>
                </svg>
                <svg class="cd-theme-icon cd-theme-icon-moon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M6 .278a.5.5 0 0 1 .587.81A6.5 6.5 0 1 0 14.912 9.41a.5.5 0 0 1 .81.587A7.5 7.5 0 1 1 6 .278z"/>
                </svg>
            </button>

            <div class="cd-admin-nav-section">
                <div class="cd-admin-nav-label">Overview</div>
                <a class="cd-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8 3.293 1 10.293V15h4v-4h6v4h4v-4.707L8 3.293z"/>
                        <path d="M7.293 1.5a1 1 0 0 1 1.414 0l6.5 6.5a1 1 0 0 1-1.414 1.414L8 3.621 2.207 9.414A1 1 0 0 1 .793 8L7.293 1.5z"/>
                    </svg>
                    Dashboard
                </a>
            </div>

            <div class="cd-admin-nav-section">
                <div class="cd-admin-nav-label">Website</div>

                <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminContentOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminDesktopContent" aria-expanded="{{ $adminContentOpen ? 'true' : 'false' }}" aria-controls="adminDesktopContent">
                    <span class="cd-admin-nav-link w-100">
                        <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-4A.5.5 0 0 1 0 5.5v-4zM1 2v3h3V2H1zm6-.5A.5.5 0 0 1 7.5 1h8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 7 5.5v-4zM8 2v3h7V2H8z"/>
                            <path d="M1 9.5A.5.5 0 0 1 1.5 9h13a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-13A.5.5 0 0 1 1 14.5v-5zM2 10v4h12v-4H2z"/>
                        </svg>
                        Content
                        <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                        </svg>
                    </span>
                </button>
                <div class="collapse {{ $adminContentOpen ? 'show' : '' }}" id="adminDesktopContent">
                    <div class="cd-admin-subnav">
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">Slider</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">Services</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Projects</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}" href="{{ route('admin.team.index') }}">Team</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.ctas.*') ? 'active' : '' }}" href="{{ route('admin.ctas.index') }}">Home CTAs</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.digital-skills.*') ? 'active' : '' }}" href="{{ route('admin.digital-skills.index') }}">Digital Skills</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.digital-skill-enrollments.*') ? 'active' : '' }}" href="{{ route('admin.digital-skill-enrollments.index') }}">Skill Enrollments</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.google-reviews.*') ? 'active' : '' }}" href="{{ route('admin.google-reviews.index') }}">Google Reviews</a>
                    </div>
                </div>

                <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminSettingsOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminDesktopSettings" aria-expanded="{{ $adminSettingsOpen ? 'true' : 'false' }}" aria-controls="adminDesktopSettings">
                    <span class="cd-admin-nav-link w-100">
                        <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M9.667.864a1 1 0 0 0-1.334 0l-.4.356a1 1 0 0 1-1.228.095l-.5-.284a1 1 0 0 0-1.366.366l-.3.52a1 1 0 0 1-1.135.47l-.58-.156a1 1 0 0 0-1.22.707l-.148.58a1 1 0 0 1-.84.74l-.59.078a1 1 0 0 0-.863 1.012l.06.595a1 1 0 0 1-.41 1.01l-.49.34a1 1 0 0 0-.267 1.387l.284.5a1 1 0 0 1-.095 1.228l-.356.4a1 1 0 0 0 0 1.334l.356.4a1 1 0 0 1 .095 1.228l-.284.5a1 1 0 0 0 .267 1.387l.49.34a1 1 0 0 1 .41 1.01l-.06.595a1 1 0 0 0 .863 1.012l.59.078a1 1 0 0 1 .84.74l.148.58a1 1 0 0 0 1.22.707l.58-.156a1 1 0 0 1 1.135.47l.3.52a1 1 0 0 0 1.366.366l.5-.284a1 1 0 0 1 1.228.095l.4.356a1 1 0 0 0 1.334 0l.4-.356a1 1 0 0 1 1.228-.095l.5.284a1 1 0 0 0 1.366-.366l.3-.52a1 1 0 0 1 1.135-.47l.58.156a1 1 0 0 0 1.22-.707l.148-.58a1 1 0 0 1 .84-.74l.59-.078a1 1 0 0 0 .863-1.012l-.06-.595a1 1 0 0 1 .41-1.01l.49-.34a1 1 0 0 0 .267-1.387l-.284-.5a1 1 0 0 1 .095-1.228l.356-.4a1 1 0 0 0 0-1.334l-.356-.4a1 1 0 0 1-.095-1.228l.284-.5a1 1 0 0 0-.267-1.387l-.49-.34a1 1 0 0 1-.41-1.01l.06-.595a1 1 0 0 0-.863-1.012l-.59-.078a1 1 0 0 1-.84-.74l-.148-.58a1 1 0 0 0-1.22-.707l-.58.156a1 1 0 0 1-1.135-.47l-.3-.52a1 1 0 0 0-1.366-.366l-.5.284a1 1 0 0 1-1.228-.095l-.4-.356z"/>
                            <path d="M8 10.5A2.5 2.5 0 1 1 8 5.5a2.5 2.5 0 0 1 0 5z"/>
                        </svg>
                        Settings
                        <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                        </svg>
                    </span>
                </button>
                <div class="collapse {{ $adminSettingsOpen ? 'show' : '' }}" id="adminDesktopSettings">
                    <div class="cd-admin-subnav">
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}" href="{{ route('admin.site-settings.edit') }}">Site Settings</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.contact-settings.*') ? 'active' : '' }}" href="{{ route('admin.contact-settings.edit') }}">Contact Settings</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.mail-settings.*') ? 'active' : '' }}" href="{{ route('admin.mail-settings.edit') }}">Email Settings</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}" href="{{ route('admin.payment-settings.edit') }}">Payment Settings</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.ai-settings.*') ? 'active' : '' }}" href="{{ route('admin.ai-settings.index') }}">AI Settings</a>
                    </div>
                </div>
            </div>

            <div class="cd-admin-nav-section">
                <div class="cd-admin-nav-label">Operations</div>
                <button class="cd-admin-nav-toggle btn p-0 text-start {{ $adminOperationsOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminDesktopOperations" aria-expanded="{{ $adminOperationsOpen ? 'true' : 'false' }}" aria-controls="adminDesktopOperations">
                    <span class="cd-admin-nav-link w-100">
                        <svg class="cd-admin-nav-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M6 0a2 2 0 0 0-2 2v1H2.5A1.5 1.5 0 0 0 1 4.5v9A2.5 2.5 0 0 0 3.5 16h9A2.5 2.5 0 0 0 15 13.5v-9A1.5 1.5 0 0 0 13.5 3H12V2a2 2 0 0 0-2-2H6zm5 3V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v1h6z"/>
                            <path d="M2 6h12v7.5A1.5 1.5 0 0 1 12.5 15h-9A1.5 1.5 0 0 1 2 13.5V6z"/>
                        </svg>
                        Manage
                        <svg class="cd-admin-nav-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M1.5 5.5a.5.5 0 0 1 .707 0L8 11.293l5.793-5.793a.5.5 0 0 1 .707.707l-6.146 6.146a.5.5 0 0 1-.708 0L1.5 6.207a.5.5 0 0 1 0-.707z"/>
                        </svg>
                    </span>
                </button>
                <div class="collapse {{ $adminOperationsOpen ? 'show' : '' }}" id="adminDesktopOperations">
                    <div class="cd-admin-subnav">
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">Jobs</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}" href="{{ route('admin.job-applications.index') }}">Job Applications</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.service-subscriptions.*') ? 'active' : '' }}" href="{{ route('admin.service-subscriptions.index') }}">Service Subscriptions</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.it-intakes.*') ? 'active' : '' }}" href="{{ route('admin.it-intakes.index') }}">IT Intake</a>
                        <a class="cd-admin-nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}" href="{{ route('admin.contact-messages.index') }}">Contact Messages</a>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-3">
                <form method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-outline-dark w-100" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </aside>

<div class="global-loader" id="globalLoader" aria-hidden="true">
    <div class="global-loader-card card">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
                <div class="global-loader-spinner" aria-hidden="true"></div>
                <div class="flex-grow-1">
                    <div class="fw-semibold">Loading…</div>
                    <div class="text-muted small" id="globalLoaderHint">Please wait.</div>
                    <div class="mt-3 d-none" id="globalLoaderActions">
                        <a class="btn btn-sm btn-primary" id="globalLoaderRetryLink" href="{{ url()->current() }}">Retry</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
                        <a class="btn btn-sm btn-outline-secondary" id="globalLoaderTimeoutLink" href="{{ route('service-timeout') }}">Service Timeout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="cd-admin-content">
        <main class="cd-admin-main">
            <div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm3.93-9.412-4.29 4.29a.75.75 0 0 1-1.06 0L4.07 8.368a.75.75 0 1 1 1.06-1.06l2.02 2.02 3.76-3.76a.75.75 0 0 1 1.06 1.06z"/>
            </svg>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-10.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l1.002-4.705c.076-.34-.006-.545-.999-.42z"/>
                <circle cx="8" cy="4.5" r="1"/>
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-10.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l1.002-4.705c.076-.34-.006-.545-.999-.42z"/>
                <circle cx="8" cy="4.5" r="1"/>
            </svg>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @yield('content')
            </div>
        </main>
    </div>
</div>
@stack('modals')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggles = document.querySelectorAll('[data-cd-theme-toggle]');
        if (toggles.length) {
            function currentTheme() {
                return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            }

            toggles.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = currentTheme() === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    try {
                        localStorage.setItem('cd_theme', next);
                    } catch (e) {}
                });
            });
        }

        var mobileSidebar = document.getElementById('adminSidebarMobile');
        if (mobileSidebar && window.bootstrap && window.bootstrap.Offcanvas) {
            mobileSidebar.addEventListener('click', function (e) {
                var link = e.target && e.target.closest ? e.target.closest('a') : null;
                if (!link) return;
                var instance = window.bootstrap.Offcanvas.getInstance(mobileSidebar);
                if (instance) instance.hide();
            }, true);
        }

        var loader = document.getElementById('globalLoader');
        if (!loader) return;

        var hint = document.getElementById('globalLoaderHint');
        var actions = document.getElementById('globalLoaderActions');
        var retryLink = document.getElementById('globalLoaderRetryLink');
        var timeoutLink = document.getElementById('globalLoaderTimeoutLink');
        var timeoutBaseHref = timeoutLink ? timeoutLink.getAttribute('href') : null;
        var lastTargetUrl = null;
        var timeoutId = null;
        var actionsId = null;

        function showLoader(targetUrl) {
            loader.classList.add('is-active');
            loader.setAttribute('aria-hidden', 'false');

            if (hint) {
                hint.textContent = 'Please wait.';
            }
            if (actions) {
                actions.classList.add('d-none');
            }

            if (timeoutId) clearTimeout(timeoutId);
            if (actionsId) clearTimeout(actionsId);

            lastTargetUrl = typeof targetUrl === 'string' && targetUrl ? targetUrl : null;

            if (retryLink) {
                retryLink.setAttribute('href', lastTargetUrl || window.location.href);
            }
            if (timeoutLink && timeoutBaseHref) {
                var timeoutHref = timeoutBaseHref;
                if (lastTargetUrl) {
                    timeoutHref = timeoutHref + '?retry=' + encodeURIComponent(lastTargetUrl);
                }
                timeoutLink.setAttribute('href', timeoutHref);
            }

            timeoutId = setTimeout(function () {
                if (hint) {
                    hint.textContent = 'This is taking longer than expected.';
                }
            }, 9000);

            actionsId = setTimeout(function () {
                if (actions) {
                    actions.classList.remove('d-none');
                }
            }, 14000);
        }

        function hideLoader() {
            loader.classList.remove('is-active');
            loader.setAttribute('aria-hidden', 'true');
            if (timeoutId) clearTimeout(timeoutId);
            if (actionsId) clearTimeout(actionsId);
            timeoutId = null;
            actionsId = null;
        }

        window.addEventListener('pageshow', hideLoader);
        window.addEventListener('focus', function () {
            if (document.visibilityState === 'visible') {
                hideLoader();
            }
        });

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || form.method && form.method.toLowerCase() === 'dialog') return;
            var action = (form.getAttribute && form.getAttribute('action')) ? form.getAttribute('action') : '';
            var target = null;
            try {
                target = new URL(action || window.location.href, window.location.href).toString();
            } catch (err) {
                target = window.location.href;
            }
            showLoader(target);
        }, true);

        document.addEventListener('click', function (e) {
            var link = e.target && e.target.closest ? e.target.closest('a') : null;
            if (!link) return;
            if (link.target && link.target !== '_self') return;
            if (link.hasAttribute('download')) return;
            if (link.getAttribute('href') === null) return;
            var href = link.getAttribute('href') || '';
            if (href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('sms:')) return;

            var target = null;
            try {
                target = new URL(href, window.location.href).toString();
            } catch (err) {
                target = window.location.href;
            }

            showLoader(target);
        }, true);

        window.addEventListener('load', hideLoader);
    });
</script>
@yield('scripts')
</body>
</html>
