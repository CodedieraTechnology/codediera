<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PaymentSettingsController extends Controller
{
    public function edit()
    {
        $settings = PaymentSetting::query()->firstOrCreate(['id' => 1], [
            'paystack_enabled' => false,
            'trial_days' => 3,
            'paystack_auth_amount_kobo' => 10000,
        ]);

        $authAmountNaira = (int) round(((int) $settings->paystack_auth_amount_kobo) / 100);

        return view('admin.payment_settings.edit', compact('settings', 'authAmountNaira'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'paystack_enabled' => ['nullable'],
            'paystack_public_key' => ['nullable', 'string', 'max:255'],
            'paystack_secret_key' => ['nullable', 'string', 'max:255'],
            'trial_days' => ['nullable', 'integer', 'min:0', 'max:30'],
            'paystack_auth_amount_naira' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        $settings = PaymentSetting::query()->firstOrCreate(['id' => 1], [
            'paystack_enabled' => false,
            'trial_days' => 3,
            'paystack_auth_amount_kobo' => 10000,
        ]);

        $settings->paystack_enabled = $request->boolean('paystack_enabled');
        $settings->paystack_public_key = $data['paystack_public_key'] ?? null;

        if (array_key_exists('trial_days', $data) && $data['trial_days'] !== null) {
            $settings->trial_days = (int) $data['trial_days'];
        }

        if (array_key_exists('paystack_auth_amount_naira', $data) && $data['paystack_auth_amount_naira'] !== null) {
            $settings->paystack_auth_amount_kobo = (int) $data['paystack_auth_amount_naira'] * 100;
        }

        if ($request->filled('paystack_secret_key')) {
            $settings->paystack_secret_key = Crypt::encryptString($request->input('paystack_secret_key'));
        }

        $settings->save();

        return redirect()->route('admin.payment-settings.edit')->with('status', 'Payment settings updated');
    }
}

