<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalSkillsEnrollment;
use Illuminate\Http\Request;

class DigitalSkillsEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalSkillsEnrollment::query()
            ->with('item')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $q = trim((string) $request->string('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhereHas('item', function ($i) use ($q) {
                        $i->where('title', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $enrollments = $query->paginate(20)->withQueryString();

        return view('admin.digital_skill_enrollments.index', compact('enrollments'));
    }

    public function show(DigitalSkillsEnrollment $enrollment)
    {
        $enrollment->load('item');

        return view('admin.digital_skill_enrollments.show', compact('enrollment'));
    }

    public function updateStatus(Request $request, DigitalSkillsEnrollment $enrollment)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $enrollment->status = $data['status'];
        $enrollment->save();

        return redirect()->route('admin.digital-skill-enrollments.show', $enrollment)->with('status', 'Status updated');
    }
}

