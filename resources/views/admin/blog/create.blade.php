@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">New Post</h1>
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-4 max-w-3xl">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <select name="category_id" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body</label>
            @include('admin.editor.simple', [
                'field' => 'body',
                'context' => 'blog',
                'slug' => old('slug'),
                'content' => old('body')
            ])
            @error('body')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
            <textarea name="excerpt" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('excerpt') }}</textarea>
            @error('excerpt')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Feature Image</label>
            <div class="text-right">
                <label class="inline-block px-4 py-2 bg-brand text-white rounded hover:bg-brand/90 cursor-pointer">
                    Upload Image
                    <input type="file" name="cover_image" accept="image/*" class="hidden" />
                </label>
            </div>
            @error('cover_image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
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
            const title = document.querySelector('input[name="title"]');
            const slug = document.querySelector('input[name="slug"]');
            const metaTitle = document.querySelector('input[name="meta_title"]');
            const excerpt = document.querySelector('textarea[name="excerpt"]');
            let metaTouched = false;
            let excerptTouched = false;

            metaTitle.addEventListener('input', () => metaTouched = true);
            excerpt.addEventListener('input', () => excerptTouched = true);

            const debounce = (fn, d = 300) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), d); }; };

            title.addEventListener('input', debounce(e => {
                const val = e.target.value;
                if (!val.trim()) { slug.value = ''; if (!metaTouched) metaTitle.value = ''; return; }
                fetch("{{ route('admin.blog.generate-slug') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ title: val })
                }).then(r => r.json()).then(data => {
                    slug.value = data.slug;
                    if (!metaTouched) metaTitle.value = data.meta_title;
                });
            }));
        });
    </script>
@endsection
