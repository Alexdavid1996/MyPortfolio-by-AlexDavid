<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::orderByDesc('id')->paginate(6);
        return view('admin.messages.index', compact('messages'));
    }

    public function sidebar(Request $request)
    {
        $messages = Message::orderByDesc('id')->paginate(4, ['*'], 'sidebar_page');
        return view('admin.messages.sidebar', compact('messages'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return back()->with('status', 'Message deleted.');
    }
}
