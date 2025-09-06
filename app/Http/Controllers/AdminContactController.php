<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function edit()
    {
        $contact = Contact::first();

        return view('admin.contact.edit', compact('contact'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);

        Contact::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('admin.contact')->with('status', 'Contact updated.');
    }
}

