@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Settings</h1>
@if(session('status'))
  <div class="mb-4 text-green-600 dark:text-green-400">{{ session('status') }}</div>
@endif

<div class="mb-10">
  <section>
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-cog-6-tooth class="w-6 h-6 mr-1 text-brand" /> General Settings
    </h2>

    <form method="POST" action="{{ route('admin.settings.general') }}" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      @csrf

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
          <input
            type="text"
            name="site_name"
            value="{{ old('site_name', $settings->site_name) }}"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('site_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
          <input
            type="file"
            name="favicon"
            accept=".ico,image/x-icon"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600
                   file:px-4 file:py-2 file:rounded file:border-0
                   file:bg-brand file:text-white hover:file:bg-brand/90
                   file:cursor-pointer"
          />
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: .ico file, 48×48 pixels.</p>
          @error('favicon')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

          @if(!empty($settings->favicon))
            <div class="mt-2 flex items-center gap-3">
              <span class="text-xs text-gray-500 dark:text-gray-400">Current:</span>
              <img src="{{ asset($settings->favicon) }}" alt="Current favicon" class="w-6 h-6 rounded" />
            </div>
          @endif
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home Page Title</label>
          <input
            type="text"
            name="home_page_h1"
            value="{{ old('home_page_h1', $settings->home_page_h1) }}"
            placeholder="Hey there!"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('home_page_h1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home Page Description</label>
          <textarea
            name="home_page_description"
            placeholder="Welcome to my portfolio glad to have you here 🚀"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          >{{ old('home_page_description', $settings->home_page_description) }}</textarea>
          @error('home_page_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Footer Copyright</label>
          <input
            type="text"
            name="footer_copyright"
            value="{{ old('footer_copyright', $settings->footer_copyright) }}"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('footer_copyright')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Social Links (dynamic) --}}
      <div
        x-data="
        {
          links: @js(old('social_links', $settings->social_links ?? [])),
          init() {
            if (!Array.isArray(this.links)) this.links = [];
            if (this.links.length === 0) this.links.push({ url: '' });
          },
          add() { this.links.push({ url: '' }); },
          remove(i) { this.links.splice(i, 1); },
          platformName(u, fallback = '—') {
            try {
              if ((u ?? '').trim() === '') return (this.links.length > 1) ? fallback : '—';
              const host = new URL(u).hostname.replace(/^www\./, '');
              return host.split('.')[0] || fallback;
            } catch(e) { return fallback; }
          }
        }
        "
        x-init="init()"
      >
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Social Links</label>

        <template x-for="(link, index) in links" :key="index">
          <div class="flex items-center gap-2 mb-2">
            <span
              class="px-2 py-0.5 rounded text-xs font-medium border border-white/10 bg-white/5 text-gray-600 dark:text-gray-300"
              x-text="(link.name ?? platformName(link.url)) || '—'"
            ></span>

            <input
              type="url"
              x-model="link.url"
              :name="`social_links[${index}][url]`"
              placeholder="https://"
              class="flex-1 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
            />

            <button type="button" @click="remove(index)" class="text-red-500 hover:text-red-700 px-2">&times;</button>
          </div>
        </template>

        <button type="button" @click="add()" class="mt-2 px-2 py-1 bg-brand text-white rounded text-sm">
          Add Link
        </button>

        @error('social_links.*.url')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">
        Save Settings
      </button>
    </form>
  </section>
</div>

<div class="mb-10">
  <section>
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-photo class="w-6 h-6 mr-1 text-brand" /> Social Share Image (Default)
    </h2>

    <form method="POST" action="{{ route('admin.settings.general') }}" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      @csrf

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Share Image</label>
        <input
          type="file"
          name="default_share_image"
          accept="image/*"
          class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600
                 file:px-4 file:py-2 file:rounded file:border-0
                 file:bg-brand file:text-white hover:file:bg-brand/90
                 file:cursor-pointer"
        />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended 1200×630 (OG/Twitter large). JPG/PNG/WebP.</p>
        @error('default_share_image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

        @if($settings->default_share_image)
          <img src="{{ asset($settings->default_share_image) }}" alt="Current share image" class="mt-2 w-32 h-32 object-cover rounded">
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $settings->default_share_image }}</p>
        @else
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No default share image yet</p>
        @endif
      </div>

      <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
    </form>
  </section>
</div>

<div>
  <section>
    <h2 class="text-xl font-semibold mb-4 flex items-center">
      <x-heroicon-o-user class="w-6 h-6 mr-1 text-brand" /> Account
    </h2>

    <form method="POST" action="{{ route('admin.settings.account') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      @csrf

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            type="email"
            name="email"
            value="{{ old('email', $user->email) }}"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
          <input
            type="password"
            name="current_password"
            placeholder="Current Password (Pass:1235)"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('current_password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
          <input
            type="password"
            name="password"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
          @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
          <input
            type="password"
            name="password_confirmation"
            class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600"
          />
        </div>
      </div>

      <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">
        Save Account
      </button>
    </form>
  </section>
</div>
@endsection
