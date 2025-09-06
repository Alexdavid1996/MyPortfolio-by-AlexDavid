<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class AdminBlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:140|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['description'] = Purifier::clean($data['description'] ?? '', 'default');
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?: $data['name']);
        $data['canonical_url'] = route('blog.category', $data['slug'], false);
        $data['meta_title'] = trim($data['meta_title'] ?? '') === '' ? $data['name'] : $data['meta_title'];

        BlogCategory::create($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category created');
    }

    public function edit(BlogCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:140|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['description'] = Purifier::clean($data['description'] ?? '', 'default');
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?: $data['name'], $category->id);
        $data['canonical_url'] = route('blog.category', $data['slug'], false);
        $data['meta_title'] = trim($data['meta_title'] ?? '') === '' ? $data['name'] : $data['meta_title'];

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('status', 'Category deleted');
    }

    public function generateSlug(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $slug = $this->generateUniqueSlug($request->name);
        return response()->json(['slug' => $slug, 'meta_title' => $request->name]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        while (BlogCategory::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . Str::random(4);
        }
        return $slug;
    }

    /**
     * Store an uploaded image inside "categories" folder and return web path.
     */
    private function storeImage($file, string $slug): string
    {
        $folder = 'categories/' . now()->format('Y/m') . '/' . $slug;
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($basename) ?: 'image';
        $filename = $safeName . '.' . $ext;

        $i = 1;
        $candidate = $filename;
        while (Storage::disk('public')->exists($folder . '/' . $candidate)) {
            $candidate = $safeName . '-' . $i++ . '.' . $ext;
        }

        $path = $file->storeAs($folder, $candidate, 'public');
        return 'storage/' . $path;
    }

    public function uploadImage(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|max:8192',
            'slug'  => 'required|string',
        ]);

        $path = $this->storeImage($request->file('image'), $data['slug']);

        return response()->json(['location' => asset($path)]);
    }
}

