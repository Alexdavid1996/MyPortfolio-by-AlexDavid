@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        @if(session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" 
              class="space-y-4 bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
            @csrf

            {{-- Title + Description --}}
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-brand">
                    {{ $contact?->title ?? 'Contact' }}
                </h1>
                @if($contact?->description)
                    <p class="mt-2 text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                        {{ $contact->description }}
                    </p>
                @endif
            </div>

            {{-- Form fields --}}
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" required>
                @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" required>
                @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" required>
                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                <textarea id="message" name="message" rows="5" 
                          class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" required>{{ old('message') }}</textarea>
                @error('message')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="captcha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    What is {{ $a }} + {{ $b }}?
                </label>
                <input id="captcha" type="text" name="captcha" 
                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" required>
                @error('captcha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Submit --}}
            <div class="pt-4">
                <button type="submit" 
                        class="bg-brand text-white px-6 py-3 rounded w-full font-semibold hover:bg-brand/90">
                    Send Message
                </button>
            </div>
        </form>
    </div>
@endsection
