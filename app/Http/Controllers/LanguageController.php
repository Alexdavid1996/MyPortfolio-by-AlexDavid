<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,conversational,fluent,native',
        ]);

        $validated['user_id'] = auth()->id();
        Language::create($validated);

        return redirect()->route('admin.cv')->with('status', 'Language added');
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,conversational,fluent,native',
        ]);

        $language->update($validated);

        return redirect()->route('admin.cv')->with('status', 'Language updated');
    }

    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('admin.cv')->with('status', 'Language removed');
    }
}
