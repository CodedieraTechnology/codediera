<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalSkillsItem;
use App\Models\DigitalSkillsLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DigitalSkillsItemController extends Controller
{
    public function index()
    {
        $items = DigitalSkillsItem::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.digital_skills.index', compact('items'));
    }

    public function create()
    {
        $maxSort = (int) (DigitalSkillsItem::query()->max('sort_order') ?? 0);

        $item = new DigitalSkillsItem();
        $item->sort_order = $maxSort + 1;
        $item->is_active = true;
        $item->is_free = true;
        $item->currency = 'NGN';

        $instructors = User::query()
            ->where('is_instructor', true)
            ->orderBy('name')
            ->get();

        return view('admin.digital_skills.create', compact('item', 'instructors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_link' => ['nullable', 'url', 'max:500'],
            'course_outline' => ['nullable', 'string', 'max:20000'],
            'lessons' => ['nullable', 'array', 'max:500'],
            'lessons.*.id' => ['nullable', 'integer'],
            'lessons.*.title' => ['nullable', 'string', 'max:255'],
            'lessons.*.brief_info' => ['nullable', 'string', 'max:2000'],
            'lessons.*.video_url' => ['nullable', 'url', 'max:500'],
            'lessons.*.pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'lessons.*.image' => ['nullable', 'image', 'max:4096'],
            'lessons.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'lessons.*.is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'max:4096'],
            'instructor_user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('is_instructor', true)],
            'total_hours' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'is_free' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $item = DB::transaction(function () use ($request, $data) {
            $item = new DigitalSkillsItem();
            $fillData = $data;
            unset($fillData['preview_title'], $fillData['preview_link'], $fillData['course_outline']);
            $item->fill($fillData);

            if (is_null($item->sort_order)) {
                $maxSort = (int) (DigitalSkillsItem::query()->max('sort_order') ?? 0);
                $item->sort_order = $maxSort + 1;
            }

            $item->is_free = $request->boolean('is_free');
            if ($item->is_free) {
                $item->price = null;
            }
            $item->is_active = $request->boolean('is_active');

            if ($request->hasFile('image')) {
                $item->image_path = $request->file('image')->store('digital_skills', 'public');
            }

            $item->save();

            $this->syncLessonsFromRequest($request, $item);

            return $item;
        });

        return redirect()->route('admin.digital-skills.index')->with('status', 'Item created');
    }

    public function edit(DigitalSkillsItem $item)
    {
        $instructors = User::query()
            ->where('is_instructor', true)
            ->orderBy('name')
            ->get();

        return view('admin.digital_skills.edit', compact('item', 'instructors'));
    }

    public function update(Request $request, DigitalSkillsItem $item)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_link' => ['nullable', 'url', 'max:500'],
            'course_outline' => ['nullable', 'string', 'max:20000'],
            'lessons' => ['nullable', 'array', 'max:500'],
            'lessons.*.id' => ['nullable', 'integer'],
            'lessons.*.title' => ['nullable', 'string', 'max:255'],
            'lessons.*.brief_info' => ['nullable', 'string', 'max:2000'],
            'lessons.*.video_url' => ['nullable', 'url', 'max:500'],
            'lessons.*.pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'lessons.*.image' => ['nullable', 'image', 'max:4096'],
            'lessons.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'lessons.*.is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'max:4096'],
            'instructor_user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('is_instructor', true)],
            'total_hours' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'is_free' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'remove_image' => ['nullable'],
        ]);

        DB::transaction(function () use ($request, $data, $item) {
            $sortOrder = $data['sort_order'] ?? null;
            $fillData = $data;
            unset($fillData['preview_title'], $fillData['preview_link'], $fillData['course_outline']);
            $item->fill($fillData);
            if (is_null($sortOrder)) {
                $item->sort_order = $item->getOriginal('sort_order') ?? 0;
            }
            $item->is_free = $request->boolean('is_free');
            if ($item->is_free) {
                $item->price = null;
            }
            $item->is_active = $request->boolean('is_active');

            if ($request->boolean('remove_image') && $item->image_path) {
                Storage::disk('public')->delete($item->image_path);
                $item->image_path = null;
            }

            if ($request->hasFile('image')) {
                if ($item->image_path) {
                    Storage::disk('public')->delete($item->image_path);
                }
                $item->image_path = $request->file('image')->store('digital_skills', 'public');
            }

            $item->save();

            $this->syncLessonsFromRequest($request, $item);
        });

        return redirect()->route('admin.digital-skills.index')->with('status', 'Item updated');
    }

    public function show(DigitalSkillsItem $item)
    {
        return redirect()->route('admin.digital-skills.edit', $item);
    }

    public function storeInstructor(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_instructor' => true,
            'is_admin' => false,
        ]);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function storeLesson(Request $request, DigitalSkillsItem $item)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'brief_info' => ['nullable', 'string', 'max:2000'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $videoRaw = trim((string) ($data['video_url'] ?? ''));
        $videoUrl = $videoRaw !== '' ? $this->normalizeVideoUrl($videoRaw) : null;
        $sortOrder = $data['sort_order'] ?? null;
        if (is_null($sortOrder)) {
            $maxSort = (int) (DigitalSkillsLesson::query()
                ->where('digital_skills_item_id', $item->id)
                ->max('sort_order') ?? 0);
            $sortOrder = $maxSort + 1;
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('digital_skills/lesson_pdfs', 'public');
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('digital_skills/lesson_images', 'public');
        }

        DigitalSkillsLesson::query()->create([
            'digital_skills_item_id' => $item->id,
            'title' => $data['title'],
            'brief_info' => $data['brief_info'] ?? null,
            'content' => null,
            'video_url' => $videoUrl,
            'pdf_path' => $pdfPath,
            'image_path' => $imagePath,
            'is_preview' => false,
            'sort_order' => (int) $sortOrder,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('status', 'Lesson added');
    }

    public function destroy(DigitalSkillsItem $item)
    {
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();

        return redirect()->route('admin.digital-skills.index')->with('status', 'Item deleted');
    }

    private function syncLessonsFromRequest(Request $request, DigitalSkillsItem $item): void
    {
        $previewTitle = trim((string) $request->input('preview_title', ''));
        $previewLink = trim((string) $request->input('preview_link', ''));
        $hasPreview = ($previewTitle !== '' || $previewLink !== '');

        DigitalSkillsLesson::query()
            ->where('digital_skills_item_id', $item->id)
            ->where('is_preview', true)
            ->delete();

        $sortStart = 1;
        if ($hasPreview) {
            $title = $previewTitle !== '' ? $previewTitle : 'Preview';
            $videoUrl = $previewLink !== '' ? $this->normalizeVideoUrl($previewLink) : null;
            DigitalSkillsLesson::query()->create([
                'digital_skills_item_id' => $item->id,
                'title' => $title,
                'content' => null,
                'video_url' => $videoUrl,
                'is_preview' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]);
            $sortStart = 2;
        }

        $lessons = $request->input('lessons');
        if (is_array($lessons)) {
            $existing = DigitalSkillsLesson::query()
                ->where('digital_skills_item_id', $item->id)
                ->where('is_preview', false)
                ->get()
                ->keyBy('id');

            $keepIds = [];
            $sort = $sortStart;

            foreach ($lessons as $idx => $row) {
                $title = trim((string) ($row['title'] ?? ''));
                if ($title === '') {
                    continue;
                }

                $lessonId = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
                $lesson = $lessonId && isset($existing[$lessonId]) ? $existing[$lessonId] : null;

                $videoRaw = trim((string) ($row['video_url'] ?? ''));
                $videoUrl = $videoRaw !== '' ? $this->normalizeVideoUrl($videoRaw) : null;
                $brief = trim((string) ($row['brief_info'] ?? ''));
                $briefInfo = $brief !== '' ? $brief : null;
                $sortOrder = isset($row['sort_order']) && $row['sort_order'] !== '' ? (int) $row['sort_order'] : $sort;
                $isActive = isset($row['is_active']) && (string) $row['is_active'] !== '0';

                $pdfPath = null;
                $pdfFile = $request->file("lessons.$idx.pdf");
                if ($pdfFile) {
                    if ($lesson?->pdf_path) {
                        Storage::disk('public')->delete($lesson->pdf_path);
                    }
                    $pdfPath = $pdfFile->store('digital_skills/lesson_pdfs', 'public');
                }

                $imagePath = null;
                $imageFile = $request->file("lessons.$idx.image");
                if ($imageFile) {
                    if ($lesson?->image_path) {
                        Storage::disk('public')->delete($lesson->image_path);
                    }
                    $imagePath = $imageFile->store('digital_skills/lesson_images', 'public');
                }

                if ($lesson) {
                    $lesson->title = $title;
                    $lesson->brief_info = $briefInfo;
                    $lesson->video_url = $videoUrl;
                    $lesson->sort_order = $sortOrder;
                    $lesson->is_active = $isActive;
                    if (!is_null($pdfPath)) {
                        $lesson->pdf_path = $pdfPath;
                    }
                    if (!is_null($imagePath)) {
                        $lesson->image_path = $imagePath;
                    }
                    $lesson->save();
                    $keepIds[] = $lesson->id;
                } else {
                    $created = DigitalSkillsLesson::query()->create([
                        'digital_skills_item_id' => $item->id,
                        'title' => $title,
                        'brief_info' => $briefInfo,
                        'content' => null,
                        'video_url' => $videoUrl,
                        'pdf_path' => $pdfPath,
                        'image_path' => $imagePath,
                        'is_preview' => false,
                        'sort_order' => $sortOrder,
                        'is_active' => $isActive,
                    ]);
                    $keepIds[] = $created->id;
                }

                $sort++;
            }

            $toDelete = $existing->keys()->diff($keepIds)->values();
            if ($toDelete->count()) {
                $deleteRows = DigitalSkillsLesson::query()
                    ->where('digital_skills_item_id', $item->id)
                    ->where('is_preview', false)
                    ->whereIn('id', $toDelete->all())
                    ->get();

                foreach ($deleteRows as $l) {
                    if ($l->pdf_path) {
                        Storage::disk('public')->delete($l->pdf_path);
                    }
                    if ($l->image_path) {
                        Storage::disk('public')->delete($l->image_path);
                    }
                    $l->delete();
                }
            }

            return;
        }

        $courseLines = $this->normalizeLines((string) $request->input('course_outline', ''));
        DigitalSkillsLesson::query()
            ->where('digital_skills_item_id', $item->id)
            ->where('is_preview', false)
            ->delete();

        $sort = $sortStart;
        foreach ($courseLines as $title) {
            DigitalSkillsLesson::query()->create([
                'digital_skills_item_id' => $item->id,
                'title' => $title,
                'content' => null,
                'video_url' => null,
                'is_preview' => false,
                'sort_order' => $sort,
                'is_active' => true,
            ]);
            $sort++;
        }
    }

    private function normalizeLines(string $text): array
    {
        if (preg_match('/<\\s*\\w+[^>]*>/', $text) === 1) {
            $text = preg_replace_callback('/<\\s*a\\s+[^>]*href\\s*=\\s*([\\\"\\\'])(.*?)\\1[^>]*>.*?<\\s*\\/\\s*a\\s*>/is', function ($m) {
                return ' ' . ($m[2] ?? '') . ' ';
            }, $text) ?? $text;
            $text = preg_replace('/<\\s*br\\s*\\/?>/i', "\n", $text) ?? $text;
            $text = preg_replace('/<\\s*\\/\\s*(p|div|li|tr|h[1-6])\\s*>/i', "\n", $text) ?? $text;
            $text = strip_tags($text);
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = array_map('trim', explode("\n", $text));
        $lines = array_values(array_filter($lines, fn ($l) => $l !== ''));

        $seen = [];
        $out = [];
        foreach ($lines as $l) {
            $key = strtolower($l);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $l;
        }

        return $out;
    }

    private function normalizeVideoUrl(string $url): string
    {
        $url = trim($url);

        if (preg_match('/(?:youtube\\.com\\/(?:watch\\?v=|embed\\/|shorts\\/)|youtu\\.be\\/)([A-Za-z0-9_-]{11})/i', $url, $m) === 1) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        if (preg_match('/vimeo\\.com\\/(?:video\\/)?(\\d+)/i', $url, $m) === 1) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }
}
