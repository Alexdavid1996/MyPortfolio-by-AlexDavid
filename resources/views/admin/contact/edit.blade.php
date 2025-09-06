@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Contact</h1>

@if(session('status'))
    <div class="mb-4 text-green-600 dark:text-green-400">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('admin.contact.update') }}" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    @csrf

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title', $contact?->title) }}"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
        />
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
        >{{ old('description', $contact?->description) }}</textarea>
        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta description</label>
        <input
            id="meta_description"
            type="text"
            name="meta_description"
            value="{{ old('meta_description', $contact?->meta_description) }}"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
        />
        @error('meta_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
</form>
@endsection

