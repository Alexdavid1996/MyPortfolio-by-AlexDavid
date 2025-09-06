<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'icon_key' => 'nullable|string|max:100',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        $validated['years_experience'] = $validated['years_of_experience'] ?? null;
        unset($validated['years_of_experience']);

        Skill::create($validated);

        return redirect()->route('admin.cv')->with('status', 'Skill added');
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'icon_key' => 'nullable|string|max:100',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        $validated['years_experience'] = $validated['years_of_experience'] ?? null;
        unset($validated['years_of_experience']);

        $skill->update($validated);

        return redirect()->route('admin.cv')->with('status', 'Skill updated');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.cv')->with('status', 'Skill removed');
    }
}
