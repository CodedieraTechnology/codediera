<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCta;
use Illuminate\Http\Request;

class HomeCtaController extends Controller
{
    public function index()
    {
        $items = HomeCta::query()->orderBy('slug')->get();

        return view('admin.ctas.index', compact('items'));
    }

    public function edit(HomeCta $cta)
    {
        return view('admin.ctas.edit', compact('cta'));
    }

    public function update(Request $request, HomeCta $cta)
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable'],
        ]);

        $cta->fill($data);
        $cta->is_active = $request->boolean('is_active');
        $cta->save();

        return redirect()->route('admin.ctas.index')->with('status', 'CTA updated');
    }
}

