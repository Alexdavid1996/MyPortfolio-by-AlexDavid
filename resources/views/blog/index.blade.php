@extends('layouts.app')

@section('content')
  <h1 class="section-title">{{ $currentCategory->name ?? 'Blog' }}</h1>

  @if(isset($categories) && $categories->count())
    <div class="mb-6 flex flex-wrap gap-2">
      <a href="{{ route('blog.index') }}" class="px-3 py-1 text-sm rounded-full border {{ !$currentCategory ? 'bg-brand text-white border-brand' : 'bg-white/10 text-gray-600 dark:text-gray-300 border-white/20 hover:bg-brand/10 hover:text-brand' }}">All</a>
      @foreach($categories as $category)
        <a href="{{ route('blog.category', $category->slug) }}" class="px-3 py-1 text-sm rounded-full border {{ $currentCategory && $currentCategory->id === $category->id ? 'bg-brand text-white border-brand' : 'bg-white/10 text-gray-600 dark:text-gray-300 border-white/20 hover:bg-brand/10 hover:text-brand' }}">{{ $category->name }}</a>
      @endforeach
    </div>
  @endif

  @if($posts->count())
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @foreach($posts as $post)
        <article class="card">
          @php
            $cover = $post->cover_image_url
                ? asset($post->cover_image_url)
                : asset('images/placeholder-1200x630.svg');
          @endphp
          <img src="{{ $cover }}" alt="{{ $post->title }}" class="w-full aspect-[1200/630] object-cover rounded-t-xl">
          <div class="p-5">
            <div class="flex flex-wrap gap-2 mb-2">
              @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" class="badge">{{ $post->category->name }}</a>
              @endif
            </div>
            <h2 class="text-xl font-semibold">
              <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $post->excerpt }}</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              {{ optional($post->published_at)->format('F j, Y') }}
              @if($post->updated_at && $post->updated_at->gt($post->published_at))
                <span class="block text-xs text-gray-400">Updated on {{ $post->updated_at->format('F j, Y') }}</span>
              @endif
            </p>
            <a href="{{ route('blog.show', $post->slug) }}" class="btn mt-3">Read more →</a>
          </div>
        </article>
      @endforeach
    </div>
    <div class="mt-6">
      {{ $posts->links() }}
    </div>
  @else
    <p class="text-gray-600">No posts yet.</p>
  @endif
@endsection
