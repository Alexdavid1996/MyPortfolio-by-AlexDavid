<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Contact, Message};

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contact = Contact::first();
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $request->session()->put('contact_captcha', $a + $b);

        $canonical = $this->absoluteUrl(route('contact.index'));
        $meta = [
            'title' => $contact?->title ?? 'Contact',
            'description' => $contact?->meta_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage(),
        ];

        return view('contact.index', compact('contact', 'a', 'b', 'meta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'email' => 'required|email',
            'message' => 'required|string',
            'captcha' => 'required|numeric',
        ]);

        $expected = $request->session()->get('contact_captcha');
        if ((int)$request->captcha !== (int)$expected) {
            return back()->withErrors(['captcha' => 'Incorrect Answer. Please try again.'])->withInput();
        }

        Message::create($request->only(['first_name', 'last_name', 'email', 'message']));
        $request->session()->forget('contact_captcha');

        return back()->with('success', 'Message sent successfully.');
    }
}
