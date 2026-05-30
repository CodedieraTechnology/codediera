<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AiSettingsController extends Controller
{
    public function index()
    {
        $settings = AiSetting::query()->orderBy('id')->get();
        return view('admin.ai_settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'provider' => ['required', 'string', 'in:gemini,openai,anthropic,groq,deepseek,mistral,cohere,perplexity'],
            'api_key' => ['required', 'string', 'max:1000'],
            'model' => ['required', 'string', 'max:255'],
            'ssl_verify' => ['nullable', 'boolean'],
        ]);

        $enabled = $request->boolean('enabled');

        $settings = new AiSetting();
        $settings->enabled = $enabled;
        $settings->provider = $data['provider'];
        $settings->model = $data['model'];
        $settings->ssl_verify = $request->boolean('ssl_verify');
        $settings->api_key = Crypt::encryptString($data['api_key']);
        $settings->save();

        if ($enabled) {
            // Disable all other configurations
            AiSetting::query()->where('id', '!=', $settings->id)->update(['enabled' => false]);
        }

        Cache::forget('ai_settings.first');

        return redirect()->route('admin.ai-settings.index')->with('status', 'AI Configuration added successfully.');
    }

    public function update(Request $request, AiSetting $aiSetting = null)
    {
        if (!$aiSetting || !$aiSetting->exists) {
            $id = $request->input('config_id') ?: $request->input('id');
            $aiSetting = AiSetting::find($id);
        }

        if (!$aiSetting) {
            return redirect()->route('admin.ai-settings.index')->with('error', 'AI Configuration not found.');
        }

        $data = $request->validate([
            'enabled' => ['nullable'],
            'provider' => ['required', 'string', 'in:gemini,openai,anthropic,groq,deepseek,mistral,cohere,perplexity'],
            'api_key' => ['nullable', 'string', 'max:1000'],
            'model' => ['required', 'string', 'max:255'],
            'ssl_verify' => ['nullable', 'boolean'],
        ]);

        $enabled = $request->boolean('enabled');

        $aiSetting->enabled = $enabled;
        $aiSetting->provider = $data['provider'];
        $aiSetting->model = $data['model'];
        $aiSetting->ssl_verify = $request->boolean('ssl_verify');

        if ($request->filled('api_key')) {
            $aiSetting->api_key = Crypt::encryptString($request->input('api_key'));
        }

        $aiSetting->save();

        if ($enabled) {
            // Disable all other configurations
            AiSetting::query()->where('id', '!=', $aiSetting->id)->update(['enabled' => false]);
        }

        Cache::forget('ai_settings.first');

        return redirect()->route('admin.ai-settings.index')->with('status', 'AI Configuration updated successfully.');
    }

    public function destroy(AiSetting $aiSetting)
    {
        $aiSetting->delete();
        Cache::forget('ai_settings.first');
        return redirect()->route('admin.ai-settings.index')->with('status', 'AI Configuration deleted successfully.');
    }

    public function testConnection(Request $request)
    {
        $data = $request->validate([
            'provider' => ['required', 'string', 'in:gemini,openai,anthropic,groq,deepseek,mistral,cohere,perplexity'],
            'model' => ['required', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:1000'],
            'ssl_verify' => ['nullable', 'boolean'],
            'id' => ['nullable', 'integer'],
        ]);

        $provider = $data['provider'];
        $model = $data['model'];
        $apiKey = $data['api_key'];
        $settingsId = $request->input('id');

        $settings = null;
        if ($settingsId) {
            $settings = AiSetting::find($settingsId);
        }

        // Fall back to saved encrypted API key if input is empty
        if (empty($apiKey) && $settings) {
            if ($settings->api_key) {
                try {
                    $apiKey = Crypt::decryptString($settings->api_key);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to decrypt the saved API key. Please re-enter your API key.'
                    ], 422);
                }
            }
        }

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key is required to test the connection.'
            ], 422);
        }

        $sslVerify = $settings ? $settings->ssl_verify : true;
        if ($request->has('ssl_verify')) {
            $sslVerify = $request->boolean('ssl_verify');
        }

        try {
            $client = Http::asJson();
            if (!$sslVerify) {
                $client = $client->withoutVerifying();
            }

            $response = null;

            switch ($provider) {
                case 'gemini':
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
                    $response = $client->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => 'Test connection. Respond with "OK".']
                                ]
                            ]
                        ]
                    ]);
                    break;

                case 'openai':
                    $url = 'https://api.openai.com/v1/chat/completions';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                case 'anthropic':
                    $url = 'https://api.anthropic.com/v1/messages';
                    $response = $client->withHeaders([
                        'x-api-key' => $apiKey,
                        'anthropic-version' => '2023-06-01',
                        'content-type' => 'application/json',
                    ])->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                case 'groq':
                    $url = 'https://api.groq.com/openai/v1/chat/completions';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                case 'deepseek':
                    $url = 'https://api.deepseek.com/chat/completions';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                case 'mistral':
                    $url = 'https://api.mistral.ai/v1/chat/completions';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                case 'cohere':
                    $url = 'https://api.cohere.ai/v1/chat';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'message' => 'Hello'
                    ]);
                    break;

                case 'perplexity':
                    $url = 'https://api.perplexity.ai/chat/completions';
                    $response = $client->withToken($apiKey)->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Hello']
                        ],
                        'max_tokens' => 5
                    ]);
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Unsupported AI provider.'
                    ], 422);
            }

            if ($response && $response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful! The API is responding correctly.'
                ]);
            }

            $errorMsg = 'API request failed with status ' . ($response ? $response->status() : 'unknown');
            if ($response) {
                $errorData = $response->json();
                if (!empty($errorData)) {
                    if (isset($errorData['error']['message'])) {
                        $errorMsg = $errorData['error']['message'];
                    } elseif (isset($errorData['message'])) {
                        $errorMsg = $errorData['message'];
                    }
                } else {
                    $body = $response->body();
                    if (!empty($body)) {
                        $errorMsg .= ': ' . substr($body, 0, 200);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'API Error: ' . $errorMsg
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
