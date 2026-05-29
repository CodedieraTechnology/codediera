<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->get();

        return view('jobs.index', compact('vacancies'));
    }

    public function show(JobVacancy $vacancy)
    {
        if (!$vacancy->is_active) {
            abort(404);
        }

        return view('jobs.show', compact('vacancy'));
    }

    public function apply(JobVacancy $vacancy)
    {
        if (!$vacancy->is_active) {
            return redirect()->route('jobs.apply')->with('status', 'This vacancy is not accepting applications.');
        }

        return view('jobs.apply', compact('vacancy'));
    }

    public function submit(Request $request, JobVacancy $vacancy)
    {
        if (!$vacancy->is_active) {
            return redirect()->route('jobs.apply')->with('status', 'This vacancy is not accepting applications.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'message' => ['nullable', 'string'],
        ]);

        $application = new JobApplication();
        $application->fill($data);
        $application->job_vacancy_id = $vacancy->id;
        $application->position = $vacancy->title;

        if ($request->hasFile('cv')) {
            $application->cv_path = $request->file('cv')->store('job_applications', 'local');
        }

        $application->save();

        return redirect()->route('jobs.vacancies.apply', $vacancy)->with('status', 'Application submitted');
    }
}

