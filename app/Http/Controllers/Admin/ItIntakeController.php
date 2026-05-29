<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItIntake;
use Illuminate\Http\Request;

class ItIntakeController extends Controller
{
    public function index()
    {
        $items = ItIntake::query()->orderByDesc('id')->get();

        return view('admin.it_intakes.index', compact('items'));
    }

    public function show(ItIntake $intake)
    {
        return view('admin.it_intakes.show', compact('intake'));
    }

    public function update(Request $request, ItIntake $intake)
    {
        $data = $request->validate([
            'approval_status' => ['required', 'string', 'max:50'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'coordinator_signature' => ['nullable', 'string', 'max:255'],
            'coordinator_date' => ['nullable', 'date'],
        ]);

        $intake->fill($data);
        $intake->save();

        return redirect()->route('admin.it-intakes.show', $intake)->with('status', 'IT Intake updated');
    }

    public function destroy(ItIntake $intake)
    {
        $intake->delete();

        return redirect()->route('admin.it-intakes.index')->with('status', 'IT Intake deleted');
    }
}

