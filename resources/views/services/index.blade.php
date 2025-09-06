@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-brand">
                {{ $page?->title ?? 'Services' }}
            </h1>
            @if($page?->description)
                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    {{ $page->description }}
                </p>
            @endif
        </div>

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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border 
             bg-emerald-500/20 text-emerald-300 border-emerald-500/30">
    {{ $service->price }}
</span>
                        <a href="{{ route('contact.index') }}"
   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border 
          bg-sky-500/20 text-sky-300 border-sky-500/30 hover:bg-sky-500/30 hover:text-sky-200 transition">
    Inquiry Now!
</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
@endsection
