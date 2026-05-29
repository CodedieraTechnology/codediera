<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleReview;
use Illuminate\Http\Request;

class GoogleReviewController extends Controller
{
    public function index()
    {
        $reviews = GoogleReview::query()->orderByDesc('id')->paginate(20);
        $settings = \App\Models\SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );
        return view('admin.google_reviews.index', compact('reviews', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'google_places_api_key' => ['nullable', 'string', 'max:255'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'google_places_ssl_verify' => ['nullable', 'boolean'],
        ]);

        $settings = \App\Models\SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );

        $settings->fill($data);
        $settings->google_places_ssl_verify = $request->boolean('google_places_ssl_verify');
        $settings->save();
        \Illuminate\Support\Facades\Cache::forget('site_settings.first');

        return redirect()->route('admin.google-reviews.index')->with('status', 'API settings updated successfully.');
    }

    public function testConnection(Request $request)
    {
        $apiKey = $request->input('google_places_api_key');
        $placeId = $request->input('google_place_id');
        $sslVerify = $request->boolean('google_places_ssl_verify', true);

        if (empty($apiKey) || empty($placeId)) {
            return response()->json([
                'success' => false,
                'message' => 'Both API Key and Place ID are required to test the connection.'
            ], 422);
        }

        try {
            $client = \Illuminate\Support\Facades\Http::asJson();
            if (!$sslVerify) {
                $client = $client->withoutVerifying();
            }
            $response = $client->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $apiKey,
                'fields' => 'name,status'
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'HTTP request failed with status ' . $response->status()
                ]);
            }

            $data = $response->json();
            $status = $data['status'] ?? 'UNKNOWN';

            if ($status === 'OK') {
                $name = $data['result']['name'] ?? 'Your Place';
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful! Located: "' . $name . '"'
                ]);
            } else {
                $errorMsg = $data['error_message'] ?? 'Status returned: ' . $status;
                return response()->json([
                    'success' => false,
                    'message' => 'Google API Error: ' . $errorMsg
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleApprove(GoogleReview $review)
    {
        $review->is_approved = !$review->is_approved;
        $review->save();

        return redirect()->route('admin.google-reviews.index')->with('status', 'Review status updated.');
    }

    public function destroy(GoogleReview $review)
    {
        $review->delete();

        return redirect()->route('admin.google-reviews.index')->with('status', 'Review deleted successfully.');
    }
}
