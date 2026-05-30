<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Super Admin | Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cd-primary: #6366f1;
            --cd-primary-hover: #4f46e5;
            --cd-bg: #0b0f19;
            --cd-card-bg: rgba(17, 24, 39, 0.55);
            --cd-text: #f3f4f6;
            --cd-text-muted: #9ca3af;
            --cd-border: rgba(255, 255, 255, 0.08);
            --cd-focus-ring: rgba(99, 102, 241, 0.25);
        }
        body {
            background-color: var(--cd-bg);
            color: var(--cd-text);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            margin: 0;
            padding: 1.5rem;
        }
        /* Background decorative blobs */
        .bg-blob {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.18;
            z-index: 0;
            pointer-events: none;
            animation: float 20s infinite alternate ease-in-out;
        }
        .bg-blob-1 {
            background: #4f46e5;
            top: -10%;
            left: -10%;
        }
        .bg-blob-2 {
            background: #ec4899;
            bottom: -10%;
            right: -10%;
            animation-delay: -10s;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, 60px) scale(1.1); }
            100% { transform: translate(-20px, -20px) scale(0.95); }
        }
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .glass-card {
            background: var(--cd-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--cd-border);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 2.5rem 2.25rem;
        }
        .logo-frame {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        }
        .register-title {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 0.35rem;
        }
        .register-subtitle {
            font-size: 0.9rem;
            color: var(--cd-text-muted);
            margin-bottom: 2rem;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--cd-text-muted);
            margin-bottom: 0.5rem;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--cd-border);
            color: #ffffff;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-family: inherit;
            transition: all 0.25s ease;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.06);
            border-color: var(--cd-primary);
            color: #ffffff;
            box-shadow: 0 0 0 4px var(--cd-focus-ring);
        }
        .btn-submit {
            background: linear-gradient(135deg, var(--cd-primary) 0%, var(--cd-primary-hover) 100%);
            border: none;
            color: #ffffff;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -10 rgba(99, 102, 241, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.6);
            filter: brightness(1.05);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .alert-custom {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px dashed rgba(239, 68, 68, 0.3);
            color: #f87171;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

@php($siteSettings = \App\Models\SiteSetting::query()->first())
@php($brandName = $siteSettings?->site_name ?? config('app.name', 'Codediera'))

<div class="bg-blob bg-blob-1"></div>
<div class="bg-blob bg-blob-2"></div>

<div class="register-container">
    <div class="glass-card">
        <div class="text-center">
            <div class="logo-frame">
                @if($siteSettings?->logo_path)
                    <img class="logo-img" src="{{ asset('storage/'.$siteSettings->logo_path) }}" alt="Logo" style="max-width: 32px; height: auto;">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shield-lock text-indigo" viewBox="0 0 16 16" style="color: var(--cd-primary);">
                        <path d="M5.338 1.59a61.447 61.447 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.22 62.22 0 0 1 5.072.56z"/>
                        <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415v2.585a.5.5 0 0 1-1 0V7.915A1.5 1.5 0 1 1 9.5 6.5z"/>
                    </svg>
                @endif
            </div>
            <h1 class="register-title">Setup Super Admin</h1>
            <p class="register-subtitle">Create the primary administrator account</p>
        </div>

        @if ($errors->any())
            <div class="alert-custom d-flex align-items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('admin.register.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Full Name</label>
                <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="John Doe">
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="admin@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required placeholder="••••••••">
            </div>

            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••">
            </div>

            <button class="btn btn-submit w-100 mb-3" type="submit">Create Super Admin</button>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="text-decoration-none" style="font-size: 0.9rem; color: var(--cd-text-muted);">
                    Back to Sign In
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
