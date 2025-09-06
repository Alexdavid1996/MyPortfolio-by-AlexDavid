<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'role_title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'is_current' => 'boolean',
            'summary' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        Experience::create($validated);

        return redirect()->route('admin.cv')->with('status', 'Experience added');
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'role_title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'is_current' => 'boolean',
            'summary' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        $experience->update($validated);

        return redirect()->route('admin.cv')->with('status', 'Experience updated');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('admin.cv')->with('status', 'Experience removed');
    }
}
