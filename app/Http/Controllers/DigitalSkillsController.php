<?php

namespace App\Http\Controllers;

use App\Models\DigitalSkillsEnrollment;
use App\Models\DigitalSkillsItem;
use App\Models\DigitalSkillsLesson;
use App\Models\DigitalSkillsRating;
use App\Models\HomeCta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DigitalSkillsController extends Controller
{
    public function index()
    {
        $cta = HomeCta::query()->where('slug', 'get_digital_skills')->first();

        $items = DigitalSkillsItem::query()
            ->where('is_active', true)
            ->with(['instructor:id,name'])
            ->withCount([
                'lessons as courses_count' => function ($q) {
                    $q->where('is_active', true)->where('is_preview', false);
                },
            ])
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $instructorRatings = [];
        $instructorIds = $items->pluck('instructor_user_id')->filter()->unique()->values()->all();
        if (count($instructorIds)) {
            $instructorRatings = DigitalSkillsRating::query()
                ->join('digital_skills_items', 'digital_skills_ratings.digital_skills_item_id', '=', 'digital_skills_items.id')
                ->whereIn('digital_skills_items.instructor_user_id', $instructorIds)
                ->selectRaw('digital_skills_items.instructor_user_id as instructor_id, COUNT(*) as c, AVG(digital_skills_ratings.rating) as a')
                ->groupBy('digital_skills_items.instructor_user_id')
                ->get()
                ->keyBy('instructor_id')
                ->map(function ($row) {
                    $count = (int) ($row->c ?? 0);
                    $avg = ($count > 0 && !is_null($row->a)) ? round((float) $row->a, 1) : null;
                    return ['count' => $count, 'avg' => $avg];
                })
                ->all();
        }

        return view('digital_skills.index', compact('cta', 'items', 'instructorRatings'));
    }

    public function show(Request $request, DigitalSkillsItem $item)
    {
        if (!$item->is_active) {
            abort(404);
        }

        $item->load('instructor:id,name');
        $item->load([
            'lessons' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order')->orderByDesc('id');
            },
        ]);

        $previewLessons = $item->lessons->where('is_preview', true)->values();
        $allLessons = $item->lessons->values();
        $previewHero = $previewLessons->filter(function ($l) {
            return !is_null($l->video_url) && trim((string) $l->video_url) !== '';
        })->first();

        $ratingsQuery = DigitalSkillsRating::query()->where('digital_skills_item_id', $item->id);
        $ratingsCount = (int) $ratingsQuery->count();
        $avgRating = $ratingsCount ? round((float) $ratingsQuery->avg('rating'), 1) : null;

        $ratings = DigitalSkillsRating::query()
            ->where('digital_skills_item_id', $item->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $hasAccess = (bool) $request->session()->get("digital_skills_access.{$item->id}", false);

        $instructorRating = null;
        if (!is_null($item->instructor_user_id)) {
            $instructorQuery = DigitalSkillsRating::query()
                ->join('digital_skills_items', 'digital_skills_ratings.digital_skills_item_id', '=', 'digital_skills_items.id')
                ->where('digital_skills_items.instructor_user_id', $item->instructor_user_id);
            $instructorCount = (int) $instructorQuery->count();
            $instructorAvg = $instructorCount ? round((float) $instructorQuery->avg('digital_skills_ratings.rating'), 1) : null;
            $instructorRating = ['count' => $instructorCount, 'avg' => $instructorAvg];
        }

        return view('digital_skills.show', compact('item', 'previewLessons', 'allLessons', 'previewHero', 'ratings', 'ratingsCount', 'avgRating', 'instructorRating', 'hasAccess'));
    }

    public function lesson(Request $request, DigitalSkillsItem $item, int $lesson)
    {
        if (!$item->is_active) {
            abort(404);
        }

        $lessonItem = DigitalSkillsLesson::query()
            ->where('digital_skills_item_id', $item->id)
            ->where('is_active', true)
            ->findOrFail($lesson);

        $hasAccess = (bool) $request->session()->get("digital_skills_access.{$item->id}", false);
        if (!$lessonItem->is_preview && !$hasAccess) {
            return redirect()
                ->route('digital-skills.show', $item)
                ->with('status', 'Please enroll to access the full course content.');
        }

        $lessons = DigitalSkillsLesson::query()
            ->where('digital_skills_item_id', $item->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $accessible = $lessons->filter(function ($l) use ($hasAccess) {
            return $l->is_preview || $hasAccess;
        })->values();

        $prevLesson = null;
        $nextLesson = null;
        $pos = $accessible->search(function ($l) use ($lessonItem) {
            return (int) $l->id === (int) $lessonItem->id;
        });
        if (is_int($pos) || is_numeric($pos)) {
            $pos = (int) $pos;
            $prevLesson = $pos > 0 ? $accessible->get($pos - 1) : null;
            $nextLesson = ($pos >= 0 && $pos < ($accessible->count() - 1)) ? $accessible->get($pos + 1) : null;
        }

        return view('digital_skills.lesson', [
            'item' => $item,
            'lesson' => $lessonItem,
            'hasAccess' => $hasAccess,
            'lessons' => $lessons,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
        ]);
    }

    public function rate(Request $request, DigitalSkillsItem $item)
    {
        if (!$item->is_active) {
            abort(404);
        }

        $hasAccess = (bool) $request->session()->get("digital_skills_access.{$item->id}", false);
        if (!$hasAccess) {
            return redirect()
                ->route('digital-skills.show', $item)
                ->with('status', 'Please enroll to rate this course.');
        }

        $data = $request->validate([
            'r_name' => ['required', 'string', 'max:255'],
            'r_email' => ['required', 'email', 'max:255'],
            'r_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'r_comment' => ['nullable', 'string', 'max:2000'],
        ]);

        DigitalSkillsRating::query()->create([
            'digital_skills_item_id' => $item->id,
            'name' => $data['r_name'],
            'email' => $data['r_email'],
            'rating' => (int) $data['r_rating'],
            'comment' => $data['r_comment'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
        ]);

        return redirect()->route('digital-skills.show', $item)->with('status', 'Thanks for your rating.');
    }

    public function enroll(Request $request)
    {
        $data = $request->validate([
            'digital_skills_item_id' => ['required', 'integer', 'exists:digital_skills_items,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $item = DigitalSkillsItem::query()
            ->where('is_active', true)
            ->findOrFail($data['digital_skills_item_id']);

        $amount = null;
        $paymentStatus = 'pending';

        $price = !is_null($item->price) ? (float) $item->price : null;
        if (($item->is_free ?? false) || $price === null || $price <= 0) {
            $amount = 0;
            $paymentStatus = 'free';
        } else {
            $amount = $price;
            $paymentStatus = 'pending';
        }

        $enrollment = DigitalSkillsEnrollment::query()->create([
            'digital_skills_item_id' => $item->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'] ?? null,
            'amount' => $amount,
            'currency' => $item->currency ?: 'NGN',
            'payment_status' => $paymentStatus,
            'status' => 'new',
        ]);

        if ($paymentStatus === 'free') {
            $request->session()->put("digital_skills_access.{$item->id}", true);

            return redirect()->route('digital-skills.show', $item)->with('status', 'Enrollment received. You can now access the course content.');
        }

        return redirect()->route('digital-skills.checkout', $enrollment);
    }

    public function checkout(DigitalSkillsEnrollment $enrollment)
    {
        $enrollment->load('item');

        return view('digital_skills.checkout', compact('enrollment'));
    }

    public function pay(Request $request, DigitalSkillsEnrollment $enrollment)
    {
        $enrollment->load('item');

        if ($enrollment->payment_status === 'paid') {
            return redirect()->route('digital-skills.checkout', $enrollment)->with('status', 'Payment already completed.');
        }

        $enrollment->payment_status = ($enrollment->amount && (float)$enrollment->amount > 0) ? 'paid' : 'free';
        $enrollment->paid_at = now();
        $enrollment->payment_reference = 'DSK-' . strtoupper(Str::random(10));
        $enrollment->status = 'enrolled';
        $enrollment->save();

        $request->session()->put("digital_skills_access.{$enrollment->digital_skills_item_id}", true);

        return redirect()->route('digital-skills.show', $enrollment->item)->with('status', 'Payment received. You can now access the course content.');
    }
}
