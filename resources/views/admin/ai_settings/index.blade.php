@extends('admin.layout')

@section('title', 'AI Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">AI Settings</h1>
        <button class="btn btn-primary d-flex align-items-center gap-2" type="button" id="btnAddConfig" data-bs-toggle="modal" data-bs-target="#aiConfigModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
            </svg>
            Add AI Configuration
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Provider</th>
                            <th>Model Name</th>
                            <th>SSL Verification</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <div class="fw-semibold text-capitalize">{{ $item->provider }}</div>
                                </td>
                                <td><code>{{ $item->model }}</code></td>
                                <td>
                                    <span class="badge {{ $item->ssl_verify ? 'bg-success' : 'bg-warning' }}">
                                        {{ $item->ssl_verify ? 'Verify SSL' : 'Bypass SSL' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->enabled ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->enabled ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary btn-edit-config" type="button"
                                        data-id="{{ $item->id }}"
                                        data-provider="{{ $item->provider }}"
                                        data-model="{{ $item->model }}"
                                        data-ssl-verify="{{ $item->ssl_verify ? 1 : 0 }}"
                                        data-enabled="{{ $item->enabled ? 1 : 0 }}"
                                        data-has-key="{{ $item->api_key ? 1 : 0 }}"
                                        data-bs-toggle="modal" data-bs-target="#aiConfigModal">
                                        Edit
                                    </button>
                                    <form method="post" action="{{ route('admin.ai-settings.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No AI configurations added yet. Click "Add AI Configuration" to begin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Add/Edit AI Config -->
    <div class="modal fade" id="aiConfigModal" tabindex="-1" aria-labelledby="aiConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="aiConfigForm" method="post" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="config_id" id="config_id" value="">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="aiConfigModalLabel">Add AI Configuration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="enabled" name="enabled" value="1">
                            <label class="form-check-label fw-semibold" for="enabled">Enable this Configuration</label>
                            <div class="form-text">Toggle to make this the active AI integration configuration globally. (Activating this will automatically deactivate other configurations).</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="provider">AI Provider</label>
                                <select class="form-select" id="provider" name="provider">
                                    <option value="gemini">Google Gemini (Recommended)</option>
                                    <option value="openai">OpenAI</option>
                                    <option value="anthropic">Anthropic Claude</option>
                                    <option value="groq">Groq</option>
                                    <option value="deepseek">DeepSeek</option>
                                    <option value="mistral">Mistral AI</option>
                                    <option value="cohere">Cohere</option>
                                    <option value="perplexity">Perplexity</option>
                                </select>
                                <div class="form-text">Select the artificial intelligence provider.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="model">Model Name</label>
                                <input class="form-control" id="model" name="model" type="text" placeholder="gemini-2.0-flash" required>
                                <div class="form-text">Specify the exact model version to use (e.g., <code>gemini-2.0-flash</code>, <code>gpt-4o</code>, or <code>claude-3-5-sonnet-20241022</code>).</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="api_key">API Key</label>
                            <div class="input-group mb-1">
                                <input class="form-control" id="api_key" name="api_key" type="password" placeholder="Enter your API key">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="api_key">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="form-text">Your API key is securely encrypted before being stored.</div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="ssl_verify" id="ssl_verify" value="1" checked>
                                <label class="form-check-label fw-semibold" for="ssl_verify">
                                    Verify SSL Certificate
                                </label>
                                <div class="form-text">Disable only for local development/testing environment if outgoing API calls fail due to cURL/SSL certificate configuration errors.</div>
                            </div>
                        </div>

                        <div id="connectionTestAlert" class="alert d-none mt-3"></div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button class="btn btn-outline-info" type="button" id="btnTestConnection">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="testConnectionSpinner" role="status" aria-hidden="true"></span>
                            Test Connection
                        </button>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btnSaveConfig">Save Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var providerSelect = document.getElementById('provider');
        var modelInput = document.getElementById('model');
        var modalTitle = document.getElementById('aiConfigModalLabel');
        var form = document.getElementById('aiConfigForm');
        var formMethod = document.getElementById('formMethod');
        var configIdInput = document.getElementById('config_id');
        var enabledInput = document.getElementById('enabled');
        var apiKeyInput = document.getElementById('api_key');
        var sslVerifyInput = document.getElementById('ssl_verify');
        var btnSave = document.getElementById('btnSaveConfig');

        var defaultModels = {
            gemini: 'gemini-2.0-flash',
            openai: 'gpt-4o',
            anthropic: 'claude-3-5-sonnet-20241022',
            groq: 'llama-3.3-70b-versatile',
            deepseek: 'deepseek-chat',
            mistral: 'mistral-large-latest',
            cohere: 'command-r-plus',
            perplexity: 'sonar-reasoning'
        };

        // Track if we are in edit mode to prevent changing model name when provider changes
        var isEditMode = false;

        providerSelect.addEventListener('change', function () {
            if (!isEditMode) {
                var selected = providerSelect.value;
                if (defaultModels[selected]) {
                    modelInput.value = defaultModels[selected];
                }
            }
        });

        // Add Configuration Modal Init
        document.getElementById('btnAddConfig').addEventListener('click', function () {
            isEditMode = false;
            modalTitle.textContent = 'Add AI Configuration';
            form.action = '{{ route("admin.ai-settings.store") }}';
            formMethod.value = 'POST';
            configIdInput.value = '';
            enabledInput.checked = false;
            providerSelect.value = 'gemini';
            modelInput.value = defaultModels.gemini;
            apiKeyInput.value = '';
            apiKeyInput.setAttribute('required', 'required');
            apiKeyInput.placeholder = 'Enter your API key';
            sslVerifyInput.checked = true;
            btnSave.textContent = 'Save Settings';
            document.getElementById('connectionTestAlert').className = 'alert d-none mt-3';
            document.getElementById('connectionTestAlert').textContent = '';
        });

        // Edit Configuration Modal Init
        document.querySelectorAll('.btn-edit-config').forEach(function (button) {
            button.addEventListener('click', function () {
                isEditMode = true;
                modalTitle.textContent = 'Edit AI Configuration';

                var id = button.getAttribute('data-id');
                var provider = button.getAttribute('data-provider');
                var model = button.getAttribute('data-model');
                var sslVerify = button.getAttribute('data-ssl-verify') === '1';
                var enabled = button.getAttribute('data-enabled') === '1';
                var hasKey = button.getAttribute('data-has-key') === '1';

                form.action = '/admin/ai-settings/' + id;
                formMethod.value = 'PUT';
                configIdInput.value = id;
                enabledInput.checked = enabled;
                providerSelect.value = provider;
                modelInput.value = model;
                apiKeyInput.value = '';
                apiKeyInput.removeAttribute('required');

                if (hasKey) {
                    apiKeyInput.placeholder = '•••••••••••••••••••••••••••••••• (Leave blank to keep existing key)';
                } else {
                    apiKeyInput.placeholder = 'Enter your API key';
                }

                sslVerifyInput.checked = sslVerify;
                btnSave.textContent = 'Update Settings';
                document.getElementById('connectionTestAlert').className = 'alert d-none mt-3';
                document.getElementById('connectionTestAlert').textContent = '';
            });
        });

        // Password visibility toggler
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

        // AI Connection Test Logic
        var btnTest = document.getElementById('btnTestConnection');
        var spinner = document.getElementById('testConnectionSpinner');
        var alertEl = document.getElementById('connectionTestAlert');

        if (btnTest) {
            btnTest.addEventListener('click', function() {
                alertEl.className = 'alert d-none mt-3';
                alertEl.textContent = '';

                var id = configIdInput.value;
                var key = apiKeyInput.value.trim();
                var provider = providerSelect.value;
                var model = modelInput.value.trim();
                var sslVerify = sslVerifyInput.checked;
                
                var hasKeyPlaceholder = apiKeyInput.placeholder.indexOf('••••') !== -1;

                if (!key && !hasKeyPlaceholder) {
                    alertEl.className = 'alert alert-warning mt-3';
                    alertEl.textContent = 'Please enter an API Key first.';
                    return;
                }

                if (!model) {
                    alertEl.className = 'alert alert-warning mt-3';
                    alertEl.textContent = 'Please specify a Model Name first.';
                    return;
                }

                btnTest.disabled = true;
                spinner.classList.remove('d-none');

                fetch('{{ route("admin.ai-settings.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        provider: provider,
                        model: model,
                        api_key: key,
                        ssl_verify: sslVerify ? 1 : 0
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
        }
    });
</script>
@endsection
