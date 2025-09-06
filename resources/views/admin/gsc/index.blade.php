@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">GSC</h1>

@if(session('status'))
    <div class="mb-4 text-green-600 dark:text-green-400">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('admin.gsc.store') }}" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    @csrf

    {{-- Google Search Console Verification --}}
    <div>
        <label for="verification_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Google Search Console Verification Code
        </label>
        <input
            id="verification_code"
            type="text"
            name="verification_code"
            value="{{ old('verification_code', $google?->verification_code) }}"
            placeholder="e.g. ecf7aiUoQthH4OLMm2aZw5-RvJhEnD3wiSwoT_OilME"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
            autocomplete="off"
            spellcheck="false"
            inputmode="latin"
        />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            GSC HTML tag example:
            <code>&lt;meta name="google-site-verification" content="s2oQ-4OLMm2a_Zw5-RvJh" /&gt;</code>
            . Add only the <strong>content</strong> value, for example
            <code>s2oQ-4OLMm2a_Zw5-RvJh</code>. This will render on all user pages, not on admin.
        </p>
        @error('verification_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Custom Head Code --}}
    <div>
        <label for="head_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Custom Header Code
        </label>
        <textarea
            id="head_code"
            name="head_code"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
            rows="4"
            placeholder="Paste code to inject before &lt;/head&gt; (e.g. meta tags, analytics, verification)…"
        >{{ old('head_code', $custom?->head_code) }}</textarea>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            This code will be inserted <strong>before</strong> the closing <code>&lt;/head&gt;</code> tag on all user-facing pages
            (Home, Blog, Portfolio, Category, Post, index/show). Not on admin pages.
        </p>
        @error('head_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Custom Body Code --}}
    <div>
        <label for="body_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Custom Body Code
        </label>
        <textarea
            id="body_code"
            name="body_code"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
            rows="4"
            placeholder="Paste code to inject before &lt;/body&gt; (e.g. FB Pixel, GA, chat widgets)…"
        >{{ old('body_code', $custom?->body_code) }}</textarea>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            This code will be inserted <strong>before</strong> the closing <code>&lt;/body&gt;</code> tag on all user-facing pages
            (Home, Blog, Portfolio, Category, Post, index/show). Not on admin pages.
        </p>
        @error('body_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">
        Save
    </button>
</form>
@endsection
