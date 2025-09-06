<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class AdminBlogPostController extends Controller
{
    public function index()
    {
        // Prefer latest publish/update. Fallback to created_at.
        $posts = BlogPost::with('category')
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:blog_posts,slug',
            'category_id'      => 'required|exists:blog_categories,id',
            'excerpt'          => 'nullable|string',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|max:8192', // up to 8MB
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        // Clean body HTML using the default purifier profile
        $data['body'] = Purifier::clean($data['body'], 'default');

        // Slug, excerpt, meta title
        $data['slug']          = $this->generateUniqueSlug($data['slug'] ?: $data['title']);
        $data['excerpt']       = trim($data['excerpt'] ?? '') === '' ? $this->generateExcerpt($data['body'], $data['title']) : $data['excerpt'];
        $data['meta_title']    = trim($data['meta_title'] ?? '') === '' ? $data['title'] : $data['meta_title'];
        $data['published_at']  = now();     // set as published now
        $data['status']        = 'published';

        // Cover image (optional)
        if ($request->hasFile('cover_image')) {
            $data['cover_image_url'] = $this->storeImage($request->file('cover_image'), $data['slug']);
        }

        $blog = BlogPost::create($data);

        return redirect()->route('admin.blog.edit', $blog)->with('status', 'Successfully updated!');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blog.edit', ['post' => $blog, 'categories' => $categories]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:blog_posts,slug,' . $blog->id,
            'category_id'      => 'required|exists:blog_categories,id',
            'excerpt'          => 'nullable|string',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|max:8192',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        // Clean body HTML using the default purifier profile
        $data['body'] = Purifier::clean($data['body'], 'default');

        // Slug, excerpt, meta title
        $data['slug']       = $this->generateUniqueSlug($data['slug'] ?: $data['title'], $blog->id);
        $data['excerpt']    = trim($data['excerpt'] ?? '') === '' ? $this->generateExcerpt($data['body'], $data['title']) : $data['excerpt'];
        $data['meta_title'] = trim($data['meta_title'] ?? '') === '' ? $data['title'] : $data['meta_title'];

        // If a new cover image is uploaded, delete the old one and store the new one
        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image_url) {
                $path = public_path($blog->cover_image_url);
                if ($path && file_exists($path)) {
                    @unlink($path);
                }
            }
            $data['cover_image_url'] = $this->storeImage($request->file('cover_image'), $data['slug']);
        }

        $blog->update($data);

        return redirect()->back()->with('status', 'Successfully updated!');
    }

    public function destroy(BlogPost $blog)
    {
        // Clean up cover image from disk if present
        if ($blog->cover_image_url) {
            $path = public_path($blog->cover_image_url);
            if ($path && file_exists($path)) {
                @unlink($path);
            }
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Post deleted');
    }

    public function generateSlug(Request $request)
    {
        $request->validate(['title' => 'required|string']);
        $slug = $this->generateUniqueSlug($request->title);

        return response()->json(['slug' => $slug, 'meta_title' => $request->title]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;

        while (
            BlogPost::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . Str::random(4);
        }

        return $slug;
    }

    private function generateExcerpt(string $body, string $title): ?string
    {
        $plain = preg_replace('/```[\s\S]*?```/m', '', $body);   // remove code blocks
        $plain = preg_replace('/^#+\s.*$/m', '', $plain);        // remove headings
        $plain = strip_tags($plain);
        $plain = preg_replace('/\s+/', ' ', $plain);
        $plain = trim($plain);

        if ($plain === '') {
            return null;
        }

        return Str::words($plain, 10, '...');
    }

    /**
     * Store an uploaded image in public/image/blog_posts and return relative path.
     */
    private function storeImage($file, string $slug): string
    {
        $folder = public_path('image/blog_posts');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = uniqid($slug . '_') . '.' . $ext;
        $file->move($folder, $filename);

        return 'image/blog_posts/' . $filename;
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
