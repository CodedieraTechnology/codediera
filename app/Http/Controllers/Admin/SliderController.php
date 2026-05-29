<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $items = Slider::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.sliders.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'video' => ['nullable', 'file', 'mimes:mp4,m4v,mov,webm,ogg,ogv', 'max:204800'],
            'video_upload_path' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ], [
            'video.max' => 'The video is too large. Please upload a smaller file.',
            'video.mimes' => 'The video must be a file of type: mp4, m4v, mov, webm, ogg, ogv.',
        ]);

        $slider = new Slider();
        $slider->fill($data);
        $slider->is_active = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $slider->image_path = $request->file('image')->store('sliders', 'public');
        }
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            if (!$file->isValid()) {
                return back()
                    ->with('error', 'Video upload failed. Please try again with a smaller video.')
                    ->withInput($request->except(['video', 'image']));
            }

            try {
                $slider->video_path = $file->store('sliders/videos', 'public');
            } catch (\Throwable $e) {
                report($e);
                return back()
                    ->with('error', 'Video upload failed on the server. Please try again.')
                    ->withInput($request->except(['video', 'image']));
            }
        } elseif (!empty($data['video_upload_path'])) {
            $stored = $this->storeVideoFromTempUpload((string)$data['video_upload_path']);
            if ($stored === null) {
                return back()
                    ->with('error', 'Video upload failed. Please upload the video again.')
                    ->withInput($request->except(['video', 'image']));
            }
            $slider->video_path = $stored;
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('status', 'Slider item created');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'video' => ['nullable', 'file', 'mimes:mp4,m4v,mov,webm,ogg,ogv', 'max:204800'],
            'video_upload_path' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'remove_image' => ['nullable'],
            'remove_video' => ['nullable'],
        ], [
            'video.max' => 'The video is too large. Please upload a smaller file.',
            'video.mimes' => 'The video must be a file of type: mp4, m4v, mov, webm, ogg, ogv.',
        ]);

        $slider->fill($data);
        $slider->is_active = $request->boolean('is_active');

        if ($request->boolean('remove_image') && $slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
            $slider->image_path = null;
        }
        if ($request->boolean('remove_video') && $slider->video_path) {
            Storage::disk('public')->delete($slider->video_path);
            $slider->video_path = null;
        }

        if ($request->hasFile('image')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            $slider->image_path = $request->file('image')->store('sliders', 'public');
        }
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            if (!$file->isValid()) {
                return back()
                    ->with('error', 'Video upload failed. Please try again with a smaller video.')
                    ->withInput($request->except(['video', 'image']));
            }

            try {
                if ($slider->video_path) {
                    Storage::disk('public')->delete($slider->video_path);
                }
                $slider->video_path = $file->store('sliders/videos', 'public');
            } catch (\Throwable $e) {
                report($e);
                return back()
                    ->with('error', 'Video upload failed on the server. Please try again.')
                    ->withInput($request->except(['video', 'image']));
            }
        } elseif (!empty($data['video_upload_path'])) {
            $stored = $this->storeVideoFromTempUpload((string)$data['video_upload_path']);
            if ($stored === null) {
                return back()
                    ->with('error', 'Video upload failed. Please upload the video again.')
                    ->withInput($request->except(['video', 'image']));
            }
            if ($slider->video_path) {
                Storage::disk('public')->delete($slider->video_path);
            }
            $slider->video_path = $stored;
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('status', 'Slider item updated');
    }

    public function uploadVideoChunk(Request $request)
    {
        $data = $request->validate([
            'upload_id' => ['required', 'string', 'max:80'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:10000'],
            'file_name' => ['required', 'string', 'max:255'],
            'chunk' => ['required', 'file', 'max:4096'],
        ]);

        $uploadId = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$data['upload_id']);
        if ($uploadId === '') {
            return response()->json(['message' => 'Invalid upload id'], 422);
        }

        $dir = "tmp/slider_uploads/{$uploadId}/chunks";
        Storage::disk('local')->makeDirectory($dir);

        $index = (int)$data['chunk_index'];
        $path = "{$dir}/{$index}.part";
        $stream = fopen($request->file('chunk')->getRealPath(), 'rb');
        Storage::disk('local')->put($path, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        return response()->json(['ok' => true]);
    }

    public function completeVideoUpload(Request $request)
    {
        $data = $request->validate([
            'upload_id' => ['required', 'string', 'max:80'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:10000'],
            'file_name' => ['required', 'string', 'max:255'],
        ]);

        $uploadId = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$data['upload_id']);
        if ($uploadId === '') {
            return response()->json(['message' => 'Invalid upload id'], 422);
        }

        $total = (int)$data['total_chunks'];
        $chunkDir = "tmp/slider_uploads/{$uploadId}/chunks";
        if (!Storage::disk('local')->exists($chunkDir)) {
            return response()->json(['message' => 'Upload not found'], 422);
        }

        $safeName = (string)$data['file_name'];
        $safeName = preg_replace('/[^\w.\- ]+/', '', $safeName) ?: 'video';
        $safeName = trim($safeName);
        $ext = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'mp4';
        }

        $mergedRel = "tmp/slider_uploads/{$uploadId}/merged." . $ext;
        $mergedAbs = storage_path('app/' . $mergedRel);
        Storage::disk('local')->makeDirectory("tmp/slider_uploads/{$uploadId}");

        $out = fopen($mergedAbs, 'wb');
        if ($out === false) {
            return response()->json(['message' => 'Cannot write file'], 500);
        }

        try {
            for ($i = 0; $i < $total; $i++) {
                $part = "{$chunkDir}/{$i}.part";
                if (!Storage::disk('local')->exists($part)) {
                    fclose($out);
                    return response()->json(['message' => 'Missing chunk'], 422);
                }

                $in = Storage::disk('local')->readStream($part);
                if ($in === false) {
                    fclose($out);
                    return response()->json(['message' => 'Cannot read chunk'], 500);
                }

                stream_copy_to_stream($in, $out);
                if (is_resource($in)) {
                    fclose($in);
                }
            }
        } finally {
            if (is_resource($out)) {
                fclose($out);
            }
        }

        for ($i = 0; $i < $total; $i++) {
            Storage::disk('local')->delete("{$chunkDir}/{$i}.part");
        }

        return response()->json([
            'ok' => true,
            'video_upload_path' => $mergedRel,
        ]);
    }

    private function storeVideoFromTempUpload(string $relativePath): ?string
    {
        $relativePath = str_replace('\\', '/', $relativePath);
        if (!Str::startsWith($relativePath, 'tmp/slider_uploads/')) {
            return null;
        }
        if (Str::contains($relativePath, '..')) {
            return null;
        }

        $abs = storage_path('app/' . $relativePath);
        if (!is_file($abs)) {
            return null;
        }

        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        if (!in_array($ext, ['mp4', 'm4v', 'mov', 'webm', 'ogg', 'ogv'], true)) {
            return null;
        }

        $name = Str::uuid()->toString() . '.' . $ext;
        $target = 'sliders/videos/' . $name;
        $stream = @fopen($abs, 'r');
        if ($stream === false) {
            return null;
        }

        try {
            $ok = Storage::disk('public')->put($target, $stream);
        } finally {
            fclose($stream);
        }

        @unlink($abs);

        return $ok ? $target : null;
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }
        if ($slider->video_path) {
            Storage::disk('public')->delete($slider->video_path);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('status', 'Slider item deleted');
    }
}
