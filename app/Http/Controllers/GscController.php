<?php

namespace App\Http\Controllers;

use App\Models\GoogleSearch;
use App\Models\CustomHeaderAndBody;
use Illuminate\Http\Request;

class GscController extends Controller
{
    public function index()
    {
        $google = GoogleSearch::first();
        $custom = CustomHeaderAndBody::first();

        return view('admin.gsc.index', compact('google', 'custom'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'verification_code' => ['nullable', 'string', 'max:255'],
            'head_code' => ['nullable', 'string'],
            'body_code' => ['nullable', 'string'],
        ]);

        GoogleSearch::query()->updateOrCreate(
            ['id' => 1],
            ['verification_code' => $validated['verification_code'] ?? null]
        );

        CustomHeaderAndBody::query()->updateOrCreate(
            ['id' => 1],
            [
                'head_code' => $validated['head_code'] ?? null,
                'body_code' => $validated['body_code'] ?? null,
            ]
        );

        return redirect()->route('admin.gsc')->with('status', 'Settings saved.');
    }
}
