<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;

class TeamPageController extends Controller
{
    public function index()
    {
        $team = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('pages.team', compact('team'));
    }
}

