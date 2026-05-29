<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectsPageController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('pages.projects', compact('projects'));
    }
}

