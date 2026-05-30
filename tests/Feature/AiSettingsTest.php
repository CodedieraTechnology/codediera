<?php

namespace Tests\Feature;

use App\Models\AiSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminUser()
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    protected function getRegularUser()
    {
        return User::factory()->create([
            'is_admin' => false,
        ]);
    }

    public function test_guest_cannot_access_ai_settings_test_connection()
    {
        $response = $this->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => 'test-key',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_ai_settings_test_connection()
    {
        $user = $this->getRegularUser();

        $response = $this->actingAs($user)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => 'test-key',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_validation_errors()
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'invalid-provider',
            'model' => '',
            'api_key' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['provider', 'model']);
    }

    public function test_test_connection_successful_gemini()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['success' => true], 200),
        ]);

        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => 'valid-gemini-key',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Connection test successful! The API is responding correctly.'
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com')
                && str_contains($request->url(), 'key=valid-gemini-key')
                && $request->method() === 'POST';
        });
    }

    public function test_test_connection_successful_openai()
    {
        Http::fake([
            'api.openai.com/*' => Http::response(['success' => true], 200),
        ]);

        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'openai',
            'model' => 'gpt-4o',
            'api_key' => 'valid-openai-key',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Connection test successful! The API is responding correctly.'
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.openai.com')
                && $request->hasHeader('Authorization', 'Bearer valid-openai-key')
                && $request->method() === 'POST';
        });
    }

    public function test_test_connection_falls_back_to_saved_decrypted_key()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['success' => true], 200),
        ]);

        // Save a mock API key in the database
        $settings = AiSetting::query()->firstOrCreate(['id' => 1]);
        $settings->provider = 'gemini';
        $settings->model = 'gemini-2.0-flash';
        $settings->api_key = Crypt::encryptString('saved-gemini-key');
        $settings->save();

        $admin = $this->getAdminUser();

        // Pass an empty API key so it falls back to the database key
        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => '',
            'id' => $settings->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Connection test successful! The API is responding correctly.'
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'key=saved-gemini-key');
        });
    }

    public function test_test_connection_fails_without_any_key()
    {
        // Ensure no settings exist or key is null
        AiSetting::query()->where('id', 1)->delete();

        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'API Key is required to test the connection.'
        ]);
    }

    public function test_test_connection_error_handling_from_api()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'message' => 'API key not valid. Please pass a valid API key.'
                ]
            ], 400),
        ]);

        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => 'invalid-gemini-key',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'API Error: API key not valid. Please pass a valid API key.'
        ]);
    }

    public function test_test_connection_without_ssl_verification()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['success' => true], 200),
        ]);

        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-settings.test'), [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'api_key' => 'valid-gemini-key',
            'ssl_verify' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Connection test successful! The API is responding correctly.'
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com')
                && str_contains($request->url(), 'key=valid-gemini-key');
        });
    }

    public function test_update_ai_settings_saves_ssl_verify()
    {
        $settings = AiSetting::query()->firstOrCreate(['id' => 1]);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->put(route('admin.ai-settings.update', $settings), [
            'provider' => 'openai',
            'model' => 'gpt-4o',
            'api_key' => 'new-key',
            'ssl_verify' => '0',
        ]);

        $response->assertRedirect(route('admin.ai-settings.index'));
        $this->assertDatabaseHas('ai_settings', [
            'provider' => 'openai',
            'model' => 'gpt-4o',
            'ssl_verify' => false,
        ]);
    }

    public function test_update_ai_settings_without_id_in_route_parameter()
    {
        $settings = AiSetting::query()->firstOrCreate(['id' => 1]);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->put(route('admin.ai-settings.update'), [
            'config_id' => $settings->id,
            'provider' => 'openai',
            'model' => 'gpt-4o-mini',
            'api_key' => 'another-key',
            'ssl_verify' => '1',
        ]);

        $response->assertRedirect(route('admin.ai-settings.index'));
        $this->assertDatabaseHas('ai_settings', [
            'provider' => 'openai',
            'model' => 'gpt-4o-mini',
            'ssl_verify' => true,
        ]);
    }

    public function test_admin_can_access_ai_settings_index_and_list()
    {
        $admin = $this->getAdminUser();
        AiSetting::query()->firstOrCreate(['id' => 1], [
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ai-settings.index'));

        $response->assertStatus(200);
        $response->assertSee('gemini-2.0-flash');
    }

    public function test_admin_can_store_ai_setting()
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.ai-settings.store'), [
            'provider' => 'anthropic',
            'model' => 'claude-3-5-sonnet',
            'api_key' => 'secret-anthropic-key',
            'ssl_verify' => '1',
            'enabled' => '1',
        ]);

        $response->assertRedirect(route('admin.ai-settings.index'));
        $this->assertDatabaseHas('ai_settings', [
            'provider' => 'anthropic',
            'model' => 'claude-3-5-sonnet',
            'ssl_verify' => true,
            'enabled' => true,
        ]);
    }

    public function test_admin_can_delete_ai_setting()
    {
        $settings = AiSetting::query()->firstOrCreate(['id' => 1]);
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->delete(route('admin.ai-settings.destroy', $settings));

        $response->assertRedirect(route('admin.ai-settings.index'));
        $this->assertDatabaseMissing('ai_settings', [
            'id' => $settings->id,
        ]);
    }
}
