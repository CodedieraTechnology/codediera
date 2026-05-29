<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\HomeCta;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\GoogleReview;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $services = Service::query()
            ->where('is_active', true)
            ->with('images')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $projects = Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $team = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $ctas = HomeCta::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        $contactSettings = ContactSetting::query()->first();

        $reviews = GoogleReview::query()
            ->where('is_approved', true)
            ->orderByDesc('id')
            ->get();

        return view('home', compact('sliders', 'services', 'projects', 'team', 'ctas', 'contactSettings', 'reviews'));
    }

    public function storeReview(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000',
            'google_review_url' => 'nullable|url|max:255',
        ]);

        $gradients = [
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
            'linear-gradient(135deg, #ff8008 0%, #ffc837 100%)',
            'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            'linear-gradient(135deg, #fc00ff 0%, #00dbde 100%)',
            'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        ];
        $avatarBg = $gradients[array_rand($gradients)];

        $review = GoogleReview::create([
            'name' => $validated['name'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'reviewer_title' => 'Google Reviewer',
            'avatar_bg' => $avatarBg,
            'google_review_url' => $validated['google_review_url'] ?? null,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $review
        ]);
    }
}
