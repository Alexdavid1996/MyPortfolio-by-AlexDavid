@extends('layouts.app')

@section('content')
  @php 
    $thumb = $item->thumbnail_url 
      ? asset($item->thumbnail_url) 
      : asset('images/placeholder-1200x630.svg'); 
  @endphp

  <div class="max-w-3xl mx-auto text-center">
    {{-- Feature image --}}
    <div class="flex justify-center mb-6">
      <img 
        src="{{ $thumb }}" 
        alt="{{ $item->title }}" 
        class="w-full max-w-3xl aspect-[1200/630] object-cover rounded-2xl shadow-md border border-gray-200 dark:border-white/10"
      >
    </div>

    {{-- Title --}}
    <h1 class="section-title text-center mb-3">{{ $item->title }}</h1>

    {{-- Meta info (date) --}}
    {{-- Portfolio items do not display a date --}}

    {{-- Tech stack badges --}}
    @if($item->tech_stack)
      <div class="flex flex-wrap justify-center gap-2 mb-8">
        @foreach($item->tech_stack as $tech)
          <span class="badge">{{ $tech }}</span>
        @endforeach
      </div>
    @endif

      {{-- Description --}}
      @if($item->description)
        <article class="prose dark:prose-invert max-w-none mx-auto mb-8 text-center content-body">
          {!! $item->description !!}
        </article>
      @endif
  </div>
@endsection
