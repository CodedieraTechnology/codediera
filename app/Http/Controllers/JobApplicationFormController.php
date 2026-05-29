<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationFormController extends Controller
{
    public function create()
    {
        return view('jobs.apply');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'message' => ['nullable', 'string'],
        ]);

        $application = new JobApplication();
        $application->fill($data);

        if ($request->hasFile('cv')) {
            $application->cv_path = $request->file('cv')->store('job_applications', 'local');
        }

        $application->save();

        return redirect()->route('jobs.apply')->with('status', 'Application submitted');
    }
}

