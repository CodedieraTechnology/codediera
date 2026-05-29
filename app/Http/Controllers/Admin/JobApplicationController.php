<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index()
    {
        $items = JobApplication::query()
            ->with('vacancy')
            ->orderByDesc('id')
            ->get();

        return view('admin.job_applications.index', compact('items'));
    }

    public function show(JobApplication $application)
    {
        return view('admin.job_applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $application->status = $data['status'];
        $application->save();

        return redirect()->route('admin.job-applications.show', $application)->with('status', 'Status updated');
    }

    public function downloadCv(JobApplication $application)
    {
        if (!$application->cv_path || !Storage::disk('local')->exists($application->cv_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($application->cv_path);
    }
}
