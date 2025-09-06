@extends('layouts.app')

@section('content')
  @php
    $user = $user ?? \App\Models\User::first();
  @endphp
  @php 
    $cover = $post->cover_image_url 
      ? asset($post->cover_image_url) 
      : asset('images/placeholder-1200x630.svg'); 
  @endphp

  <div class="max-w-3xl mx-auto text-center">
    {{-- Feature image --}}
    <div class="flex justify-center mb-6">
      <img 
        src="{{ $cover }}" 
        alt="{{ $post->title }}" 
        class="w-full max-w-3xl aspect-[1200/630] object-cover rounded-2xl shadow-md border border-gray-200 dark:border-white/10"
      >
    </div>

    {{-- Title --}}
    <h1 class="section-title text-center mb-3">{{ $post->title }}</h1>

    {{-- Meta + Category --}}
    <div class="flex flex-wrap items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
      <span>By {{ trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? '')) }}</span>
      <span class="mx-2">&middot;</span>
      <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
      @if($post->updated_at && $post->updated_at->gt($post->published_at))
        <span class="mx-2">&middot;</span>
        <span class="italic">Updated on {{ $post->updated_at->format('F j, Y') }}</span>
      @endif

      @if($post->category)
        <span class="mx-2">&middot;</span>
        <a href="{{ route('blog.category', $post->category->slug) }}" class="badge !text-xs !py-0.5 !px-2">
          {{ $post->category->name }}
        </a>
      @endif
    </div>

    {{-- Body --}}
    <article class="prose dark:prose-invert max-w-none mx-auto text-center content-body">
      {!! $post->body !!}
    </article>
  </div>
@endsection
