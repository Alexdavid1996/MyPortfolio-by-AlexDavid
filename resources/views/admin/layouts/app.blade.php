<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="admin-prefix" content="{{ config('app.admin_prefix') }}">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/css/editor/editor-support.css', 'resources/js/app.js'])
    <style>
        #sidebar-refresh .spinner { display: none; }
        #sidebar-refresh.loading .spinner { display: inline-block; }
        #sidebar-refresh.loading .icon { display: none; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
@php
    use Illuminate\Support\Str;
    $sidebarMessages = \App\Models\Message::orderByDesc('created_at')->paginate(4, ['*'], 'sidebar_page')->withPath(route('admin.messages.sidebar'));
@endphp
<div x-data="{ open: false }" class="min-h-screen flex">
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"></div>
    <aside :class="{'-translate-x-full md:translate-x-0': !open}" class="fixed md:relative z-20 inset-y-0 left-0 w-64 transform md:transform-none transition-transform duration-200 bg-white dark:bg-gray-800 overflow-y-auto">
        <div class="p-6 space-y-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="text-center space-y-2">
                @csrf
                @php $avatar = auth()->user()?->avatar_url ? asset(auth()->user()->avatar_url) : asset('images/avatar-placeholder.png'); @endphp
                <div x-data="{ preview: '{{ $avatar }}' }">
                    <img :src="preview" alt="Avatar" class="mx-auto mb-3 h-28 w-28 rounded-full object-cover border-2 border-brand shadow-lg shadow-brand/30 scale-105 transition-transform duration-300 hover:scale-110 hover:shadow-2xl hover:shadow-brand/60" />
                    <label class="inline-block px-4 py-2 bg-brand text-white rounded hover:bg-brand/90 cursor-pointer">
                        Upload Avatar
                        <input type="file" name="avatar" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0]); $event.target.form.submit();" />
                    </label>
                </div>
            </form>
            @php $user = auth()->user(); @endphp
            @if($user)
                @php $settings = $settings ?? \App\Models\Setting::first(); @endphp
                @include('partials.sidebar-info', ['user' => $user, 'settings' => $settings])
            @endif
            <nav class="space-y-2">
                <a href="{{ route('admin.cv') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.cv') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-identification class="w-6 h-6" />
                    <span>My CV</span>
                </a>
                <a href="{{ route('admin.portfolio.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.portfolio.*') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-briefcase class="w-6 h-6" />
                    <span>Portfolio</span>
                </a>
                <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.blog.*') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-newspaper class="w-6 h-6" />
                    <span>Blog</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.services.*') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-star class="w-6 h-6" />
                    <span>Services</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.categories.*') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-tag class="w-6 h-6" />
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.messages.*') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-inbox class="w-6 h-6" />
                    <span>Inbox</span>
                </a>
                <a href="{{ route('admin.contact') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.contact') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-envelope class="w-6 h-6" />
                    <span>Contact</span>
                </a>
                <a href="{{ route('admin.gsc') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.gsc') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-globe-alt class="w-6 h-6" />
                    <span>GSC</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition transform duration-300 hover:scale-105 hover:bg-brand/20 hover:shadow-md hover:shadow-brand/40 {{ request()->routeIs('admin.settings') ? 'bg-brand/10 text-brand' : 'text-gray-700 dark:text-gray-300' }}">
                    <x-heroicon-o-cog-6-tooth class="w-6 h-6" />
                    <span>Settings</span>
                </a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white transition">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-6 h-6" />
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 p-4 shadow">
            <button @click="open = !open" class="text-gray-500 focus:outline-none" aria-label="Toggle menu">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </button>
            <span class="font-semibold">Admin</span>
        </header>
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
    <aside class="hidden md:block fixed md:relative z-20 inset-y-0 right-0 w-64 bg-white dark:bg-gray-800 overflow-y-auto">
        <div class="p-6 space-y-4">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <x-heroicon-o-inbox class="w-6 h-6" />
                <span>Messages</span>
                <button type="button" id="sidebar-refresh" class="ml-auto flex items-center gap-1 px-2 py-1 text-sm bg-brand text-white rounded hover:bg-brand/90 disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-heroicon-o-arrow-path class="w-4 h-4 icon" />
                    <x-heroicon-o-arrow-path class="w-4 h-4 animate-spin spinner" />
                </button>
            </h2>
            <div id="sidebar-messages">
                @include('admin.messages.sidebar', ['messages' => $sidebarMessages])
            </div>
            <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-1 px-3 py-1 mt-4 text-sm bg-brand text-white rounded hover:bg-brand/90 mx-auto">View All</a>
        </div>
    </aside>
</div>
<div id="modal-root"></div>
@if(session('status'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    window.showAlert(@json(session('status')));
});
</script>
@endif
</body>
</html>
