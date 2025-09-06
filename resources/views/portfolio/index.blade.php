@extends('layouts.app')

@section('content')
  <h1 class="section-title">Portfolio</h1>

  @if($items->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($items as $item)
        <article class="card">
          @php $thumb = $item->thumbnail_url ? asset($item->thumbnail_url) : asset('images/placeholder-1200x630.svg'); @endphp
          <img src="{{ $thumb }}" alt="{{ $item->title }}" class="w-full aspect-[1200/630] object-cover rounded-t-xl">
          <div class="p-5">
            <h2 class="text-xl font-semibold">
              <a href="{{ route('portfolio.show', $item->slug) }}" class="hover:underline">{{ $item->title }}</a>
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $item->short_description }}</p>
            @if($item->tech_stack)
              <div class="mt-3 flex flex-wrap gap-2">
                @foreach($item->tech_stack as $tech)
                  <span class="badge">{{ $tech }}</span>
                @endforeach
              </div>
            @endif
            <a href="{{ route('portfolio.show', $item->slug) }}" class="btn mt-3">View full project →</a>
          </div>
        </article>
      @endforeach
    </div>
    <div class="mt-6">
      {{ $items->links() }}
    </div>
  @else
    <p class="text-gray-600">No portfolio items yet.</p>
  @endif
@endsection
