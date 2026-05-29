<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $items = Project::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.projects.index', compact('items'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'zip' => ['nullable', 'file', 'mimes:zip', 'max:51200'],
            'url' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $project = new Project();
        $project->fill($data);
        $project->is_active = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $project->image_path = $request->file('image')->store('projects', 'public');
        }
        if ($request->hasFile('zip')) {
            $project->zip_path = $request->file('zip')->store('project_zips', 'public');
        }

        $project->save();

        return redirect()->route('admin.projects.index')->with('status', 'Project created');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'zip' => ['nullable', 'file', 'mimes:zip', 'max:51200'],
            'url' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'remove_image' => ['nullable'],
            'remove_zip' => ['nullable'],
        ]);

        $project->fill($data);
        $project->is_active = $request->boolean('is_active');

        if ($request->boolean('remove_image') && $project->image_path) {
            Storage::disk('public')->delete($project->image_path);
            $project->image_path = null;
        }
        if ($request->boolean('remove_zip') && $project->zip_path) {
            Storage::disk('public')->delete($project->zip_path);
            $project->zip_path = null;
        }

        if ($request->hasFile('image')) {
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $project->image_path = $request->file('image')->store('projects', 'public');
        }
        if ($request->hasFile('zip')) {
            if ($project->zip_path) {
                Storage::disk('public')->delete($project->zip_path);
            }
            $project->zip_path = $request->file('zip')->store('project_zips', 'public');
        }

        $project->save();

        return redirect()->route('admin.projects.index')->with('status', 'Project updated');
    }

    public function destroy(Project $project)
    {
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }
        if ($project->zip_path) {
            Storage::disk('public')->delete($project->zip_path);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted');
    }
}
