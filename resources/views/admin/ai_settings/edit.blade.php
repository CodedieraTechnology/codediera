@extends('admin.layout')

@section('title', 'AI Settings')

@section('content')
    <h1 class="h3 mb-3">AI Settings</h1>

    <form method="post" action="{{ route('admin.ai-settings.update') }}" class="card card-body shadow-sm">
        @csrf
        @method('PUT')

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" role="switch" id="enabled" name="enabled" value="1" {{ old('enabled', $settings->enabled) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="enabled">Enable AI Integration</label>
            <div class="form-text">Toggle to enable or disable AI-powered content generation and assistive features globally.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="provider">AI Provider</label>
                <select class="form-select" id="provider" name="provider">
                    <option value="gemini" {{ old('provider', $settings->provider) === 'gemini' ? 'selected' : '' }}>Google Gemini (Recommended)</option>
                    <option value="openai" {{ old('provider', $settings->provider) === 'openai' ? 'selected' : '' }}>OpenAI</option>
                    <option value="anthropic" {{ old('provider', $settings->provider) === 'anthropic' ? 'selected' : '' }}>Anthropic Claude</option>
                    <option value="groq" {{ old('provider', $settings->provider) === 'groq' ? 'selected' : '' }}>Groq</option>
                    <option value="deepseek" {{ old('provider', $settings->provider) === 'deepseek' ? 'selected' : '' }}>DeepSeek</option>
                    <option value="mistral" {{ old('provider', $settings->provider) === 'mistral' ? 'selected' : '' }}>Mistral AI</option>
                    <option value="cohere" {{ old('provider', $settings->provider) === 'cohere' ? 'selected' : '' }}>Cohere</option>
                    <option value="perplexity" {{ old('provider', $settings->provider) === 'perplexity' ? 'selected' : '' }}>Perplexity</option>
                </select>
                <div class="form-text">Select the artificial intelligence provider for integration.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="model">Model Name</label>
                <input class="form-control" id="model" name="model" type="text" value="{{ old('model', $settings->model) }}" placeholder="gemini-1.5-flash" required>
                <div class="form-text">Specify the exact model version to use (e.g., <code>gemini-1.5-flash</code>, <code>gpt-4o</code>, or <code>claude-3-5-sonnet</code>).</div>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="api_key">API Key</label>
            <div class="input-group mb-1">
                <input class="form-control" id="api_key" name="api_key" type="password" placeholder="{{ $settings->api_key ? '•••••••••••••••••••••••••••••••• (Leave blank to keep existing key)' : 'Enter your API key' }}">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="api_key">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </button>
            </div>
            <div class="form-text">Your API key is securely encrypted before being stored in the database.</div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary px-4" type="submit">Save Settings</button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var providerSelect = document.getElementById('provider');
        var modelInput = document.getElementById('model');

        var defaultModels = {
            gemini: 'gemini-1.5-flash',
            openai: 'gpt-4o',
            anthropic: 'claude-3-5-sonnet-20241022',
            groq: 'llama-3.3-70b-versatile',
            deepseek: 'deepseek-chat',
            mistral: 'mistral-large-latest',
            cohere: 'command-r-plus',
            perplexity: 'sonar-reasoning'
        };

        providerSelect.addEventListener('change', function () {
            var selected = providerSelect.value;
            if (defaultModels[selected]) {
                modelInput.value = defaultModels[selected];
            }
        });

        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                var targetId = button.getAttribute('data-target');
                var input = document.getElementById(targetId);
                if (input) {
                    var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    if (type === 'password') {
                        button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
                    } else {
                        button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.831.518l-1.02-1.02A8.951 8.951 0 0 1 8 1c5.279 0 9 5.148 9 5.5 0 .257-.461.875-1.042 1.486L13.359 11.24zM6 8a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"/><path d="M11.612 9.564 11.24 9.192c.224-.363.372-.78.372-1.228a4 4 0 1 0-7.828 1.18L2.23 7.585A8.997 8.997 0 0 1 8 2.25c4.717 0 8 4.75 8 5 0 .07-.154.385-.457.812l-.93-.93z"/><path d="M5.525 7.646 1.354 3.475a.5.5 0 1 0-.708.708l1.35 1.35C.851 6.586 0 8 0 8s3 5.5 8 5.5a9.06 9.06 0 0 0 4.14-.949l2.136 2.136a.5.5 0 0 0 .707-.707l-2.14-2.14L5.525 7.646zm2.463 3.65c-.328 0-.648-.067-.946-.188L8.71 9.44c.484.28 1.05.3 1.29.083l-.707-.707c-.453-.138-.813-.498-.951-.951l-.707-.707c-.014.24-.035.806.245 1.29.138.224.37.37.594.37zM4.77 7.07l-.768-.768C3.62 6.67 3.5 7 3.5 7.5c0 1.24 1.01 2.25 2.25 2.25.5 0 .83-.12.98-.182l-.666-.666c-.156.064-.325.098-.564.098a1.25 1.25 0 0 1-1.25-1.25c0-.239.034-.408.098-.564L4.77 7.07z"/></svg>`;
                    }
                }
            });
        });
    });
</script>
@endsection
