<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $items = TeamMember::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.team.index', compact('items'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $member = new TeamMember();
        $member->name = $data['name'];
        $member->role = $data['role'] ?? null;
        $member->bio = $data['bio'] ?? null;
        $member->sort_order = $data['sort_order'] ?? 0;
        $member->is_active = $request->boolean('is_active');
        $member->social_links = array_filter([
            'facebook' => $data['social_facebook'] ?? null,
            'twitter' => $data['social_twitter'] ?? null,
            'linkedin' => $data['social_linkedin'] ?? null,
        ]);

        if ($request->hasFile('photo')) {
            $member->photo_path = $request->file('photo')->store('team', 'public');
        }

        $member->save();

        return redirect()->route('admin.team.index')->with('status', 'Team member created');
    }

    public function edit(TeamMember $member)
    {
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, TeamMember $member)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'remove_photo' => ['nullable'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $member->name = $data['name'];
        $member->role = $data['role'] ?? null;
        $member->bio = $data['bio'] ?? null;
        $member->sort_order = $data['sort_order'] ?? 0;
        $member->is_active = $request->boolean('is_active');
        $member->social_links = array_filter([
            'facebook' => $data['social_facebook'] ?? null,
            'twitter' => $data['social_twitter'] ?? null,
            'linkedin' => $data['social_linkedin'] ?? null,
        ]);

        if ($request->boolean('remove_photo') && $member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
            $member->photo_path = null;
        }

        if ($request->hasFile('photo')) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $member->photo_path = $request->file('photo')->store('team', 'public');
        }

        $member->save();

        return redirect()->route('admin.team.index')->with('status', 'Team member updated');
    }

    public function destroy(TeamMember $member)
    {
        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }

        $member->delete();

        return redirect()->route('admin.team.index')->with('status', 'Team member deleted');
    }
}

