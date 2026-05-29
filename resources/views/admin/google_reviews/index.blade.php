@extends('admin.layout')

@section('title', 'Google Reviews')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Google Reviews</h1>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.292-.16c.764-.415 1.6.42 1.185 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.292c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.185-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                </svg>
                Google Places API Integration Settings
            </h2>
            <p class="text-muted small mb-4">
                Configure your Places API credentials to automatically pull real reviews from Google maps and showcase them on your website's home page.
            </p>
            <form method="post" action="{{ route('admin.google-reviews.settings.update') }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" for="google_places_api_key">Google Places API Key</label>
                        <input class="form-control" id="google_places_api_key" name="google_places_api_key" type="text" placeholder="AIzaSy..." value="{{ old('google_places_api_key', $settings->google_places_api_key) }}">
                        <div class="form-text small text-muted">Retrieve from Google Cloud Console with Places API enabled.</div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" for="google_place_id">Google Place ID</label>
                        <input class="form-control" id="google_place_id" name="google_place_id" type="text" placeholder="ChI..." value="{{ old('google_place_id', $settings->google_place_id) }}">
                        <div class="form-text small text-muted">Use the Google Place ID Finder to get your business place ID.</div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="google_places_ssl_verify" id="google_places_ssl_verify" value="1" {{ old('google_places_ssl_verify', $settings->google_places_ssl_verify ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="google_places_ssl_verify">
                                Verify SSL Certificate (Disable only for local testing if cURL fails)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column gap-2 justify-content-end">
                        <button class="btn btn-outline-info w-100 py-2" type="button" id="btnTestConnection">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="testConnectionSpinner" role="status" aria-hidden="true"></span>
                            Test Connection
                        </button>
                        <button class="btn btn-primary w-100 py-2" type="submit">Save Settings</button>
                    </div>
                </div>

                <div id="connectionTestAlert" class="alert d-none mt-3"></div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Author</th>
                <th>Rating</th>
                <th>Review Text</th>
                <th>Source Link</th>
                <th>Approved</th>
                <th>Date</th>
                <th class="text-end" style="width: 180px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reviews as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <div class="text-muted small">{{ $item->reviewer_title ?: 'Google Reviewer' }}</div>
                    </td>
                    <td>
                        <span class="text-warning fw-bold">
                            @for($i = 0; $i < $item->rating; $i++)★@endfor
                        </span>
                    </td>
                    <td class="text-muted small" style="max-width: 320px;">
                        {{ \Illuminate\Support\Str::limit($item->review_text, 140) }}
                    </td>
                    <td>
                        @if($item->google_review_url)
                            <a href="{{ $item->google_review_url }}" target="_blank" rel="noopener" class="small">Open Link</a>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $item->is_approved ? 'bg-success' : 'bg-secondary' }}">
                            {{ $item->is_approved ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="small">{{ $item->created_at->format('M j, Y') }}</td>
                    <td class="text-end">
                        <form method="post" action="{{ route('admin.google-reviews.approve', $item) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-sm {{ $item->is_approved ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                {{ $item->is_approved ? 'Reject' : 'Approve' }}
                            </button>
                        </form>
                        <form method="post" action="{{ route('admin.google-reviews.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No reviews submitted yet</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $reviews->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btnTest = document.getElementById('btnTestConnection');
            if (!btnTest) return;

            var spinner = document.getElementById('testConnectionSpinner');
            var alertEl = document.getElementById('connectionTestAlert');
            var keyInput = document.getElementById('google_places_api_key');
            var placeInput = document.getElementById('google_place_id');
            var sslVerifyInput = document.getElementById('google_places_ssl_verify');

            btnTest.addEventListener('click', function() {
                // Clear state
                alertEl.className = 'alert d-none mt-3';
                alertEl.textContent = '';

                var key = keyInput.value.trim();
                var place = placeInput.value.trim();
                var sslVerify = sslVerifyInput ? sslVerifyInput.checked : true;

                if (!key || !place) {
                    alertEl.className = 'alert alert-warning mt-3';
                    alertEl.textContent = 'Please fill out both the API Key and Place ID fields first.';
                    return;
                }

                // Show spinner
                btnTest.disabled = true;
                spinner.classList.remove('d-none');

                fetch('{{ route("admin.google-reviews.test-connection") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        google_places_api_key: key,
                        google_place_id: place,
                        google_places_ssl_verify: sslVerify ? 1 : 0
                    })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        if (!res.ok) {
                            throw data;
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    alertEl.className = 'alert alert-success mt-3';
                    alertEl.textContent = data.message || 'Connection successful!';
                })
                .catch(function(err) {
                    alertEl.className = 'alert alert-danger mt-3';
                    alertEl.textContent = (err && err.message) || 'Connection failed. Please check your credentials and try again.';
                })
                .finally(function() {
                    btnTest.disabled = false;
                    spinner.classList.add('d-none');
                });
            });
        });
    </script>
@endsection
