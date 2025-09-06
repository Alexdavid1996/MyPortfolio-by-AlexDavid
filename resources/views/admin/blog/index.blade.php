@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Blog Posts</h1>
    <div class="mb-4">
        <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">New Post</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($posts as $post)
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow flex flex-col justify-between">
                <div>
                    <img src="{{ $post->cover_image_url ? asset($post->cover_image_url) : asset('images/placeholder-1200x630.svg') }}" alt="{{ $post->title }}" class="mb-2 w-full h-32 object-cover rounded">
                    <h2 class="font-semibold text-lg">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $post->category->name ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $post->slug }}</p>
                    @if($post->published_at)
                        <p class="text-xs text-gray-500 mt-1">Published {{ $post->published_at->format('M d, Y') }}</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Last updated {{ $post->updated_at->format('M d, Y') }}</p>
                    @endif
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.blog.edit', $post) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" data-confirm="Delete this post?">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
