<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index()
    {
        $items = JobVacancy::query()
            ->orderBy('sort_order')
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.jobs.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'posted_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $job = new JobVacancy();
        $job->fill($data);
        $job->is_active = $request->boolean('is_active');

        if (!$job->posted_at && $job->is_active) {
            $job->posted_at = now();
        }

        $job->save();

        return redirect()->route('admin.jobs.index')->with('status', 'Job vacancy created');
    }

    public function edit(JobVacancy $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobVacancy $job)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'posted_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $job->fill($data);
        $job->is_active = $request->boolean('is_active');

        if (!$job->posted_at && $job->is_active) {
            $job->posted_at = now();
        }

        $job->save();

        return redirect()->route('admin.jobs.index')->with('status', 'Job vacancy updated');
    }

    public function destroy(JobVacancy $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('status', 'Job vacancy deleted');
    }
}

