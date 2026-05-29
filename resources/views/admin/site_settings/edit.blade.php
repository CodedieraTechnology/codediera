@extends('admin.layout')

@section('title', 'Site Settings')

@section('content')
    <h1 class="h3 mb-3">Site Settings</h1>

    <form method="post" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="card card-body">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label" for="site_name">Site Name</label>
                <input class="form-control" id="site_name" name="site_name" type="text" placeholder="Codediera Technologies" value="{{ old('site_name', $settings->site_name) }}">
            </div>
            <div class="col-lg-3 mb-3">
                <label class="form-label" for="primary_color">Primary Color</label>
                <input class="form-control form-control-color w-100" id="primary_color" name="primary_color" type="color" value="{{ old('primary_color', $settings->primary_color ?: '#0d6efd') }}">
            </div>
            <div class="col-lg-3 mb-3">
                <label class="form-label" for="heading_color">Heading Color</label>
                <input class="form-control form-control-color w-100" id="heading_color" name="heading_color" type="color" value="{{ old('heading_color', $settings->heading_color ?: '#0f172a') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <input class="form-control" id="meta_description" name="meta_description" type="text" placeholder="Short description for search engines…" value="{{ old('meta_description', $settings->meta_description) }}">
        </div>

        <div class="card card-body mb-3">
            <div class="fw-semibold mb-2">Home Hero</div>
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label" for="home_hero_kicker">Kicker (optional)</label>
                    <input class="form-control" id="home_hero_kicker" name="home_hero_kicker" type="text" value="{{ old('home_hero_kicker', $settings->home_hero_kicker) }}" placeholder="What We Do">
                </div>
                <div class="col-lg-8">
                    <label class="form-label" for="home_hero_title">Title (optional)</label>
                    <input class="form-control" id="home_hero_title" name="home_hero_title" type="text" value="{{ old('home_hero_title', $settings->home_hero_title) }}" placeholder="{{ $settings->site_name ?: config('app.name', 'Codediera') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="home_hero_body">Brief description (optional)</label>
                    <textarea class="form-control" id="home_hero_body" name="home_hero_body" rows="3" placeholder="Briefly describe what your company does">{{ old('home_hero_body', $settings->home_hero_body) }}</textarea>
                </div>
            </div>

            <hr class="my-3">

            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label" for="home_hero_item1_title">Item 1 title</label>
                    <input class="form-control" id="home_hero_item1_title" name="home_hero_item1_title" type="text" value="{{ old('home_hero_item1_title', $settings->home_hero_item1_title) }}" placeholder="Web & Business Software">
                    <label class="form-label mt-2" for="home_hero_item1_body">Item 1 text</label>
                    <input class="form-control" id="home_hero_item1_body" name="home_hero_item1_body" type="text" value="{{ old('home_hero_item1_body', $settings->home_hero_item1_body) }}" placeholder="Company websites, portals, dashboards, and automation.">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="home_hero_item2_title">Item 2 title</label>
                    <input class="form-control" id="home_hero_item2_title" name="home_hero_item2_title" type="text" value="{{ old('home_hero_item2_title', $settings->home_hero_item2_title) }}" placeholder="Mobile & Product Delivery">
                    <label class="form-label mt-2" for="home_hero_item2_body">Item 2 text</label>
                    <input class="form-control" id="home_hero_item2_body" name="home_hero_item2_body" type="text" value="{{ old('home_hero_item2_body', $settings->home_hero_item2_body) }}" placeholder="Apps, setup, access, and ongoing support/renewals.">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="home_hero_item3_title">Item 3 title</label>
                    <input class="form-control" id="home_hero_item3_title" name="home_hero_item3_title" type="text" value="{{ old('home_hero_item3_title', $settings->home_hero_item3_title) }}" placeholder="Digital Skills & Career Support">
                    <label class="form-label mt-2" for="home_hero_item3_body">Item 3 text</label>
                    <input class="form-control" id="home_hero_item3_body" name="home_hero_item3_body" type="text" value="{{ old('home_hero_item3_body', $settings->home_hero_item3_body) }}" placeholder="Training, mentorship, IT placement, and growth.">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label" for="footer_text">Footer About Text</label>
                <input class="form-control" id="footer_text" name="footer_text" type="text" placeholder="We build modern web and mobile solutions…" value="{{ old('footer_text', $settings->footer_text) }}">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label" for="copyright_text">Copyright Text</label>
                <input class="form-control" id="copyright_text" name="copyright_text" type="text" placeholder="© {{ date('Y') }} {{ $settings->site_name ?: config('app.name', 'Codediera') }}. All rights reserved." value="{{ old('copyright_text', $settings->copyright_text) }}">
            </div>
        </div>

        <div class="card card-body mb-3">
            <div class="fw-semibold mb-2">Footer Social Links</div>
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label" for="social_facebook">Facebook URL</label>
                    <input class="form-control" id="social_facebook" name="social_facebook" type="url" placeholder="https://facebook.com/yourpage" value="{{ old('social_facebook', $settings->social_facebook) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="social_instagram">Instagram URL</label>
                    <input class="form-control" id="social_instagram" name="social_instagram" type="url" placeholder="https://instagram.com/yourhandle" value="{{ old('social_instagram', $settings->social_instagram) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="social_twitter">Twitter/X URL</label>
                    <input class="form-control" id="social_twitter" name="social_twitter" type="url" placeholder="https://x.com/yourhandle" value="{{ old('social_twitter', $settings->social_twitter) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="social_linkedin">LinkedIn URL</label>
                    <input class="form-control" id="social_linkedin" name="social_linkedin" type="url" placeholder="https://linkedin.com/company/yourcompany" value="{{ old('social_linkedin', $settings->social_linkedin) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="social_whatsapp">WhatsApp URL</label>
                    <input class="form-control" id="social_whatsapp" name="social_whatsapp" type="url" placeholder="https://wa.me/234XXXXXXXXXX" value="{{ old('social_whatsapp', $settings->social_whatsapp) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="google_review_url">Google Review URL</label>
                    <input class="form-control" id="google_review_url" name="google_review_url" type="url" placeholder="https://g.page/r/your-id/review" value="{{ old('google_review_url', $settings->google_review_url) }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="google_places_api_key">Google Places API Key</label>
                    <input class="form-control" id="google_places_api_key" name="google_places_api_key" type="text" placeholder="AIzaSy..." value="{{ old('google_places_api_key', $settings->google_places_api_key) }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="google_place_id">Google Place ID</label>
                    <input class="form-control" id="google_place_id" name="google_place_id" type="text" placeholder="ChI..." value="{{ old('google_place_id', $settings->google_place_id) }}">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="google_places_ssl_verify" id="google_places_ssl_verify" value="1" {{ old('google_places_ssl_verify', $settings->google_places_ssl_verify ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="google_places_ssl_verify">
                            Verify SSL Certificate (Disable only for local testing if cURL fails)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Logo</div>
                        @if($settings->logo_path)
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo" style="height:40px;width:auto">
                                <div class="form-check">
                                    <input class="form-check-input" id="remove_logo" name="remove_logo" type="checkbox" value="1">
                                    <label class="form-check-label" for="remove_logo">Remove</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <input class="form-control" id="logo" name="logo" type="file" accept="image/*">
                    <div class="text-muted small mt-2">Recommended: PNG/SVG, height ~40px</div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Favicon</div>
                        @if($settings->favicon_path)
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/'.$settings->favicon_path) }}" alt="Favicon" style="height:24px;width:24px">
                                <div class="form-check">
                                    <input class="form-check-input" id="remove_favicon" name="remove_favicon" type="checkbox" value="1">
                                    <label class="form-check-label" for="remove_favicon">Remove</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <input class="form-control" id="favicon" name="favicon" type="file" accept=".ico,image/*">
                    <div class="text-muted small mt-2">Supported: ICO/PNG/SVG</div>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Back</a>
        </div>
    </form>
@endsection
