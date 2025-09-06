<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Setting;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = Portfolio::where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->paginate(6);
        $settings = Setting::first();
        $canonical = $this->absoluteUrl(route('portfolio.index'));
        $meta = [
            'title' => 'Portfolio - ' . ($settings?->site_name ?? ''),
            'description' => $settings?->home_page_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage(),
        ];
        return view('portfolio.index', compact('items','meta'));
    }

    public function show(string $slug)
    {
        $item = Portfolio::where('slug',$slug)->where('status','published')->firstOrFail();
        $canonical = $this->absoluteUrl(route('portfolio.show', $item->slug));
        $meta = [
            'title' => $item->meta_title ?: $item->title,
            'description' => $item->meta_description ?: $item->short_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'article',
            'image' => $this->resolveShareImage($item->thumbnail_url),
        ];
        return view('portfolio.show', compact('item','meta'));
    }
}
