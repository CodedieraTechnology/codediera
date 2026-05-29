<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );

        return view('admin.site_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'heading_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'home_hero_kicker' => ['nullable', 'string', 'max:50'],
            'home_hero_title' => ['nullable', 'string', 'max:255'],
            'home_hero_body' => ['nullable', 'string', 'max:600'],
            'home_hero_item1_title' => ['nullable', 'string', 'max:120'],
            'home_hero_item1_body' => ['nullable', 'string', 'max:255'],
            'home_hero_item2_title' => ['nullable', 'string', 'max:120'],
            'home_hero_item2_body' => ['nullable', 'string', 'max:255'],
            'home_hero_item3_title' => ['nullable', 'string', 'max:120'],
            'home_hero_item3_body' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_whatsapp' => ['nullable', 'url', 'max:255'],
            'google_review_url' => ['nullable', 'url', 'max:255'],
            'google_places_api_key' => ['nullable', 'string', 'max:255'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'google_places_ssl_verify' => ['nullable', 'boolean'],

            'logo' => ['nullable', 'image', 'max:4096'],
            'favicon' => ['nullable', 'file', 'max:1024', 'mimes:ico,png,svg,jpg,jpeg'],

            'remove_logo' => ['nullable'],
            'remove_favicon' => ['nullable'],
        ]);

        $settings = SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );

        $settings->fill($data);
        $settings->google_places_ssl_verify = $request->boolean('google_places_ssl_verify');

        if ($request->boolean('remove_logo') && $settings->logo_path) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->logo_path = null;
        }

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('settings', 'public');
        }

        if ($request->boolean('remove_favicon') && $settings->favicon_path) {
            Storage::disk('public')->delete($settings->favicon_path);
            $settings->favicon_path = null;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $request->file('favicon')->store('settings', 'public');
        }

        $settings->save();
        Cache::forget('site_settings.first');

        return redirect()->route('admin.site-settings.edit')->with('status', 'Site settings updated');
    }
}
