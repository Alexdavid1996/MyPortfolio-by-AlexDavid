<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ ($settings->theme ?? 'light') === 'dark' ? 'dark' : '' }}">
@php
    $settings = $settings ?? \App\Models\Setting::first();
    $user = $user ?? \App\Models\User::first();
    $googleSearch = \App\Models\GoogleSearch::first();
    $customHeaderAndBody = \App\Models\CustomHeaderAndBody::first();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if($settings?->favicon)
        <link rel="icon" href="{{ asset($settings->favicon) }}">
    @endif
    @include('partials.seo', ['meta' => $meta ?? []])
    @if($googleSearch?->verification_code)
        <meta name="google-site-verification" content="{{ $googleSearch->verification_code }}" />
    @endif
    @vite(['resources/css/app.css', 'resources/css/editor/editor-support.css', 'resources/js/app.js'])
    @if($customHeaderAndBody?->head_code)
        {!! $customHeaderAndBody->head_code !!}
    @endif
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div x-data="{ open: false }" class="min-h-screen flex">
        <!-- Mobile overlay -->
        <div
            x-show="open"
            @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="{'-translate-x-full md:translate-x-0': !open}"
            class="fixed md:relative z-20 inset-y-0 left-0 w-64 transform md:transform-none transition-transform duration-200 bg-white dark:bg-gray-800 overflow-y-auto"
        >
            <button
                @click="open = false"
                class="md:hidden absolute top-4 right-4 text-gray-500 focus:outline-none"
                aria-label="Close menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="p-6 space-y-6">
                {{-- Profile --}}
                <div class="text-center">
                    @php $avatar = $user?->avatar_url ? asset($user->avatar_url) : asset('images/avatar-placeholder.png'); @endphp
                    <img src="{{ $avatar }}" alt="Avatar"
 class="mx-auto mb-3 h-28 w-28 rounded-full object-cover border-2 border-brand
        shadow-lg shadow-brand/30 scale-105
        transition-transform duration-300
        hover:scale-110 hover:shadow-2xl hover:shadow-brand/60">

                    <h2 class="text-xl font-bold">
                        {{ trim(($user?->first_name ?? '').' '.($user?->last_name ?? '')) ?: 'Your Name' }}
                    </h2>
                    @include('partials.sidebar-info', ['user' => $user, 'settings' => $settings])


                </div>

           {{-- Nav --}}
<div class="p-4 rounded-xl border border-white/10 bg-white/5 dark:bg-gray-800/70 shadow-md">
    <nav class="space-y-2">
        <a href="{{ route('home') }}" 
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white 
                  hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 hover:scale-105 
                  transition transform duration-300">
            <x-heroicon-o-home class="w-6 h-6 text-cyan-400" />
            <span>Home</span>
        </a>
        <a href="{{ route('portfolio.index') }}" 
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white 
                  hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 hover:scale-105 
                  transition transform duration-300">
            <x-heroicon-o-briefcase class="w-6 h-6 text-amber-400" />
            <span>Portfolio</span>
        </a>
        <a href="{{ route('blog.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white
                  hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 hover:scale-105
                  transition transform duration-300">
            <x-heroicon-o-newspaper class="w-6 h-6 text-pink-400" />
            <span>Blog</span>
        </a>
        @if(\App\Models\ServicePage::where('active', 1)->exists())
            <a href="{{ route('services.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg text-white
                      hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 hover:scale-105
                      transition transform duration-300">
                <x-heroicon-o-star class="w-6 h-6 text-yellow-400" />
                <span>Services</span>
            </a>
        @endif
        <a href="{{ route('contact.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white
                  hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 hover:scale-105
                  transition transform duration-300">
            <x-heroicon-o-envelope class="w-6 h-6 text-green-400" />
            <span>Contact</span>
        </a>
    </nav>
</div>



                {{-- Socials --}}
                @php
                    $socialLinks = collect($settings->social_links ?? [])
                        ->filter(fn($link) => filled($link['url'] ?? ''));
                @endphp
                @if($socialLinks->isNotEmpty())
                    <div class="flex justify-center space-x-4">
                        @foreach($socialLinks as $link)
                            @php
                                $url = $link['url'];
                                $domain = \Illuminate\Support\Str::lower(parse_url($url, PHP_URL_HOST) ?? '');
                                $platform = \Illuminate\Support\Str::of($domain)->replace('www.', '')->before('.');
                                $icons = [
                                    'facebook' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0114 6h3v3h-3a1 1 0 00-1 1V12h4l-.5 3h-3.5v7A10 10 0 0022 12z"/></svg>',
                                    'linkedin' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.5 8.5h4V23h-4V8.5zm7.5 0h3.8v2h.1c.5-.9 1.8-2 3.7-2 4 0 4.8 2.6 4.8 6V23h-4v-6.5c0-1.6 0-3.7-2.2-3.7-2.3 0-2.7 1.8-2.7 3.6V23h-4V8.5z"/></svg>',
                                    'github' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.021c0 4.424 2.865 8.18 6.839 9.504.5.092.682-.217.682-.482 0-.237-.009-.866-.014-1.7-2.782.605-3.369-1.34-3.369-1.34-.454-1.154-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.004.071 1.532 1.033 1.532 1.033.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.339-2.22-.253-4.555-1.112-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.748-1.026 2.748-1.026.546 1.379.202 2.398.1 2.65.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.918.678 1.852 0 1.336-.012 2.415-.012 2.741 0 .267.18.58.688.481A10.021 10.021 0 0022 12.021C22 6.484 17.523 2 12 2z" clip-rule="evenodd"/></svg>',
                                    'twitter' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.59-2.46.7a4.28 4.28 0 001.88-2.38 8.56 8.56 0 01-2.72 1.04 4.27 4.27 0 00-7.29 3.9 12.14 12.14 0 01-8.81-4.47 4.27 4.27 0 001.32 5.72 4.22 4.22 0 01-1.94-.54v.05a4.27 4.27 0 003.42 4.18 4.3 4.3 0 01-1.93.07 4.27 4.27 0 003.99 2.96A8.57 8.57 0 012 18.57a12.1 12.1 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19 0-.37-.01-.56A8.72 8.72 0 0022.46 6z"/></svg>',
                                    'instagram' => '<svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true"
  fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
  <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
  <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
  <circle cx="17.5" cy="6.5" r="0.7" fill="currentColor"></circle>
</svg>',

                                    'telegram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>',
                                    't.me' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>',
                                    'youtube' => '<svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true"
  fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
  <rect x="2" y="6" width="20" height="12" rx="3" ry="3"></rect>
  <polygon points="10,9 16,12 10,15" fill="currentColor"></polygon>
</svg>',

                                    'medium' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 7.25c0-.69.56-1.25 1.25-1.25h17.5c.69 0 1.25.56 1.25 1.25v9.5c0 .69-.56 1.25-1.25 1.25H3.25A1.25 1.25 0 012 16.75v-9.5zm5.08 1.45v6.6h.07l3.54-6.6h.03v6.6h1.2V8.7h-1.9l-2.07 3.86L5.87 8.7H4v6.6h1.2V8.7h.01l1.87 3.38 1.98-3.38H7.08z"/></svg>',
                                ];
                                $icon = collect($icons)->first(function($svg, $key) use ($domain) {
                                    return \Illuminate\Support\Str::contains($domain, $key);
                                });
                            @endphp
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="{{ ucfirst($platform) }}">
                                {!! $icon ?? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.9 2a1 1 0 000 2h6.3L3.3 18.9a1 1 0 001.4 1.4L18.6 5.4v6.3a1 1 0 002 0V2z"/></svg>' !!}
                            </a>
                        @endforeach
                    </div>
                @endif
                @if($settings?->footer_copyright)
                    <p class="text-center text-xs text-gray-500 dark:text-gray-400">{{ $settings->footer_copyright }}</p>
                @endif
            </div>
        </aside>

        {{-- Content area --}}
        <div class="flex-1 flex flex-col">
            <header class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 p-4 shadow">
                <button @click="open = !open" class="text-gray-500 focus:outline-none" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-semibold">{{ $settings->site_name ?? 'My Site' }}</span>
            </header>

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <div id="modal-root"></div>
    @if($customHeaderAndBody?->body_code)
        {!! $customHeaderAndBody->body_code !!}
    @endif
</body>
</html>
