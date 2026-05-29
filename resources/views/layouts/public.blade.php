<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php($siteTitle = $siteSettings?->site_name ?? config('app.name'))
    <title>{{ $siteTitle }}@hasSection('title') | @yield('title')@endif</title>
    @if($siteSettings?->meta_description)
        <meta name="description" content="{{ $siteSettings->meta_description }}">
    @endif
    @if($siteSettings?->favicon_path)
        <link rel="icon" href="{{ asset('storage/'.$siteSettings->favicon_path) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
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
            --cd-primary: {{ $siteSettings?->primary_color ?: '#0d6efd' }};
            --cd-heading: {{ $siteSettings?->heading_color ?: '#0f172a' }};
            --cd-nav-height: 72px;
            --cd-topbar-height: 32px;
            --cd-tech: var(--cd-primary);
            --cd-bg: #f6f7fb;
            --cd-surface: rgba(255, 255, 255, 0.96);
            --cd-surface-strong: rgba(255, 255, 255, 0.8);
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
            --bs-link-color: var(--cd-primary);
            --bs-link-hover-color: var(--cd-primary);
            --bs-heading-color: var(--cd-heading);
            color-scheme: light;
        }
        [data-theme="dark"] {
            --cd-heading: rgba(255, 255, 255, 0.96);
            --cd-bg: #0b1220;
            --cd-surface: rgba(17, 24, 39, 0.86);
            --cd-surface-strong: rgba(17, 24, 39, 0.72);
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
            --bs-heading-color: var(--cd-heading);
            color-scheme: dark;
        }
        body {
            background: var(--cd-bg);
            color: var(--cd-text);
            padding-top: calc(var(--cd-nav-height) + var(--cd-topbar-height));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 575.98px) {
            :root {
                --cd-nav-height: 86px;
                --cd-topbar-height: 36px;
            }
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
            opacity: 0.06;
            filter: grayscale(1);
            pointer-events: none;
            z-index: 0;
        }
        @endif
        .page-content {
            position: relative;
            z-index: 1;
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }
        .page-content h1,
        .page-content h2,
        .page-content h3,
        .page-content h4,
        .page-content h5,
        .page-content h6 {
            color: var(--cd-heading);
        }
        .card, .navbar, .btn, .form-control, .form-select, .alert, .carousel-inner, .dropdown-menu, .input-group-text {
            border-radius: 1rem;
        }
        .card {
            border: 0;
            box-shadow: var(--cd-card-shadow);
        }
        [data-theme="dark"] .card {
            background: var(--cd-surface);
            color: var(--cd-text);
            border: 1px solid var(--cd-border);
        }
        [data-theme="dark"] .card .text-muted {
            color: var(--cd-muted) !important;
        }
        [data-theme="dark"] .cd-trial-badge {
            background-color: rgba(255, 255, 255, 0.18) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }
        [data-theme="dark"] .cd-service-card {
            background: var(--cd-surface);
            color: var(--cd-text);
            border: 1px solid var(--cd-border);
        }
        [data-theme="dark"] .cd-service-card .text-muted {
            color: var(--cd-muted) !important;
        }
        [data-theme="dark"] .cd-service-card h1,
        [data-theme="dark"] .cd-service-card h2,
        [data-theme="dark"] .cd-service-card h3,
        [data-theme="dark"] .cd-service-card h4,
        [data-theme="dark"] .cd-service-card h5,
        [data-theme="dark"] .cd-service-card h6 {
            color: var(--cd-heading);
        }
        [data-theme="dark"] .cd-service-card .cd-trial-badge {
            background-color: rgba(255, 255, 255, 0.18) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }
        .btn {
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .cd-btn-pop {
            background: var(--cd-primary);
            border: 1px solid rgba(13, 110, 253, 0.45);
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(13, 110, 253, 0.28);
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        }
        .cd-btn-pop:hover,
        .cd-btn-pop:focus {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(13, 110, 253, 0.34);
            filter: brightness(1.02);
        }
        .cd-btn-pop:active {
            transform: translateY(0);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.24);
        }
        .btn-primary {
            background-color: var(--cd-primary);
            border-color: var(--cd-primary);
        }
        .btn-outline-primary {
            color: var(--cd-primary);
            border-color: var(--cd-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--cd-primary);
            border-color: var(--cd-primary);
        }
        .text-primary {
            color: var(--cd-primary) !important;
        }
        .bg-primary {
            background-color: var(--cd-primary) !important;
        }
        .border-primary {
            border-color: var(--cd-primary) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--cd-primary);
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
        }
        .section-title {
            letter-spacing: -0.02em;
        }
        .icon-badge {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.12);
            color: var(--cd-primary);
            flex: 0 0 auto;
        }
        [data-theme="dark"] .icon-badge {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.12);
            color: rgba(0, 0, 0, 0.86);
        }
        .page-head {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }
        .page-head h1, .page-head h2 {
            margin-bottom: 0.25rem;
        }
        .page-subtitle {
            color: var(--cd-muted);
        }
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .section-kicker {
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--cd-muted-2);
        }
        .nav-icon {
            vertical-align: -0.12em;
            opacity: 0.85;
        }
        .cd-navbar {
            padding-top: 0;
            padding-bottom: 0;
        }
        @media (min-width: 992px) {
            .navbar-expand-lg.cd-navbar {
                flex-wrap: wrap !important;
            }
        }
        .cd-topbar {
            width: 100%;
            flex: 0 0 100%;
            height: var(--cd-topbar-height);
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }
        .cd-nav-main {
            width: 100%;
            flex: 0 0 100%;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            flex-wrap: nowrap;
        }
        .cd-navbar .cd-topbar > .container,
        .cd-navbar .cd-nav-main.container {
            width: 90%;
            max-width: 90%;
        }
        .cd-nav-main .nav-link {
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            transition: background-color .15s ease, color .15s ease;
        }
        .cd-navbar.is-light .cd-nav-main .nav-link:hover {
            background: var(--cd-hover);
        }
        .cd-navbar.is-tech .cd-nav-main .nav-link:hover {
            background: rgba(255, 255, 255, 0.14);
        }
        .cd-navbar.is-light .cd-nav-main .nav-link.active {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            color: var(--cd-primary);
            font-weight: 700;
        }
        .cd-navbar.is-tech .cd-nav-main .nav-link.active {
            background: rgba(255, 255, 255, 0.20);
            color: rgba(255, 255, 255, 0.98);
            font-weight: 700;
        }
        @media (min-width: 992px) {
            .cd-nav-main .navbar-brand.flex-grow-1 {
                flex-grow: 0 !important;
            }
            .cd-nav-main {
                padding-top: 0;
                padding-bottom: 0;
            }
            .cd-nav-main .nav-link {
                padding: 0.3rem 0.65rem;
            }
            .cd-navbar .navbar-toggler {
                padding: 0.2rem 0.35rem;
                font-size: 0.78rem;
                border-radius: 10px;
            }
            .cd-navbar .navbar-toggler-icon {
                width: 0.95em;
                height: 0.95em;
            }
            .cd-nav-main .navbar-collapse {
                flex-grow: 1;
                justify-content: flex-end;
                order: 2;
            }
            .cd-nav-main .cd-theme-toggle {
                order: 3;
                margin-left: 0.5rem;
                margin-right: 0 !important;
            }
            .cd-nav-main .navbar-brand {
                order: 1;
            }
        }
        .cd-topbar-icon {
            width: 22px;
            height: 22px;
            border-radius: 9px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .cd-topbar-icon svg {
            width: 12px;
            height: 12px;
        }
        .cd-topbar-phone {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            font-weight: 600;
        }
        .cd-topbar-phone svg {
            width: 12px;
            height: 12px;
        }
        .cd-navbar.is-tech .cd-topbar,
        .cd-navbar.is-tech .cd-topbar a {
            color: rgba(255, 255, 255, 0.92);
        }
        .cd-navbar.is-tech .cd-topbar a.cd-topbar-icon {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.12);
            color: rgba(0, 0, 0, 0.86);
        }
        .cd-navbar.is-light .cd-topbar {
            border-bottom-color: var(--cd-border);
        }
        .cd-navbar.is-light .cd-topbar,
        .cd-navbar.is-light .cd-topbar a {
            color: var(--cd-text);
        }
        .cd-navbar.is-light .cd-topbar a.cd-topbar-icon {
            background: rgba(13, 110, 253, 0.12);
            color: rgba(0, 0, 0, 0.86);
        }
        #homeCarousel {
            --cd-carousel-h: 520px;
        }
        @media (max-width: 767.98px) {
            #homeCarousel {
                --cd-carousel-h: 420px;
            }
        }
        #homeCarousel .carousel-inner,
        #homeCarousel .carousel-item {
            height: var(--cd-carousel-h);
        }
        #homeCarousel .carousel-item > img {
            height: 100%;
            object-fit: cover;
        }
        #homeCarousel .carousel-item > video {
            height: 100%;
            object-fit: cover;
        }
        #homeCarousel .cd-carousel-fallback {
            height: 100%;
        }
        .carousel.carousel-fade .carousel-item {
            transition: opacity .9s ease-in-out;
        }
        #homeCarousel .carousel-item img {
            transform: translateX(0) scale(1);
            transition: transform .9s ease-in-out, filter .9s ease-in-out;
            will-change: transform, filter;
        }
        #homeCarousel .carousel-item video {
            transform: translateX(0) scale(1);
            transition: transform .9s ease-in-out, filter .9s ease-in-out;
            will-change: transform, filter;
        }
        #homeCarousel .carousel-item.active img {
            transform: translateX(0) scale(1.02);
        }
        #homeCarousel .carousel-item.active video {
            transform: translateX(0) scale(1.02);
        }
        #homeCarousel .carousel-item.skate-out img {
            transform: translateX(-24px) scale(1.06) rotate(-0.35deg);
            filter: blur(0.6px);
        }
        #homeCarousel .carousel-item.skate-out video {
            transform: translateX(-24px) scale(1.06) rotate(-0.35deg);
            filter: blur(0.6px);
        }
        @media (max-width: 767.98px) {
            #homeCarousel .carousel-item.active video {
                transform: translateX(0) scale(1.12);
            }
        }
        .carousel-caption {
            transition: opacity .6s ease, transform .6s ease;
            transform: translateY(6px);
            opacity: 0;
        }
        .carousel-item.active .carousel-caption {
            opacity: 1;
            transform: translateY(0);
        }
        .carousel-mobile-caption {
            transition: opacity .6s ease, transform .6s ease;
            transform: translateY(6px);
            opacity: 0;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            border-radius: 0 !important;
        }
        .carousel-item.active .carousel-mobile-caption {
            opacity: 1;
            transform: translateY(0);
        }
        .brand-mobile {
            line-height: 1.05;
            text-align: center;
        }
        .brand-mobile .brand-line1 {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            display: inline-block;
        }
        .brand-mobile .brand-line2 {
            color: var(--cd-primary);
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }
        @media (max-width: 575.98px) {
            .navbar-brand {
                justify-content: center;
            }
        }
        .cd-navbar {
            transition: background-color .25s ease, box-shadow .25s ease;
        }
        .cd-navbar.is-tech {
            background-color: var(--cd-tech);
        }
        .cd-navbar.is-tech .navbar-brand,
        .cd-navbar.is-tech .nav-link {
            color: rgba(255, 255, 255, 0.95);
        }
        .cd-navbar.is-tech .nav-link:hover {
            color: rgba(255, 255, 255, 1);
        }
        .cd-navbar.is-tech .brand-mobile .brand-line2 {
            color: rgba(255, 255, 255, 0.9);
        }
        .cd-navbar.is-light {
            background-color: var(--cd-surface);
            backdrop-filter: blur(10px);
            box-shadow: var(--cd-shadow);
        }
        .cd-navbar.is-light .navbar-brand,
        .cd-navbar.is-light .nav-link {
            color: var(--cd-text);
        }
        .cd-navbar.is-light .nav-link:hover {
            color: var(--cd-text);
        }
        .cd-navbar.is-light .brand-mobile .brand-line2 {
            color: #333333;
            -webkit-text-stroke: 1px #ffffff;
            text-stroke: 1px #ffffff;
        }
        .logo-frame {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            border-radius: 999px;
            border: 2px groove rgba(255, 255, 255, 0.65);
            background: rgba(255, 255, 255, 0.12);
        }
        .cd-navbar.is-tech .logo-frame {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
        }
        .cd-navbar.is-light .logo-frame {
            border-color: rgba(13, 110, 253, 0.35);
            background: #ffffff;
        }
        [data-theme="dark"] .cd-navbar .logo-frame {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
        }
        [data-theme="dark"] .cd-navbar .cd-topbar a.cd-topbar-icon {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
            color: rgba(0, 0, 0, 0.86);
        }
        [data-theme="dark"] .cd-navbar .cd-theme-toggle {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
            color: rgba(0, 0, 0, 0.86);
        }
        [data-theme="dark"] .cd-navbar .navbar-toggler {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
        }
        [data-theme="dark"] .cd-navbar .navbar-toggler-icon {
            filter: invert(1);
        }
        [data-theme="dark"] .cd-navbar .nav-icon {
            display: inline-block;
            padding: 2px;
            border-radius: 6px;
            background: #ffffff;
            color: rgba(0, 0, 0, 0.86);
            opacity: 1;
        }
        .cd-navbar.is-tech .nav-icon {
            display: inline-block;
            padding: 2px;
            border-radius: 6px;
            background: #ffffff;
            color: rgba(0, 0, 0, 0.86);
            opacity: 1;
        }
        .cd-navbar.is-tech .navbar-toggler {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.12);
        }
        .cd-navbar.is-tech .navbar-toggler-icon {
            filter: invert(1);
        }
        .brand-logo {
            height: 32px;
            width: auto;
            border-radius: 999px;
            transform-origin: 50% 50%;
            animation: cd-logo-twist-zoom 10s ease-in-out infinite;
        }
        .cd-navbar.is-light .navbar-toggler {
            border-color: var(--cd-border);
        }
        .cd-navbar.is-light .navbar-toggler-icon {
            filter: invert(1);
        }
        [data-theme="dark"] .cd-navbar.is-light .navbar-toggler-icon {
            filter: invert(1);
        }
        .navbar-toggler {
            padding: 0.25rem 0.45rem;
            font-size: 0.85rem;
            border-radius: 12px;
        }
        .navbar-toggler-icon {
            width: 1.05em;
            height: 1.05em;
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
            border-top-color: var(--cd-primary);
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
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.92);
        }
        .cd-navbar.is-tech .cd-theme-toggle {
            border-color: rgba(0, 0, 0, 0.12);
            background: #ffffff;
            color: rgba(0, 0, 0, 0.86);
        }
        .cd-navbar.is-light .cd-theme-toggle {
            border-color: var(--cd-border);
            background: #ffffff;
            color: var(--cd-primary);
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
        @keyframes cd-spin {
            to { transform: rotate(360deg); }
        }
        @keyframes cd-logo-twist-zoom {
            0% { transform: scale(1); }
            25% { transform: scale(1.22); }
            50% { transform: scale(0.70); }
            75% { transform: scale(1.14); }
            100% { transform: scale(1); }
        }

        /* Custom project card styling */
        .cd-project-card {
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            background: var(--cd-surface);
            border: 1px solid var(--cd-border);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .cd-project-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 38px rgba(15, 23, 42, 0.12);
            border-color: rgba(13, 110, 253, 0.28);
        }
        [data-theme="dark"] .cd-project-card {
            background: var(--cd-surface);
            border: 1px solid var(--cd-border);
        }
        [data-theme="dark"] .cd-project-card:hover {
            box-shadow: 0 20px 38px rgba(0, 0, 0, 0.38);
            border-color: rgba(13, 110, 253, 0.45);
        }
        .cd-project-card .card-img-container {
            position: relative;
            overflow: hidden;
            background-color: var(--cd-hover);
        }
        .cd-project-card .card-img-top {
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cd-project-card:hover .card-img-top {
            transform: scale(1.06);
        }
        .cd-project-card .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 1.5rem;
        }
        .cd-project-card .card-title {
            color: var(--cd-heading);
            transition: color 0.25s ease;
            font-weight: 700;
        }
        .cd-project-card:hover .card-title {
            color: var(--cd-primary);
        }
        .cd-project-card .card-text {
            color: var(--cd-muted);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Custom service card styling */
        .cd-service-card {
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            background: var(--cd-surface);
            border: 1px solid var(--cd-border);
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 1.25rem !important;
        }
        .cd-service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 38px rgba(15, 23, 42, 0.12);
            border-color: rgba(13, 110, 253, 0.28);
        }
        .cd-service-card .card-img-container {
            position: relative;
            overflow: hidden;
            border-top-left-radius: 1.25rem;
            border-top-right-radius: 1.25rem;
        }
        .cd-service-card .card-img-top {
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cd-service-card:hover .card-img-top {
            transform: scale(1.06);
        }
        .cd-service-card .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 1.5rem;
        }
        .cd-service-card .card-title {
            color: var(--cd-heading);
            transition: color 0.25s ease;
            font-weight: 700;
        }
        .cd-service-card:hover .card-title {
            color: var(--cd-primary);
        }
        .cd-service-card .card-text {
            color: var(--cd-muted);
            font-size: 0.875rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<?php
    $brand = $siteSettings?->site_name ?? config('app.name', 'Codediera Technologies');
    $brandParts = explode(' ', trim($brand), 2);
    $brandFirst = $brandParts[0] ?? $brand;
    $brandSecond = $brandParts[1] ?? null;

    $headerSocial = [
        'facebook' => $siteSettings?->social_facebook,
        'instagram' => $siteSettings?->social_instagram,
        'twitter' => $siteSettings?->social_twitter,
        'linkedin' => $siteSettings?->social_linkedin,
        'whatsapp' => $siteSettings?->social_whatsapp,
    ];

    $headerHasSocial = !empty(array_filter($headerSocial));
    $headerPhone = ($contactSettings ?? null)?->phone;
    $headerHasTopbar = $headerHasSocial || !empty($headerPhone);
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top cd-navbar shadow-sm is-tech" id="mainHeader">
    @if($headerHasTopbar)
        <div class="cd-topbar">
            <div class="container d-flex justify-content-center align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    {{--
                        $headerSocialIcons = [
                            'facebook' => '<path d="M16 8.049C16 3.604 12.418 0 8 0S0 3.604 0 8.049C0 12.07 2.925 15.413 6.75 16v-5.625H4.719V8.049H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.01c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.355 2.326H9.25V16C13.075 15.413 16 12.07 16 8.049z"/>',
                            'instagram' => '<path d="M8 0C5.829 0 5.555.01 4.703.048c-.85.038-1.433.174-1.944.372a3.902 3.902 0 0 0-1.41.92 3.902 3.902 0 0 0-.92 1.41c-.198.511-.334 1.094-.372 1.944C.01 5.555 0 5.829 0 8c0 2.171.01 2.445.048 3.297.038.85.174 1.433.372 1.944.216.558.506 1.03.92 1.41.38.414.852.704 1.41.92.511.198 1.094.334 1.944.372C5.555 15.99 5.829 16 8 16c2.171 0 2.445-.01 3.297-.048.85-.038 1.433-.174 1.944-.372a3.902 3.902 0 0 0 1.41-.92 3.902 3.902 0 0 0 .92-1.41c.198-.511.334-1.094.372-1.944.038-.852.048-1.126.048-3.297 0-2.171-.01-2.445-.048-3.297-.038-.85-.174-1.433-.372-1.944a3.902 3.902 0 0 0-.92-1.41 3.902 3.902 0 0 0-1.41-.92c-.511-.198-1.094-.334-1.944-.372C10.445.01 10.171 0 8 0zm0 3.892A4.108 4.108 0 1 1 3.892 8 4.104 4.104 0 0 1 8 3.892zm4.271-.214a.96.96 0 1 1-.96-.96.958.958 0 0 1 .96.96zM8 5.33A2.67 2.67 0 1 0 10.67 8 2.67 2.67 0 0 0 8 5.33z"/>',
                            'twitter' => '<path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.68 6.68 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.381A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3.25 3.25 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58 6.32 6.32 0 0 1 0 13.535 9.344 9.344 0 0 0 5.026 15z"/>',
                            'linkedin' => '<path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zM3.742 5.18c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.223 2.4 3.932c0 .694.521 1.248 1.327 1.248h.015zM13.458 13.394v-3.996c0-2.141-1.142-3.137-2.664-3.137-1.23 0-1.782.68-2.09 1.159v-1H6.304c.03.662 0 7.225 0 7.225h2.4V9.609c0-.216.016-.432.08-.586.173-.432.568-.88 1.232-.88.869 0 1.216.664 1.216 1.637v3.614h2.226z"/>',
                            'whatsapp' => '<path d="M13.601 2.326A7.897 7.897 0 0 0 8.006 0C3.588 0 .007 3.58.005 8.003c0 1.409.368 2.785 1.068 3.995L0 16l4.09-1.073a7.98 7.98 0 0 0 3.916 1.004h.003c4.418 0 8-3.58 8.002-8.003a7.94 7.94 0 0 0-2.41-5.602zM8.009 14.53h-.003a6.58 6.58 0 0 1-3.357-.928l-.24-.143-2.427.637.65-2.366-.156-.244A6.56 6.56 0 0 1 1.406 8.003c.002-3.646 2.97-6.614 6.6-6.614a6.56 6.56 0 0 1 4.67 1.94 6.57 6.57 0 0 1 1.934 4.674c-.002 3.646-2.97 6.527-6.6 6.527zm3.84-4.083c-.21-.105-1.24-.612-1.432-.682-.192-.07-.332-.105-.472.105-.14.21-.542.682-.665.822-.122.14-.245.157-.455.052-.21-.105-.887-.327-1.688-1.044-.623-.556-1.044-1.245-1.166-1.455-.122-.21-.013-.323.092-.428.094-.094.21-.245.315-.367.105-.122.14-.21.21-.35.07-.14.035-.262-.017-.367-.052-.105-.472-1.14-.647-1.56-.17-.41-.344-.354-.472-.36l-.402-.007a.772.772 0 0 0-.56.262c-.192.21-.735.717-.735 1.75 0 1.034.752 2.032.857 2.172.105.14 1.48 2.262 3.585 3.173.5.216.89.345 1.194.44.502.16.96.137 1.32.083.402-.06 1.24-.507 1.415-.997.175-.49.175-.91.122-.997-.052-.087-.192-.14-.402-.245z"/>',
                        ];
                    --}}
                    @foreach($headerSocial as $key => $href)
                        @if($href)
                            <a class="cd-topbar-icon" href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    @if($key === 'facebook')
                                        <path d="M16 8.049C16 3.604 12.418 0 8 0S0 3.604 0 8.049C0 12.07 2.925 15.413 6.75 16v-5.625H4.719V8.049H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.01c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.355 2.326H9.25V16C13.075 15.413 16 12.07 16 8.049z"/>
                                    @elseif($key === 'instagram')
                                        <path d="M8 0C5.829 0 5.555.01 4.703.048c-.85.038-1.433.174-1.944.372a3.902 3.902 0 0 0-1.41.92 3.902 3.902 0 0 0-.92 1.41c-.198.511-.334 1.094-.372 1.944C.01 5.555 0 5.829 0 8c0 2.171.01 2.445.048 3.297.038.85.174 1.433.372 1.944.216.558.506 1.03.92 1.41.38.414.852.704 1.41.92.511.198 1.094.334 1.944.372C5.555 15.99 5.829 16 8 16c2.171 0 2.445-.01 3.297-.048.85-.038 1.433-.174 1.944-.372a3.902 3.902 0 0 0 1.41-.92 3.902 3.902 0 0 0 .92-1.41c.198-.511.334-1.094.372-1.944.038-.852.048-1.126.048-3.297 0-2.171-.01-2.445-.048-3.297-.038-.85-.174-1.433-.372-1.944a3.902 3.902 0 0 0-.92-1.41 3.902 3.902 0 0 0-1.41-.92c-.511-.198-1.094-.334-1.944-.372C10.445.01 10.171 0 8 0zm0 3.892A4.108 4.108 0 1 1 3.892 8 4.104 4.104 0 0 1 8 3.892zm4.271-.214a.96.96 0 1 1-.96-.96.958.958 0 0 1 .96.96zM8 5.33A2.67 2.67 0 1 0 10.67 8 2.67 2.67 0 0 0 8 5.33z"/>
                                    @elseif($key === 'twitter')
                                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.68 6.68 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.381A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3.25 3.25 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58 6.32 6.32 0 0 1 0 13.535 9.344 9.344 0 0 0 5.026 15z"/>
                                    @elseif($key === 'linkedin')
                                        <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zM3.742 5.18c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.223 2.4 3.932c0 .694.521 1.248 1.327 1.248h.015zM13.458 13.394v-3.996c0-2.141-1.142-3.137-2.664-3.137-1.23 0-1.782.68-2.09 1.159v-1H6.304c.03.662 0 7.225 0 7.225h2.4V9.609c0-.216.016-.432.08-.586.173-.432.568-.88 1.232-.88.869 0 1.216.664 1.216 1.637v3.614h2.226z"/>
                                    @elseif($key === 'whatsapp')
                                        <path d="M13.601 2.326A7.897 7.897 0 0 0 8.006 0C3.588 0 .007 3.58.005 8.003c0 1.409.368 2.785 1.068 3.995L0 16l4.09-1.073a7.98 7.98 0 0 0 3.916 1.004h.003c4.418 0 8-3.58 8.002-8.003a7.94 7.94 0 0 0-2.41-5.602zM8.009 14.53h-.003a6.58 6.58 0 0 1-3.357-.928l-.24-.143-2.427.637.65-2.366-.156-.244A6.56 6.56 0 0 1 1.406 8.003c.002-3.646 2.97-6.614 6.6-6.614a6.56 6.56 0 0 1 4.67 1.94 6.57 6.57 0 0 1 1.934 4.674c-.002 3.646-2.97 6.527-6.6 6.527zm3.84-4.083c-.21-.105-1.24-.612-1.432-.682-.192-.07-.332-.105-.472.105-.14.21-.542.682-.665.822-.122.14-.245.157-.455.052-.21-.105-.887-.327-1.688-1.044-.623-.556-1.044-1.245-1.166-1.455-.122-.21-.013-.323.092-.428.094-.094.21-.245.315-.367.105-.122.140-.21.21-.35.07-.14.035-.262-.017-.367-.052-.105-.472-1.14-.647-1.56-.17-.41-.344-.354-.472-.36l-.402-.007a.772.772 0 0 0-.56.262c-.192.21-.735.717-.735 1.75 0 1.034.752 2.032.857 2.172.105.14 1.48 2.262 3.585 3.173.5.216.89.345 1.194.44.502.16.96.137 1.32.083.402-.06 1.24-.507 1.415-.997.175-.49.175-.91.122-.997-.052-.087-.192-.14-.402-.245z"/>
                                    @endif
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>

                @if($headerPhone)
                    <a class="cd-topbar-phone" href="tel:{{ preg_replace('/[^0-9+]/', '', $headerPhone) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M3.654 1.328a.678.678 0 0 1 .736-.187l2.522 1.01c.329.132.445.5.24.776l-1.03 1.374a.678.678 0 0 0-.145.556c.27 1.18 1.02 2.928 2.63 4.539 1.61 1.61 3.358 2.36 4.539 2.63a.678.678 0 0 0 .556-.145l1.374-1.03c.276-.205.644-.089.776.24l1.01 2.522a.678.678 0 0 1-.187.736l-1.272 1.272c-.232.232-.54.332-.826.272-1.55-.32-4.11-1.4-6.86-4.15C4.448 9.43 3.368 6.87 3.048 5.32a.678.678 0 0 1 .272-.826L4.592 3.22 3.654 1.328z"/>
                        </svg>
                        <span class="d-none d-sm-inline">Call:</span>
                        <span>{{ $headerPhone }}</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <div class="container cd-nav-main px-3 px-lg-0">
        <a class="navbar-brand d-flex align-items-center gap-2 flex-grow-1" href="{{ url('/') }}">
            @if($siteSettings?->logo_path)
                <span class="logo-frame">
                    <img class="brand-logo" src="{{ asset('storage/'.$siteSettings->logo_path) }}" alt="Logo">
                </span>
            @endif
            <span class="d-none d-sm-inline">{{ $brand }}</span>
            <span class="brand-mobile d-inline d-sm-none">
                <span class="brand-line1">{{ $brandFirst }}</span>
                @if($brandSecond)
                    <br><span class="brand-line2">-{{ $brandSecond }}-</span>
                @endif
            </span>
        </a>
        <button class="btn btn-sm cd-theme-toggle me-2" type="button" data-cd-theme-toggle aria-label="Toggle theme">
            <svg class="cd-theme-icon cd-theme-icon-sun" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                <path d="M8 0a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0zm0 14a.5.5 0 0 1 .5.5v1.25a.5.5 0 0 1-1 0V14.5A.5.5 0 0 1 8 14zm8-6a.5.5 0 0 1-.5.5h-1.25a.5.5 0 0 1 0-1H15.5A.5.5 0 0 1 16 8zM2 8a.5.5 0 0 1-.5.5H.25a.5.5 0 0 1 0-1H1.5A.5.5 0 0 1 2 8zm11.657-5.657a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM4.641 11.359a.5.5 0 0 1 0 .707l-.884.884a.5.5 0 1 1-.707-.707l.884-.884a.5.5 0 0 1 .707 0zM13.657 13.657a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707zM4.641 4.641a.5.5 0 0 1-.707 0l-.884-.884a.5.5 0 1 1 .707-.707l.884.884a.5.5 0 0 1 0 .707z"/>
            </svg>
            <svg class="cd-theme-icon cd-theme-icon-moon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M6 .278a.5.5 0 0 1 .587.81A6.5 6.5 0 1 0 14.912 9.41a.5.5 0 0 1 .81.587A7.5 7.5 0 1 1 6 .278z"/>
            </svg>
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 3.293 1 10.293V15h4v-4h6v4h4v-4.707L8 3.293z"/>
                            <path d="M7.293 1.5a1 1 0 0 1 1.414 0l6.5 6.5a1 1 0 0 1-1.414 1.414L8 3.621 2.207 9.414A1 1 0 0 1 .793 8L7.293 1.5z"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('services') || request()->is('services*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('services') ? route('services') : url('/services') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M2 2h2v2H2V2zm0 4h2v2H2V6zm0 4h2v2H2v-2zm4-8h8v2H6V2zm0 4h8v2H6V6zm0 4h8v2H6v-2z"/>
                        </svg>
                        Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('jobs.apply') || request()->is('jobs/apply*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('jobs.apply') ? route('jobs.apply') : url('/jobs/apply') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M6.5 0a.5.5 0 0 0-.5.5V2H2.5A1.5 1.5 0 0 0 1 3.5v11A1.5 1.5 0 0 0 2.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-11A1.5 1.5 0 0 0 13.5 2H10V.5a.5.5 0 0 0-1 0V2H7V.5a.5.5 0 0 0-.5-.5zM2.5 3H13.5a.5.5 0 0 1 .5.5V5H2V3.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M2 6h12v8.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V6z"/>
                        </svg>
                        Job
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('it-intake') || request()->is('it-intake*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('it-intake') ? route('it-intake') : url('/it-intake') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M9.293 0H4.5A1.5 1.5 0 0 0 3 1.5v13A1.5 1.5 0 0 0 4.5 16h7a1.5 1.5 0 0 0 1.5-1.5V4.707a1 1 0 0 0-.293-.707L10 .293A1 1 0 0 0 9.293 0zM10 1.5 11.5 3H10a.5.5 0 0 1-.5-.5V1.5z"/>
                            <path d="M4.5 9a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                        IT Intake Form
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('digital-skills') || request()->is('digital-skills*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('digital-skills') ? route('digital-skills') : url('/digital-skills') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M2 2h12a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H9.5l-1.2 1.6a.5.5 0 0 1-.8 0L6.3 12H3a2 2 0 0 1-2-2V3a1 1 0 0 1 1-1zm0 1v7a1 1 0 0 0 1 1h3.55a.5.5 0 0 1 .4.2L8 12.333l1.05-1.133a.5.5 0 0 1 .4-.2H13a1 1 0 0 0 1-1V3H2z"/>
                            <path d="M4.5 4.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1z"/>
                        </svg>
                        Digital Skills
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('projects') || request()->is('projects*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('projects') ? route('projects') : url('/projects') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-4A.5.5 0 0 1 0 5.5v-4zM1 2v3h3V2H1zm6-.5A.5.5 0 0 1 7.5 1h8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 7 5.5v-4zM8 2v3h7V2H8z"/>
                        </svg>
                        Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('team') || request()->is('team*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('team') ? route('team') : url('/team') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path d="M14 14s-1-4-6-4-6 4-6 4 1 2 6 2 6-2 6-2z"/>
                        </svg>
                        Team
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('about') || request()->is('about*')) ? 'active' : '' }}" href="{{ route('about') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 15a7 7 0 1 1 0-14 7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('contact') || request()->is('contact*')) ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('contact') ? route('contact') : url('/contact') }}">
                        <svg class="nav-icon me-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2z"/>
                            <path d="M0 4.697v7.104l5.803-3.558L0 4.697z"/>
                            <path d="M6.761 8.83 0 12.97A2 2 0 0 0 2 14h12a2 2 0 0 0 2-1.03L9.239 8.83 8 9.586 6.761 8.83z"/>
                            <path d="M16 4.697v7.104l-5.803-3.558L16 4.697z"/>
                        </svg>
                        Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                        <a class="btn btn-sm btn-outline-primary" href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}">Back to Home</a>
                        <a class="btn btn-sm btn-outline-secondary" id="globalLoaderTimeoutLink" href="{{ \Illuminate\Support\Facades\Route::has('service-timeout') ? route('service-timeout') : url('/service-timeout') }}">Service Timeout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    @if (session('status'))
        <div class="container pt-3">
            <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm3.93-9.412-4.29 4.29a.75.75 0 0 1-1.06 0L4.07 8.368a.75.75 0 1 1 1.06-1.06l2.02 2.02 3.76-3.76a.75.75 0 0 1 1.06 1.06z"/>
                </svg>
                <div>{{ session('status') }}</div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-10.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l1.002-4.705c.076-.34-.006-.545-.999-.42z"/>
                    <circle cx="8" cy="4.5" r="1"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="container pt-3">
            <div class="alert alert-danger mb-0 d-flex align-items-start gap-2" role="alert">
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
        </div>
    @endif

    @yield('content')

    <footer class="py-5 mt-auto" style="background: var(--cd-surface-strong); backdrop-filter: blur(10px); border-top: 1px solid var(--cd-border);">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        @if($siteSettings?->logo_path)
                            <span class="logo-frame" style="border-radius: 14px; padding: 6px;">
                                <img class="brand-logo" src="{{ asset('storage/'.$siteSettings->logo_path) }}" alt="Logo" style="height:28px">
                            </span>
                        @endif
                        <div class="fw-semibold">{{ $siteSettings?->site_name ?? config('app.name', 'Codediera') }}</div>
                    </div>
                    <div class="text-muted">
                        {{ $siteSettings?->footer_text ?? 'We build modern web and mobile solutions, deliver digital skills, and support career growth.' }}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="fw-semibold mb-2">Contact</div>
                    <div class="vstack gap-2 text-muted">
                        @if($contactSettings?->address)
                            <div class="d-flex align-items-start gap-2">
                                <span class="icon-badge" style="width:34px;height:34px;border-radius:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M4.146 6.146a.5.5 0 0 1 .708 0L8 9.293l3.146-3.147a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </span>
                                <div>{{ $contactSettings->address }}</div>
                            </div>
                        @endif
                        @if($contactSettings?->phone)
                            <div class="d-flex align-items-start gap-2">
                                <span class="icon-badge" style="width:34px;height:34px;border-radius:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M3.654 1.328a.678.678 0 0 1 .736-.187l2.522 1.01c.329.132.445.5.24.776l-1.03 1.374a.678.678 0 0 0-.145.556c.27 1.18 1.02 2.928 2.63 4.539 1.61 1.61 3.358 2.36 4.539 2.63a.678.678 0 0 0 .556-.145l1.374-1.03c.276-.205.644-.089.776.24l1.01 2.522a.678.678 0 0 1-.187.736l-1.272 1.272c-.232.232-.54.332-.826.272-1.55-.32-4.11-1.4-6.86-4.15C4.448 9.43 3.368 6.87 3.048 5.32a.678.678 0 0 1 .272-.826L4.592 3.22 3.654 1.328z"/>
                                    </svg>
                                </span>
                                <div><a class="text-decoration-none" href="tel:{{ preg_replace('/[^0-9+]/', '', $contactSettings->phone) }}">{{ $contactSettings->phone }}</a></div>
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
                                <div><a class="text-decoration-none" href="mailto:{{ $contactSettings->email }}">{{ $contactSettings->email }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="fw-semibold mb-2">Follow</div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                            $social = [
                                'facebook' => $siteSettings?->social_facebook,
                                'instagram' => $siteSettings?->social_instagram,
                                'twitter' => $siteSettings?->social_twitter,
                                'linkedin' => $siteSettings?->social_linkedin,
                                'whatsapp' => $siteSettings?->social_whatsapp,
                            ];
                        ?>

                            {{--
                                'facebook' => '<path d="M16 8.049C16 3.604 12.418 0 8 0S0 3.604 0 8.049C0 12.07 2.925 15.413 6.75 16v-5.625H4.719V8.049H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.01c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.355 2.326H9.25V16C13.075 15.413 16 12.07 16 8.049z"/>',
                                'instagram' => '<path d="M8 0C5.829 0 5.555.01 4.703.048c-.85.038-1.433.174-1.944.372a3.902 3.902 0 0 0-1.41.92 3.902 3.902 0 0 0-.92 1.41c-.198.511-.334 1.094-.372 1.944C.01 5.555 0 5.829 0 8c0 2.171.01 2.445.048 3.297.038.85.174 1.433.372 1.944.216.558.506 1.03.92 1.41.38.414.852.704 1.41.92.511.198 1.094.334 1.944.372C5.555 15.99 5.829 16 8 16c2.171 0 2.445-.01 3.297-.048.85-.038 1.433-.174 1.944-.372a3.902 3.902 0 0 0 1.41-.92 3.902 3.902 0 0 0 .92-1.41c.198-.511.334-1.094.372-1.944.038-.852.048-1.126.048-3.297 0-2.171-.01-2.445-.048-3.297-.038-.85-.174-1.433-.372-1.944a3.902 3.902 0 0 0-.92-1.41 3.902 3.902 0 0 0-1.41-.92c-.511-.198-1.094-.334-1.944-.372C10.445.01 10.171 0 8 0zm0 3.892A4.108 4.108 0 1 1 3.892 8 4.104 4.104 0 0 1 8 3.892zm4.271-.214a.96.96 0 1 1-.96-.96.958.958 0 0 1 .96.96zM8 5.33A2.67 2.67 0 1 0 10.67 8 2.67 2.67 0 0 0 8 5.33z"/>',
                                'twitter' => '<path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.68 6.68 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.381A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3.25 3.25 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58 6.32 6.32 0 0 1 0 13.535 9.344 9.344 0 0 0 5.026 15z"/>',
                                'linkedin' => '<path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zM3.742 5.18c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.223 2.4 3.932c0 .694.521 1.248 1.327 1.248h.015zM13.458 13.394v-3.996c0-2.141-1.142-3.137-2.664-3.137-1.23 0-1.782.68-2.09 1.159v-1H6.304c.03.662 0 7.225 0 7.225h2.4V9.609c0-.216.016-.432.08-.586.173-.432.568-.88 1.232-.88.869 0 1.216.664 1.216 1.637v3.614h2.226z"/>',
                                'whatsapp' => '<path d="M13.601 2.326A7.897 7.897 0 0 0 8.006 0C3.588 0 .007 3.58.005 8.003c0 1.409.368 2.785 1.068 3.995L0 16l4.09-1.073a7.98 7.98 0 0 0 3.916 1.004h.003c4.418 0 8-3.58 8.002-8.003a7.94 7.94 0 0 0-2.41-5.602zM8.009 14.53h-.003a6.58 6.58 0 0 1-3.357-.928l-.24-.143-2.427.637.65-2.366-.156-.244A6.56 6.56 0 0 1 1.406 8.003c.002-3.646 2.97-6.614 6.6-6.614a6.56 6.56 0 0 1 4.67 1.94 6.57 6.57 0 0 1 1.934 4.674c-.002 3.646-2.97 6.527-6.6 6.527zm3.84-4.083c-.21-.105-1.24-.612-1.432-.682-.192-.07-.332-.105-.472.105-.14.21-.542.682-.665.822-.122.14-.245.157-.455.052-.21-.105-.887-.327-1.688-1.044-.623-.556-1.044-1.245-1.166-1.455-.122-.21-.013-.323.092-.428.094-.094.21-.245.315-.367.105-.122.14-.21.21-.35.07-.14.035-.262-.017-.367-.052-.105-.472-1.14-.647-1.56-.17-.41-.344-.354-.472-.36l-.402-.007a.772.772 0 0 0-.56.262c-.192.21-.735.717-.735 1.75 0 1.034.752 2.032.857 2.172.105.14 1.48 2.262 3.585 3.173.5.216.89.345 1.194.44.502.16.96.137 1.32.083.402-.06 1.24-.507 1.415-.997.175-.49.175-.91.122-.997-.052-.087-.192-.14-.402-.245z"/>',
                            --}}
                        @foreach($social as $key => $href)
                            @if($href)
                                <a class="icon-badge text-decoration-none" style="width:34px;height:34px;border-radius:12px" href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        @if($key === 'facebook')
                                            <path d="M16 8.049C16 3.604 12.418 0 8 0S0 3.604 0 8.049C0 12.07 2.925 15.413 6.75 16v-5.625H4.719V8.049H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.01c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.355 2.326H9.25V16C13.075 15.413 16 12.07 16 8.049z"/>
                                        @elseif($key === 'instagram')
                                            <path d="M8 0C5.829 0 5.555.01 4.703.048c-.85.038-1.433.174-1.944.372a3.902 3.902 0 0 0-1.41.92 3.902 3.902 0 0 0-.92 1.41c-.198.511-.334 1.094-.372 1.944C.01 5.555 0 5.829 0 8c0 2.171.01 2.445.048 3.297.038.85.174 1.433.372 1.944.216.558.506 1.03.92 1.41.38.414.852.704 1.41.92.511.198 1.094.334 1.944.372C5.555 15.99 5.829 16 8 16c2.171 0 2.445-.01 3.297-.048.85-.038 1.433-.174 1.944-.372a3.902 3.902 0 0 0 1.41-.92 3.902 3.902 0 0 0 .92-1.41c.198-.511.334-1.094.372-1.944.038-.852.048-1.126.048-3.297 0-2.171-.01-2.445-.048-3.297-.038-.85-.174-1.433-.372-1.944a3.902 3.902 0 0 0-.92-1.41 3.902 3.902 0 0 0-1.41-.92c-.511-.198-1.094-.334-1.944-.372C10.445.01 10.171 0 8 0zm0 3.892A4.108 4.108 0 1 1 3.892 8 4.104 4.104 0 0 1 8 3.892zm4.271-.214a.96.96 0 1 1-.96-.96.958.958 0 0 1 .96.96zM8 5.33A2.67 2.67 0 1 0 10.67 8 2.67 2.67 0 0 0 8 5.33z"/>
                                        @elseif($key === 'twitter')
                                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.68 6.68 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.381A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3.25 3.25 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58 6.32 6.32 0 0 1 0 13.535 9.344 9.344 0 0 0 5.026 15z"/>
                                        @elseif($key === 'linkedin')
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zM3.742 5.18c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.223 2.4 3.932c0 .694.521 1.248 1.327 1.248h.015zM13.458 13.394v-3.996c0-2.141-1.142-3.137-2.664-3.137-1.23 0-1.782.68-2.09 1.159v-1H6.304c.03.662 0 7.225 0 7.225h2.4V9.609c0-.216.016-.432.08-.586.173-.432.568-.88 1.232-.88.869 0 1.216.664 1.216 1.637v3.614h2.226z"/>
                                        @elseif($key === 'whatsapp')
                                            <path d="M13.601 2.326A7.897 7.897 0 0 0 8.006 0C3.588 0 .007 3.58.005 8.003c0 1.409.368 2.785 1.068 3.995L0 16l4.09-1.073a7.98 7.98 0 0 0 3.916 1.004h.003c4.418 0 8-3.58 8.002-8.003a7.94 7.94 0 0 0-2.41-5.602zM8.009 14.53h-.003a6.58 6.58 0 0 1-3.357-.928l-.24-.143-2.427.637.65-2.366-.156-.244A6.56 6.56 0 0 1 1.406 8.003c.002-3.646 2.97-6.614 6.6-6.614a6.56 6.56 0 0 1 4.67 1.94 6.57 6.57 0 0 1 1.934 4.674c-.002 3.646-2.97 6.527-6.6 6.527zm3.84-4.083c-.21-.105-1.24-.612-1.432-.682-.192-.07-.332-.105-.472.105-.14.21-.542.682-.665.822-.122.14-.245.157-.455.052-.21-.105-.887-.327-1.688-1.044-.623-.556-1.044-1.245-1.166-1.455-.122-.21-.013-.323.092-.428.094-.094.21-.245.315-.367.105-.122.140-.21.21-.35.07-.14.035-.262 -.017-.367-.052-.105-.472-1.14-.647-1.56-.17-.41-.344-.354 -.472-.36l-.402-.007a.772.772 0 0 0-.56.262c-.192.21-.735.717-.735 1.75 0 1.034.752 2.032.857 2.172.105.14 1.48 2.262 3.585 3.173.5.216.89.345 1.194.44.502.16.96.137 1.32.083.402-.06 1.24-.507 1.415-.997.175-.49.175-.91.122-.997-.052-.087-.192-.14-.402-.245z"/>
                                        @endif
                                    </svg>
                                </a>
                            @endif
                        @endforeach
                        @if(empty(array_filter($social)))
                            <div class="text-muted small">Add social links in Admin → Site Settings.</div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--cd-border);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-muted small">
                <div>
                    {{ $siteSettings?->copyright_text ?: ('© '.date('Y').' '.($siteSettings?->site_name ?? config('app.name', 'Codediera')).'. All rights reserved.') }}
                </div>
                <div class="d-flex gap-3">
                    <a class="text-decoration-none text-muted" href="{{ \Illuminate\Support\Facades\Route::has('services') ? route('services') : url('/services') }}">Services</a>
                    <a class="text-decoration-none text-muted" href="{{ \Illuminate\Support\Facades\Route::has('projects') ? route('projects') : url('/projects') }}">Projects</a>
                    <a class="text-decoration-none text-muted" href="{{ route('about') }}">About Us</a>
                    <a class="text-decoration-none text-muted" href="{{ \Illuminate\Support\Facades\Route::has('contact') ? route('contact') : url('/contact') }}">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Project Detail Modal -->
<div class="modal fade" id="projectDetailModal" tabindex="-1" aria-labelledby="projectDetailTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.25rem; overflow: hidden; background: var(--cd-surface);">
            <div class="modal-header border-0 pb-0" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">
                <button type="button" class="btn-close bg-white rounded-circle p-2 shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-md-5 d-none d-md-block" id="projectDetailImgCol">
                        <div class="h-100 position-relative" style="min-height: 380px;">
                            <img id="projectDetailImg" src="" alt="Project Image" class="w-100 h-100 position-absolute start-0 top-0" style="object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-12 col-md-7 p-4 p-lg-5">
                        <div class="d-flex flex-column h-100">
                            <!-- Mobile Image -->
                            <div class="d-md-none mb-3 overflow-hidden rounded-3" id="projectDetailMobileImgCol" style="height: 200px;">
                                <img id="projectDetailMobileImg" src="" alt="Project Image" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            
                            <div class="mb-2">
                                <span class="badge text-bg-primary px-3 py-2 fw-semibold" id="projectDetailCost"></span>
                            </div>
                            
                            <h3 class="h4 mb-3 fw-bold" id="projectDetailTitle"></h3>
                            
                            <div class="text-muted mb-4 flex-grow-1" id="projectDetailDesc" style="font-size: 0.95rem; line-height: 1.6;"></div>
                            
                            <div class="d-flex gap-2 mt-auto">
                                <a id="projectDetailDemoBtn" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2" href="" target="_blank" rel="noreferrer" style="flex: 1 1 auto; padding: 0.6rem 1.2rem;">
                                    <span>Visit Site</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                        <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                    </svg>
                                </a>
                                <a id="projectDetailZipBtn" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-2" href="" download style="flex: 1 1 auto; padding: 0.6rem 1.2rem;">
                                    <span>Download ZIP</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('modals')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggles = document.querySelectorAll('[data-cd-theme-toggle]');
        if (!toggles.length) return;

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
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                    hint.textContent = 'This is taking longer than expected. Please check your connection.';
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var header = document.getElementById('mainHeader');
        if (!header) return;

        var lastY = window.scrollY || 0;
        var ticking = false;

        function applyState(currentY) {
            var directionDown = currentY > lastY;

            if (Math.abs(currentY - lastY) < 6) {
                return;
            }

            header.classList.toggle('is-tech', directionDown);
            header.classList.toggle('is-light', !directionDown);

            lastY = currentY;
        }

        window.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(function () {
                applyState(window.scrollY || 0);
                ticking = false;
            });
        }, { passive: true });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carousel = document.getElementById('homeCarousel');
        if (!carousel) return;

        function pauseVideos(root) {
            (root || document).querySelectorAll('#homeCarousel video').forEach(function (v) {
                try { v.pause(); } catch (e) {}
            });
        }

        function playActiveVideo() {
            var active = carousel.querySelector('.carousel-item.active');
            if (!active) return;
            var v = active.querySelector('video');
            if (!v) return;

            v.muted = true;
            v.playsInline = true;
            v.loop = true;
            v.autoplay = true;

            try { v.currentTime = 0; } catch (e) {}
            var p = null;
            try { p = v.play(); } catch (e) {}
            if (p && typeof p.catch === 'function') {
                p.catch(function () {});
            }
        }

        carousel.addEventListener('slide.bs.carousel', function () {
            var active = carousel.querySelector('.carousel-item.active');
            if (active) active.classList.add('skate-out');
            pauseVideos(carousel);
        });

        carousel.addEventListener('slid.bs.carousel', function () {
            carousel.querySelectorAll('.carousel-item.skate-out').forEach(function (el) {
                el.classList.remove('skate-out');
            });
            playActiveVideo();
        });

        if (!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
            playActiveVideo();
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('projectDetailModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (!button) return;

            var title = button.getAttribute('data-project-title') || '';
            var desc = button.getAttribute('data-project-desc') || '';
            var image = button.getAttribute('data-project-image') || '';
            var url = button.getAttribute('data-project-url') || '';
            var cost = button.getAttribute('data-project-cost') || '';
            var zip = button.getAttribute('data-project-zip') || '';

            modalEl.querySelector('#projectDetailTitle').textContent = title;
            
            // Description
            var descEl = modalEl.querySelector('#projectDetailDesc');
            if (descEl) descEl.innerHTML = desc.replace(/\n/g, '<br>');

            // Image
            var imgEl = modalEl.querySelector('#projectDetailImg');
            var imgMobileEl = modalEl.querySelector('#projectDetailMobileImg');
            var imgCol = modalEl.querySelector('#projectDetailImgCol');
            var imgMobileCol = modalEl.querySelector('#projectDetailMobileImgCol');
            
            if (image) {
                if (imgEl) imgEl.setAttribute('src', image);
                if (imgMobileEl) imgMobileEl.setAttribute('src', image);
                if (imgCol) imgCol.classList.remove('d-none');
                if (imgMobileCol) imgMobileCol.classList.remove('d-none');
            } else {
                if (imgCol) imgCol.classList.add('d-none');
                if (imgMobileCol) imgMobileCol.classList.add('d-none');
            }

            // Cost/Price
            var costBadge = modalEl.querySelector('#projectDetailCost');
            if (cost) {
                if (costBadge) {
                    costBadge.textContent = cost;
                    costBadge.classList.remove('d-none');
                }
            } else {
                if (costBadge) costBadge.classList.add('d-none');
            }

            // Live Demo Link
            var demoBtn = modalEl.querySelector('#projectDetailDemoBtn');
            if (url) {
                if (demoBtn) {
                    demoBtn.setAttribute('href', url);
                    demoBtn.classList.remove('d-none');
                }
            } else {
                if (demoBtn) demoBtn.classList.add('d-none');
            }

            // Download Zip Link
            var zipBtn = modalEl.querySelector('#projectDetailZipBtn');
            if (zip) {
                if (zipBtn) {
                    zipBtn.setAttribute('href', zip);
                    zipBtn.classList.remove('d-none');
                }
            } else {
                if (zipBtn) zipBtn.classList.add('d-none');
            }
        });
    });
</script>
</body>
</html>
