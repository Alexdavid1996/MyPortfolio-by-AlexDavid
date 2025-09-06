<?php

namespace App\Http\Controllers;

use App\Models\{Setting, Experience, Skill, Language, Portfolio, BlogPost, User, Service, ServicePage};

class HomeController extends Controller
{
    public function index()
    {
        $settings   = Setting::first();
        $user       = User::first();
        $experiences = Experience::orderByDesc('is_current')
            ->orderByDesc('end_date')
            ->orderBy('sort_order')
            ->paginate(5, ['*'], 'experience_page');
        $skills      = Skill::orderBy('category')->orderBy('sort_order')->get();
        $languages   = Language::orderBy('sort_order')->get();
        $servicePage = ServicePage::where('active', 1)->first();
        $services    = $servicePage
            ? Service::orderByDesc('id')->paginate(3, ['*'], 'services_page')
            : collect();
        $portfolio   = Portfolio::where('status','published')
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->paginate(3, ['*'], 'projects_page');
        $posts       = BlogPost::where('status','published')
            ->with('category')
            ->orderByDesc('published_at')
            ->paginate(3, ['*'], 'posts_page');
        $canonical = $this->absoluteUrl(route('home'));
        $meta = [
            'title' => $settings?->site_name,
            'description' => $settings?->home_page_description,
            'canonical' => $canonical,
            'url' => $canonical,
            'type' => 'website',
            'image' => $this->resolveShareImage(),
        ];

        return view('home.index', compact('settings','experiences','skills','languages','services','servicePage','portfolio','posts','meta','user'));
    }
}
