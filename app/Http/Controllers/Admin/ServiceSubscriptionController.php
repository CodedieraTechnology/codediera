<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceInquiry;
use Illuminate\Http\Request;

class ServiceSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceInquiry::query()
            ->with('service')
            ->orderByDesc('id');

        $hasFilters = false;

        if ($request->filled('q')) {
            $hasFilters = true;
            $q = trim((string) $request->string('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('order_code', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('payment_status')) {
            $hasFilters = true;
            $query->where('payment_status', $request->string('payment_status'));
        }

        if ($request->filled('status')) {
            $hasFilters = true;
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('payment_type')) {
            $hasFilters = true;
            $query->where('payment_type', $request->string('payment_type'));
        }

        if (!$hasFilters) {
            $query->whereIn('payment_status', ['paid', 'free']);
        }

        $inquiries = $query->paginate(20)->withQueryString();

        return view('admin.service_subscriptions.index', compact('inquiries'));
    }

    public function show(ServiceInquiry $inquiry)
    {
        $inquiry->load(['service', 'payments']);

        return view('admin.service_subscriptions.show', compact('inquiry'));
    }

    public function updateProgress(Request $request, ServiceInquiry $inquiry)
    {
        $data = $request->validate([
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'progress_note' => ['nullable', 'string', 'max:255'],
        ]);

        $inquiry->progress_percent = $data['progress_percent'];
        $inquiry->progress_note = $data['progress_note'] ?? null;
        $inquiry->save();

        return redirect()->route('admin.service-subscriptions.show', $inquiry)->with('status', 'Progress updated');
    }
}
