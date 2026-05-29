<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MailSettingsController extends Controller
{
    public function edit()
    {
        $settings = MailSetting::query()->firstOrCreate(['id' => 1]);

        return view('admin.mail_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'host' => ['nullable', 'string', 'max:255'],
            'port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'encryption' => ['nullable', 'in:tls,ssl'],
            'from_address' => ['nullable', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = MailSetting::query()->firstOrCreate(['id' => 1]);
        if (!$request->filled('password')) {
            unset($data['password']);
        }

        if (($data['encryption'] ?? null) === '') {
            $data['encryption'] = null;
        }

        $settings->fill($data);

        if ($request->filled('password')) {
            $settings->password = Crypt::encryptString($request->input('password'));
        }

        $settings->save();

        return redirect()->route('admin.mail-settings.edit')->with('status', 'Email settings updated');
    }
}
