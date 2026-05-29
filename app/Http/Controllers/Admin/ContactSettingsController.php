<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactSettingsController extends Controller
{
    public function edit()
    {
        $settings = ContactSetting::query()->firstOrCreate(['id' => 1], ['heading' => 'Contact Us']);

        return view('admin.contact_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'map_embed_url' => ['nullable', 'string'],
        ]);

        $settings = ContactSetting::query()->firstOrCreate(['id' => 1], ['heading' => 'Contact Us']);
        $settings->fill($data);
        $settings->save();
        Cache::forget('contact_settings.first');

        return redirect()->route('admin.contact-settings.edit')->with('status', 'Contact settings updated');
    }
}
