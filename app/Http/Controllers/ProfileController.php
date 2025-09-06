<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Language;
use App\Models\Setting;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $experiences = Experience::orderBy('sort_order')->get();
        $skills = Skill::orderBy('sort_order')->get();
        $languages = Language::where('user_id', $user->id)->orderBy('sort_order')->get();
        $settings = Setting::first();

        return view('admin.cv.index', compact('user', 'experiences', 'skills', 'languages', 'settings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image/avatars'), $filename);
            $validated['avatar_url'] = 'image/avatars/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('admin.cv')->with('status', 'Profile updated');
    }
}
