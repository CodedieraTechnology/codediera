<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\ServiceInquiry;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $items = Service::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.services.index', compact('items'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.services.create', compact('serviceTypes'));
    }

    public function show(Service $service)
    {
        $service->load([
            'images',
            'inquiries' => function ($q) {
                $q->orderByDesc('id');
            },
        ]);

        $counts = [
            'total' => $service->inquiries->count(),
            'new' => $service->inquiries->where('status', 'new')->count(),
            'in_progress' => $service->inquiries->where('status', 'in_progress')->count(),
            'done' => $service->inquiries->where('status', 'done')->count(),
        ];

        return view('admin.services.show', compact('service', 'counts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'service_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'screenshot' => ['nullable', 'image', 'max:4096'],
            'approach_image' => ['nullable', 'image', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
            'is_free' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'payment_type' => ['nullable', 'string', 'max:50'],
            'inquiry_fields' => ['nullable', 'array'],
            'inquiry_fields.*' => ['string', 'max:50'],
            'download_url' => ['nullable', 'string', 'max:2000'],
            'delivery_duration_value' => ['nullable', 'integer', 'min:1'],
            'delivery_duration_unit' => ['nullable', 'string', 'in:days,weeks,months'],
            'grace_trial_enabled' => ['nullable'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $service = new Service();
        $service->fill($data);
        $service->is_free = $request->boolean('is_free');
        if ($service->is_free) {
            $service->price = null;
            $service->payment_type = 'one_time';
        }
        $service->is_active = $request->boolean('is_active');

        if ($request->hasFile('screenshot')) {
            $service->screenshot_path = $request->file('screenshot')->store('services', 'public');
        }
        if ($request->hasFile('approach_image')) {
            $service->approach_image_path = $request->file('approach_image')->store('services', 'public');
        }
        if (!$service->is_free) {
            $service->payment_type = $data['payment_type'] ?? 'one_time';
        }
        $service->inquiry_fields = array_values(array_unique(array_filter($data['inquiry_fields'] ?? [])));
        $service->download_url = $data['download_url'] ?? null;
        $service->grace_trial_enabled = $request->boolean('grace_trial_enabled');
        $service->save();

        if (is_array($request->file('gallery_images'))) {
            foreach ($request->file('gallery_images') as $upload) {
                if (!$upload) continue;
                ServiceImage::query()->create([
                    'service_id' => $service->id,
                    'image_path' => $upload->store('services', 'public'),
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('status', 'Service created');
    }

    public function edit(Service $service)
    {
        $serviceTypes = ServiceType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.services.edit', compact('service', 'serviceTypes'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'service_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'screenshot' => ['nullable', 'image', 'max:4096'],
            'approach_image' => ['nullable', 'image', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
            'is_free' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'payment_type' => ['nullable', 'string', 'max:50'],
            'inquiry_fields' => ['nullable', 'array'],
            'inquiry_fields.*' => ['string', 'max:50'],
            'download_url' => ['nullable', 'string', 'max:2000'],
            'delivery_duration_value' => ['nullable', 'integer', 'min:1'],
            'delivery_duration_unit' => ['nullable', 'string', 'in:days,weeks,months'],
            'grace_trial_enabled' => ['nullable'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'remove_screenshot' => ['nullable'],
            'remove_approach_image' => ['nullable'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['integer'],
        ]);

        $service->fill($data);
        $service->is_free = $request->boolean('is_free');
        if ($service->is_free) {
            $service->price = null;
            $service->payment_type = 'one_time';
        }
        $service->is_active = $request->boolean('is_active');

        if ($request->boolean('remove_screenshot') && $service->screenshot_path) {
            Storage::disk('public')->delete($service->screenshot_path);
            $service->screenshot_path = null;
        }
        if ($request->hasFile('screenshot')) {
            if ($service->screenshot_path) {
                Storage::disk('public')->delete($service->screenshot_path);
            }
            $service->screenshot_path = $request->file('screenshot')->store('services', 'public');
        }

        if ($request->boolean('remove_approach_image') && $service->approach_image_path) {
            Storage::disk('public')->delete($service->approach_image_path);
            $service->approach_image_path = null;
        }
        if ($request->hasFile('approach_image')) {
            if ($service->approach_image_path) {
                Storage::disk('public')->delete($service->approach_image_path);
            }
            $service->approach_image_path = $request->file('approach_image')->store('services', 'public');
        }

        if (!$service->is_free) {
            $service->payment_type = $data['payment_type'] ?? ($service->payment_type ?: 'one_time');
        }
        $service->inquiry_fields = array_values(array_unique(array_filter($data['inquiry_fields'] ?? [])));
        $service->download_url = $data['download_url'] ?? null;
        $service->grace_trial_enabled = $request->boolean('grace_trial_enabled');
        if (is_array($request->input('remove_gallery_images')) && count($request->input('remove_gallery_images')) > 0) {
            $ids = array_values(array_filter($request->input('remove_gallery_images'), function ($v) {
                return is_numeric($v);
            }));

            if (count($ids) > 0) {
                $images = ServiceImage::query()
                    ->where('service_id', $service->id)
                    ->whereIn('id', $ids)
                    ->get();

                foreach ($images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }

        $service->save();

        if (is_array($request->file('gallery_images'))) {
            foreach ($request->file('gallery_images') as $upload) {
                if (!$upload) continue;
                ServiceImage::query()->create([
                    'service_id' => $service->id,
                    'image_path' => $upload->store('services', 'public'),
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('status', 'Service updated');
    }

    public function updateInquiryStatus(Request $request, ServiceInquiry $inquiry)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $inquiry->status = $data['status'];
        $inquiry->save();

        return redirect()->route('admin.services.show', $inquiry->service_id)->with('status', 'Status updated');
    }

    public function destroyInquiry(ServiceInquiry $inquiry)
    {
        $serviceId = $inquiry->service_id;
        $inquiry->delete();

        return redirect()->route('admin.services.show', $serviceId)->with('status', 'Request deleted');
    }

    public function destroy(Service $service)
    {
        if ($service->screenshot_path) {
            Storage::disk('public')->delete($service->screenshot_path);
        }
        if ($service->approach_image_path) {
            Storage::disk('public')->delete($service->approach_image_path);
        }

        $images = ServiceImage::query()->where('service_id', $service->id)->get();
        foreach ($images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted');
    }

    public function uploadEditorImage(Request $request)
    {
        $data = $request->validate([
            'upload' => ['required', 'image', 'max:4096'],
        ]);

        $path = $data['upload']->store('services', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
