<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\DigitalSkillsItem;
use App\Models\DigitalSkillsRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $items = DigitalSkillsItem::query()
            ->where('instructor_user_id', $user->id)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $ratingsQuery = DigitalSkillsRating::query()
            ->whereIn('digital_skills_item_id', $items->pluck('id')->all());

        $ratingsCount = (int) $ratingsQuery->count();
        $avgRating = $ratingsCount ? round((float) $ratingsQuery->avg('rating'), 1) : null;

        return view('instructor.dashboard', compact('items', 'ratingsCount', 'avgRating'));
    }
}
