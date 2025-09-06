<?php

namespace App\Http\Controllers;

use App\Models\{BlogPost, BlogCategory, Setting};

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $posts = BlogPost::where('status', 'published')
            ->with('category')
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->paginate(6);
        $settings = Setting::first();
        $canonical = $this->absoluteUrl(route('blog.index'));
        $meta = [
            'title' => 'Blog - ' . ($settings?->site_name ?? ''),
            'description' => $settings?->home_page_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage(),
        ];

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'currentCategory' => null,
            'meta' => $meta,
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug',$slug)->with('category')->firstOrFail();
        $canonical = $this->absoluteUrl(route('blog.show', $post->slug));
        $meta = [
            'title' => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: $post->excerpt,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'article',
            'image' => $this->resolveShareImage($post->cover_image_url),
            'published_time' => optional($post->published_at)->toIso8601String(),
            'modified_time' => optional($post->updated_at)->toIso8601String(),
            'section' => $post->category->name ?? null,
        ];
        if ($post->status !== 'published') {
            $meta['robots'] = 'noindex, nofollow';
        }
        return view('blog.show', compact('post','meta'));
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $categories = BlogCategory::orderBy('name')->get();
        $posts = $category->posts()
            ->where('status', 'published')
            ->with('category')
            ->orderByDesc('published_at')
            ->paginate(6);
        $latestCover = optional($category->posts()->where('status','published')->orderByDesc('published_at')->first())->cover_image_url;
        $canonical = $this->absoluteUrl($category->canonical_url ?: route('blog.category', $category->slug));
        $meta = [
            'title' => $category->meta_title ?: $category->name,
            'description' => $category->meta_description ?: strip_tags($category->description),
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage($latestCover),
        ];

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'currentCategory' => $category,
            'meta' => $meta,
        ]);
    }
}
