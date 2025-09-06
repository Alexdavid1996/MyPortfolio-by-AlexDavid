@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Services</h1>
@if(session('status'))
    <div class="mb-4 text-green-600 dark:text-green-400">{{ session('status') }}</div>
@endif

<div class="space-y-10">
    {{-- Service Page Settings --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-star class="w-6 h-6 mr-1 text-brand" /> Service Page Settings
        </h2>
        <form method="POST" action="{{ route('admin.services.page') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1"
                        class="sr-only peer"
                        @checked(old('active', $page->active ?? false))>
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-brand relative">
                        <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></span>
                    </div>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('description', $page->description ?? '') }}</textarea>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                    <textarea name="meta_description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                    @error('meta_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
        </form>
    </section>

    {{-- Services Management --}}
    <section>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <x-heroicon-o-star class="w-6 h-6 mr-1 text-brand" /> Services Management
        </h2>
        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow" x-data="{ items: @js(old('service_description', [''])) }">
                @csrf
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Title</label>
                        <input type="text" name="service_title" value="{{ old('service_title') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('service_title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Price</label>
                        <input type="text" name="price" value="{{ old('price') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div x-data="{ }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Description</label>
                        <template x-for="(item, index) in items" :key="index">
                            <input type="text" :name="`service_description[${index}]`" x-model="items[index]" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        </template>
                        <button type="button" @click="items.push('')" class="mt-2 px-2 py-1 bg-brand text-white rounded text-sm">Add</button>
                        @error('service_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Add Service</button>
            </form>

            @foreach($services as $service)
            @php $desc = preg_split('/\r?\n/', $service->service_description); @endphp
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4" x-data="{ items: @js($desc) }">
                <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Title</label>
                            <input type="text" name="service_title" value="{{ old('service_title', $service->service_title) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Price</label>
                            <input type="text" name="price" value="{{ old('price', $service->price) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Description</label>
                            <template x-for="(item, index) in items" :key="index">
                                <input type="text" :name="`service_description[${index}]`" x-model="items[index]" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                            </template>
                            <button type="button" @click="items.push('')" class="mt-2 px-2 py-1 bg-brand text-white rounded text-sm">Add</button>
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
                </form>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="text-right" data-confirm="Delete this service?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline inline-flex items-center"><x-heroicon-o-trash class="w-5 h-5 mr-1" />Delete</button>
                </form>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

