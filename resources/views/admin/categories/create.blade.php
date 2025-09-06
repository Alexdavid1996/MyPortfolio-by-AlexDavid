@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">New Category</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4 max-w-xl">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea name="description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            <p class="text-xs text-gray-500 dark:text-gray-400">~60 characters</p>
            @error('meta_title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
            <textarea name="meta_description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('meta_description') }}</textarea>
            <p class="text-xs text-gray-500 dark:text-gray-400">~155 characters</p>
            @error('meta_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded hover:bg-brand/90">Save</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const name = document.querySelector('input[name="name"]');
            const slug = document.querySelector('input[name="slug"]');
            const metaTitle = document.querySelector('input[name="meta_title"]');
            let metaTouched = false;
            metaTitle.addEventListener('input', () => metaTouched = true);

            const debounce = (fn, d = 300) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), d); }; };

            name.addEventListener('input', debounce(e => {
                const val = e.target.value.trim();
                if (!val) { slug.value = ''; if (!metaTouched) metaTitle.value = ''; return; }
                fetch("{{ route('admin.categories.generate-slug') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ name: val })
                })
                .then(r => r.json())
                .then(data => {
                    slug.value = data.slug;
                    if (!metaTouched) metaTitle.value = data.meta_title;
                });
            }));
        });
    </script>
@endsection
