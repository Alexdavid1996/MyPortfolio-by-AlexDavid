@extends('admin.layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold flex items-center gap-2">
        <x-heroicon-o-inbox class="w-8 h-8" />
        <span>Inbox Messages</span>
    </h1>
    <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-1 px-3 py-1 text-sm bg-brand text-white rounded hover:bg-brand/90">
        <x-heroicon-o-arrow-path class="w-4 h-4" />
        <span>Refresh</span>
    </a>
</div>

<div class="space-y-4">
    @foreach($messages as $message)
        <div class="p-4 bg-white dark:bg-gray-800 rounded shadow flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-semibold">{{ $message->first_name }} {{ $message->last_name }}</p>
                <p class="text-sm text-gray-500">{{ $message->email }}</p>
                <p class="text-sm mt-1">{{ Str::limit($message->message, 80) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="mt-2 sm:mt-0 flex space-x-2">
                @php
                    $payload = [
                        'id' => $message->id,
                        'name' => $message->first_name.' '.$message->last_name,
                        'email' => $message->email,
                        'message' => $message->message,
                        'date' => $message->created_at->format('M d, Y H:i'),
                    ];
                @endphp
                <button type="button" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600" data-read='@json($payload)'>Read</button>
                <form id="delete-form-{{ $message->id }}" action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Delete this message?">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $messages->links() }}
</div>

@endsection
