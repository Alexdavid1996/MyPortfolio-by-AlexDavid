@extends('layouts.app')

@php
    $settings = $settings ?? null;
@endphp

@section('content')
{{-- Welcome Section --}}
<div class="mb-10">
    <h1 class="text-3xl font-bold text-cyan-400 flex items-center gap-2">
        {{ $settings?->home_page_h1 ?? 'Hey there!' }}
        <span class="animate-wave">👋</span>
    </h1>
    <p class="text-white-400 mt-2 mb-8">
        {{ $settings?->home_page_description ?? 'Welcome to my portfolio glad to have you here 🚀' }}
    </p>
</div>

{{-- Languages & Skills --}}
@if($languages->count() || $skills->count())
  <section class="mb-12">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-academic-cap class="w-7 h-7 mr-2 text-pink-500" /> Languages &amp; Skills
    </h2>

    @php
      // Levels for SKILLS (no 'native')
      $skillLevelWidth = [
        'beginner'     => 'w-1/3',
        'intermediate' => 'w-2/3',
        'advanced'     => 'w-5/6',
        'expert'       => 'w-full',
      ];
      $skillBadgeColors = [
        'beginner'     => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
        'intermediate' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'advanced'     => 'bg-sky-500/20 text-sky-300 border-sky-500/30',
        'expert'       => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
      ];

      // Levels for LANGUAGES (includes 'native')
      $langLevelWidth = [
        'beginner'       => 'w-1/3',
        'conversational' => 'w-1/2',
        'intermediate'   => 'w-2/3',
        'advanced'       => 'w-5/6',
        'fluent'         => 'w-11/12',
        'native'         => 'w-full',
      ];
      $langBadgeColors = [
        'beginner'       => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
        'conversational' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
        'intermediate'   => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'advanced'       => 'bg-sky-500/20 text-sky-300 border-sky-500/30',
        'fluent'         => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
        'native'         => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
      ];
      
      $yearsColors = [
  'bg-pink-500/20 text-pink-300 border-pink-500/30',
  'bg-amber-500/20 text-amber-300 border-amber-500/30',
  'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
  'bg-sky-500/20 text-sky-300 border-sky-500/30',
  'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
];


      // base pill style
      $pillBase = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium border';
    @endphp

    <div class="rounded-2xl border border-white/10 bg-white/5 dark:bg-white/[0.04] p-6 shadow-sm">
      <div class="grid md:grid-cols-2 gap-8">
        {{-- Skills --}}
        @if($skills->count())
          <div>
            <div class="flex items-center gap-2 mb-4">
              <x-heroicon-o-code-bracket class="w-5 h-5 text-emerald-400" />
              <h3 class="font-semibold">Skills</h3>
              <div class="h-px flex-1 bg-white/10 rounded"></div>
            </div>

            <ul class="space-y-4">
              @foreach($skills as $skill)
                @php
                  $lvl = strtolower($skill->level ?? '');
                  $w   = $skillLevelWidth[$lvl] ?? 'w-1/2';
                @endphp

                <li class="group">
                  <div class="flex items-center justify-between gap-3">
  <div class="flex items-center gap-2">
    {{-- skill name --}}
    <span class="text-sm">{{ $skill->name }}</span>

    {{-- years of experience badge --}}
    @if(!is_null($skill->years_experience) && $skill->years_experience !== '')
      @php $yearsColor = $yearsColors[$loop->index % count($yearsColors)]; @endphp
      <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border {{ $yearsColor }}">
        {{ (int) $skill->years_experience }} Years of Experience
      </span>
    @endif
  </div>

  {{-- category + level badge --}}
  <span class="{{ $pillBase }} {{ $skillBadgeColors[$lvl] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/30' }}">
    {{ ucfirst($skill->category) }} · {{ ucfirst($skill->level) }}
  </span>
</div>


                  {{-- progress track --}}
                  <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-2 {{ $w }} rounded-full transition-all duration-300 group-hover:opacity-90
                                bg-gradient-to-r from-cyan-400/80 to-sky-400/80"></div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Languages --}}
        @if($languages->count())
          <div>
            <div class="flex items-center gap-2 mb-4">
              <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-emerald-400" />
              <h3 class="font-semibold">Languages</h3>
              <div class="h-px flex-1 bg-white/10 rounded"></div>
            </div>

            <ul class="space-y-4">
              @foreach($languages as $language)
                @php
                  $lvl = strtolower($language->level ?? '');
                  $w   = $langLevelWidth[$lvl] ?? 'w-1/2';
                @endphp

                <li class="group">
                  <div class="flex items-center justify-between gap-3">
                    <span class="text-sm">{{ $language->name }}</span>
                    <span class="{{ $pillBase }} {{ $langBadgeColors[$lvl] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/30' }}">
                      {{ ucfirst($language->level) }}
                    </span>
                  </div>

                  {{-- progress track --}}
                  <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-2 {{ $w }} rounded-full transition-all duration-300 group-hover:opacity-90
                                bg-gradient-to-r from-emerald-400/80 to-teal-400/80"></div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
  </section>
@endif

{{-- Experience --}}
@if($experiences->count())
  <section class="mb-12">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-briefcase class="w-6 h-6 text-indigo-400 mr-2" />Experience
    </h2>

    @php
      // cycle a few pleasant gradients per card
      $expColors = [
        'from-pink-500/10 via-rose-500/10 to-rose-500/15',
        'from-amber-500/10 via-yellow-500/10 to-yellow-500/15',
        'from-emerald-500/10 via-green-500/10 to-green-500/15',
        'from-sky-500/10 via-blue-500/10 to-blue-500/15',
        'from-indigo-500/10 via-violet-500/10 to-violet-500/15',
      ];

      // shared badge base
      $badgeBase = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border";
    @endphp

    <div class="card p-5 space-y-6">
      @foreach($experiences as $exp)
        @php $color = $expColors[$loop->index % count($expColors)]; @endphp

        <div class="p-4 rounded-xl border border-white/10
                    bg-white/5 dark:bg-white/[0.03]
                    bg-gradient-to-r {{ $color }}">
          <h3 class="font-medium text-white/90">
            {{ $exp->company_name }} @ {{ $exp->role_title }}
          </h3>

          <p class="text-sm text-white/60">
            {{ $exp->start_date->format('M Y') }} –
            {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }}
          </p>

          {{-- colorful badges --}}
          <div class="mt-2 flex flex-wrap gap-2">
            @if($exp->role_title)
              <span class="{{ $badgeBase }} bg-cyan-500/20 text-cyan-300 border-cyan-500/30">
                {{ $exp->role_title }}
              </span>
            @endif
            @if($exp->location)
              <span class="{{ $badgeBase }} bg-emerald-500/20 text-emerald-300 border-emerald-500/30">
                {{ $exp->location }}
              </span>
            @endif
          </div>

          @if($exp->summary)
            <p class="mt-2 text-white/70">{{ $exp->summary }}</p>
          @endif
        </div>
      @endforeach

      <div class="pt-4">
        {{ $experiences->appends(request()->except('experience_page'))->links() }}
      </div>
    </div>
  </section>
@endif

{{-- Services --}}
@if($servicePage && $services->count())
  <section class="mb-12">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-star class="w-6 h-6 text-yellow-400 mr-2" />{{ $servicePage->title ?? 'Services' }}
    </h2>
    @if($servicePage->description)
      <p class="mb-6 text-gray-600 dark:text-gray-300">{{ $servicePage->description }}</p>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($services as $service)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg shadow-brand/30 hover:shadow-2xl hover:shadow-brand/60 transition transform hover:scale-105">
          <h3 class="text-xl font-semibold mb-4">{{ $service->service_title }}</h3>
          @php
              $items = preg_split('/\r?\n/', $service->service_description ?? '');
          @endphp
          <ul class="space-y-2 mb-6">
            @foreach($items as $item)
              @if(trim($item) !== '')
                <li class="flex items-start">
                  <x-heroicon-o-star class="w-5 h-5 text-yellow-400 mt-1 mr-2 flex-shrink-0" />
                  <span>{{ $item }}</span>
                </li>
              @endif
            @endforeach
          </ul>
          <div class="flex items-center justify-between">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border bg-emerald-500/20 text-emerald-300 border-emerald-500/30">
              {{ $service->price }}
            </span>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border bg-sky-500/20 text-sky-300 border-sky-500/30 hover:bg-sky-500/30 hover:text-sky-200 transition">
              Inquiry Now!
            </a>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6">
      {{ $services->appends(request()->except('services_page'))->links() }}
    </div>
  </section>
@endif

{{-- Recent Projects & Latest Posts --}}
@if($portfolio->count() || $posts->count())
  <section class="mb-12">
    <div class="grid md:grid-cols-2 gap-12">
      @if($portfolio->count())
        <div>
          <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-green-400 mr-2" />Recent Projects
          </h2>
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($portfolio as $item)
              @php $thumb = $item->thumbnail_url ? asset($item->thumbnail_url) : asset('images/placeholder-1200x630.svg'); @endphp
              <article class="card">
                <img src="{{ $thumb }}" alt="{{ $item->title }}" class="w-full aspect-video object-cover">
                <div class="p-4">
                  <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($item->tech_stack ?? [] as $tech)
                      <span class="badge">{{ $tech }}</span>
                    @endforeach
                  </div>
                  <h3 class="text-sm font-semibold">
                    <a href="{{ route('portfolio.show', $item->slug) }}" class="hover:underline">{{ $item->title }}</a>
                  </h3>
                  <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $item->short_description }}</p>
                  <a href="{{ route('portfolio.show', $item->slug) }}" class="btn mt-3">View project →</a>
                </div>
              </article>
            @endforeach
          </div>
          <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('portfolio.index') }}" class="inline-block px-4 py-2 rounded-lg bg-gray-200 dark:bg-white/10 text-sm font-medium hover:bg-gray-300 dark:hover:bg-white/20">View all</a>
            {{ $portfolio->appends(request()->except('projects_page'))->links() }}
          </div>
        </div>
      @endif

      @if($posts->count())
        <div>
          <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-pencil-square class="w-6 h-6 text-pink-400 mr-2" />Latest Posts
          </h2>
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($posts as $post)
              @php $cover = $post->cover_image_url ? asset($post->cover_image_url) : asset('images/placeholder-1200x630.svg'); @endphp
              <article class="card">
                <img src="{{ $cover }}" alt="{{ $post->title }}" class="w-full aspect-video object-cover">
                <div class="p-4">
                  <div class="flex flex-wrap gap-2 mb-2">
                    @if($post->category)
                      <a href="{{ route('blog.category', $post->category->slug) }}" class="badge">{{ $post->category->name }}</a>
                    @endif
                  </div>
                  <h3 class="text-sm font-semibold">
                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                  </h3>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ optional($post->published_at)->format('F j, Y') }}</p>
                  <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $post->excerpt }}</p>
                  <a href="{{ route('blog.show', $post->slug) }}" class="btn mt-3">Read more →</a>
                </div>
              </article>
            @endforeach
          </div>
          <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('blog.index') }}" class="inline-block px-4 py-2 rounded-lg bg-gray-200 dark:bg-white/10 text-sm font-medium hover:bg-gray-300 dark:hover:bg-white/20">More posts</a>
            {{ $posts->appends(request()->except('posts_page'))->links() }}
          </div>
        </div>
      @endif
    </div>
  </section>
@endif
@endsection
