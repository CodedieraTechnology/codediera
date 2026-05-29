<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class AiSettingsController extends Controller
{
    public function edit()
    {
        $settings = AiSetting::query()->firstOrCreate(['id' => 1], [
            'enabled' => false,
            'provider' => 'gemini',
            'model' => 'gemini-1.5-flash',
        ]);

        return view('admin.ai_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'provider' => ['required', 'string', 'in:gemini,openai,anthropic,groq,deepseek,mistral,cohere,perplexity'],
            'api_key' => ['nullable', 'string', 'max:1000'],
            'model' => ['required', 'string', 'max:255'],
        ]);

        $settings = AiSetting::query()->firstOrCreate(['id' => 1]);
        $settings->enabled = $request->boolean('enabled');
        $settings->provider = $data['provider'];
        $settings->model = $data['model'];

        if ($request->filled('api_key')) {
            $settings->api_key = Crypt::encryptString($request->input('api_key'));
        }

        $settings->save();

        Cache::forget('ai_settings.first');

        return redirect()->route('admin.ai-settings.edit')->with('status', 'AI settings updated');
    }
}
