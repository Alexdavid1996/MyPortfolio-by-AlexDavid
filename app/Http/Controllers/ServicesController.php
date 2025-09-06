<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicePage;

class ServicesController extends Controller
{
    public function index()
    {
        $page = ServicePage::where('active', 1)->firstOrFail();
        $services = Service::orderByDesc('id')->paginate(6);
        $canonical = $this->absoluteUrl(route('services.index'));
        $meta = [
            'title' => $page?->title ?? 'Services',
            'description' => $page?->meta_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage($page?->feature_image_url),
        ];

        return view('services.index', compact('page', 'services', 'meta'));
    }
}
