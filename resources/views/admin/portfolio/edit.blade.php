@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Portfolio Item</h1>
    <form method="POST" action="{{ route('admin.portfolio.update', $portfolio) }}" enctype="multipart/form-data" class="space-y-4 max-w-3xl">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" name="title" value="{{ old('title', $portfolio->title) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $portfolio->slug) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            @include('admin.editor.simple', [
                'field' => 'description',
                'context' => 'portfolio',
                'slug' => old('slug', $portfolio->slug),
                'content' => old('description', $portfolio->description)
            ])
            @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Short Description</label>
            <textarea name="short_description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('short_description', $portfolio->short_description) }}</textarea>
            @error('short_description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add Budges</label>
            <div id="tech-stack" class="flex flex-wrap gap-2 mb-2"></div>
            <input type="text" id="tech-stack-input" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Add tech and press Enter">
            @error('tech_stack')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thumbnail</label>
            <div class="text-right">
                <label class="btn cursor-pointer">
                    Upload Image
                    <input type="file" name="thumbnail" accept="image/*" class="hidden" />
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">JPEG/PNG/WebP recommended. Max ~2–5MB.</p>
            @error('thumbnail')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            @if($portfolio->thumbnail_url)
                <img src="{{ asset($portfolio->thumbnail_url) }}" alt="Current thumbnail" class="mt-2 w-32 h-32 object-cover rounded">
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="status" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                <option value="draft" {{ old('status', $portfolio->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $portfolio->status) === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Published At</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($portfolio->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            @error('published_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $portfolio->meta_title) }}" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
            <p class="text-xs text-gray-500 dark:text-gray-400">~60 characters</p>
            @error('meta_title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
            <textarea name="meta_description" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('meta_description', $portfolio->meta_description) }}</textarea>
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
            const metaDescription = document.querySelector('textarea[name="meta_description"]');
            const shortDescription = document.querySelector('textarea[name="short_description"]');
            const techInput = document.getElementById('tech-stack-input');
            const techWrapper = document.getElementById('tech-stack');
            let metaTitleTouched = false;
            let metaDescTouched = false;
            let shortDescTouched = false;
            let slugTouched = false;

            metaTitle.addEventListener('input', () => metaTitleTouched = true);
            metaDescription.addEventListener('input', () => metaDescTouched = true);
            shortDescription.addEventListener('input', () => shortDescTouched = true);
            slug.addEventListener('input', () => slugTouched = true);

            function addTech(value) {
                value = value.trim();
                if (!value) return;
                const chip = document.createElement('span');
                chip.className = 'badge flex items-center gap-1';
                chip.textContent = value;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ml-1 text-xs';
                btn.innerHTML = '&times;';
                btn.addEventListener('click', () => chip.remove());
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'tech_stack[]';
                hidden.value = value;
                chip.appendChild(btn);
                chip.appendChild(hidden);
                techWrapper.appendChild(chip);
            }

            techInput?.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addTech(techInput.value);
                    techInput.value = '';
                }
            });

            const existingTech = @json(old('tech_stack', $portfolio->tech_stack ?? []));
            existingTech.forEach(addTech);

            const debounce = (fn, d = 300) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), d); }; };

            title.addEventListener('input', debounce(e => {
                const val = e.target.value.trim();
                if (!val) {
                    if (!slugTouched) slug.value = '';
                    if (!metaTitleTouched) metaTitle.value = '';
                    if (!metaDescTouched) metaDescription.value = '';
                    return;
                }
                fetch("{{ route('admin.portfolio.generate-slug') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ title: val })
                })
                .then(r => r.json())
                .then(data => {
                    if (!slugTouched) slug.value = data.slug;
                    if (!metaTitleTouched) metaTitle.value = data.meta_title;
                    if (!metaDescTouched) metaDescription.value = data.meta_description;
                })
                .catch(() => {});
            }));
        });
    </script>
@endsection
