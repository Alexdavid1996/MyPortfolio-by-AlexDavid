@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Categories</h1>
    <div class="mb-4">
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">New Category</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($categories as $category)
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow flex flex-col justify-between">
                <div>
                    <h2 class="font-semibold text-lg">{{ $category->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ strip_tags($category->description) }}</p>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Delete this category?">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
