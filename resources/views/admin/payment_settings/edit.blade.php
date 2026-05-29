@extends('admin.layout')

@section('title', 'Payment Settings')

@section('content')
    <h1 class="h3 mb-3">Payment Settings</h1>

    <form method="post" action="{{ route('admin.payment-settings.update') }}" class="card card-body">
        @csrf
        @method('PUT')

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" id="paystack_enabled" name="paystack_enabled" type="checkbox" value="1" @checked(old('paystack_enabled', $settings->paystack_enabled))>
            <label class="form-check-label" for="paystack_enabled">Enable Paystack</label>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="paystack_public_key">Paystack Public Key</label>
                <input class="form-control @error('paystack_public_key') is-invalid @enderror" id="paystack_public_key" name="paystack_public_key" type="text" value="{{ old('paystack_public_key', $settings->paystack_public_key) }}">
                @error('paystack_public_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="paystack_secret_key">Paystack Secret Key</label>
                <div class="input-group">
                    <input class="form-control @error('paystack_secret_key') is-invalid @enderror" id="paystack_secret_key" name="paystack_secret_key" type="password" value="" placeholder="Leave blank to keep existing key">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="paystack_secret_key">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                    @error('paystack_secret_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label" for="trial_days">Trial Days</label>
                <input class="form-control @error('trial_days') is-invalid @enderror" id="trial_days" name="trial_days" type="number" min="0" max="30" value="{{ old('trial_days', $settings->trial_days) }}">
                @error('trial_days')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="paystack_auth_amount_naira">Card Authorization Charge (₦)</label>
                <input class="form-control @error('paystack_auth_amount_naira') is-invalid @enderror" id="paystack_auth_amount_naira" name="paystack_auth_amount_naira" type="number" min="0" value="{{ old('paystack_auth_amount_naira', $authAmountNaira) }}">
                @error('paystack_auth_amount_naira')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Save</button>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

