<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class AdminPortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:portfolios,slug',
            'short_description'=> 'nullable|string',
            'description'      => 'nullable|string',
            'tech_stack'       => 'nullable|array',
            'tech_stack.*'     => 'string|max:100',
            'thumbnail'        => 'nullable|image|max:8192',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['description'] = Purifier::clean($data['description'] ?? '', 'default');
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?: $data['title']);
        $data['short_description'] = trim($data['short_description'] ?? '') === ''
            ? $this->generateShortDescription($data['description'], $data['title'])
            : $data['short_description'];
        $data['meta_title'] = trim($data['meta_title'] ?? '') === '' ? $data['title'] : $data['meta_title'];
        $data['meta_description'] = trim($data['meta_description'] ?? '') === '' ? $data['title'] : $data['meta_description'];

        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?: now();
        } else {
            $data['published_at'] = null;
        }

        $category = PortfolioCategory::first();
        if (!$category) {
            $category = PortfolioCategory::create(['name' => 'General', 'slug' => 'general']);
        }
        $data['category_id'] = $category->id;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $this->storeImage($request->file('thumbnail'), $data['slug']);
        }

        $data['tech_stack'] = array_filter($data['tech_stack'] ?? []);
        if (empty($data['tech_stack'])) {
            $data['tech_stack'] = null;
        }

        $portfolio = Portfolio::create($data);

        return redirect()->route('admin.portfolio.edit', $portfolio)->with('status', 'Successfully updated!');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolio.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:portfolios,slug,' . $portfolio->id,
            'short_description'=> 'nullable|string',
            'description'      => 'nullable|string',
            'tech_stack'       => 'nullable|array',
            'tech_stack.*'     => 'string|max:100',
            'thumbnail'        => 'nullable|image|max:8192',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['description'] = Purifier::clean($data['description'] ?? '', 'default');
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?: $data['title'], $portfolio->id);
        $data['short_description'] = trim($data['short_description'] ?? '') === ''
            ? $this->generateShortDescription($data['description'], $data['title'])
            : $data['short_description'];
        $data['meta_title'] = trim($data['meta_title'] ?? '') === '' ? $data['title'] : $data['meta_title'];
        $data['meta_description'] = trim($data['meta_description'] ?? '') === '' ? $data['title'] : $data['meta_description'];

        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?: now();
        } else {
            $data['published_at'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($portfolio->thumbnail_url) {
                $path = public_path($portfolio->thumbnail_url);
                if ($path && file_exists($path)) {
                    @unlink($path);
                }
            }
            $data['thumbnail_url'] = $this->storeImage($request->file('thumbnail'), $data['slug']);
        }

        $data['tech_stack'] = array_filter($data['tech_stack'] ?? []);
        if (empty($data['tech_stack'])) {
            $data['tech_stack'] = null;
        }

        $portfolio->update($data);

        return redirect()->back()->with('status', 'Successfully updated!');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->thumbnail_url) {
            $path = public_path($portfolio->thumbnail_url);
            if ($path && file_exists($path)) {
                @unlink($path);
            }
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('status', 'Portfolio item deleted');
    }

    public function generateSlug(Request $request)
    {
        $request->validate(['title' => 'required|string']);
        $slug = $this->generateUniqueSlug($request->title);

        return response()->json([
            'slug' => $slug,
            'meta_title' => $request->title,
            'meta_description' => $request->title,
        ]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;

        while (
            Portfolio::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . Str::random(4);
        }

        return $slug;
    }

    private function generateShortDescription(?string $body, string $title): ?string
    {
        if (!$body) {
            return null;
        }
        $plain = preg_replace('/```[\s\S]*?```/m', '', $body);
        $plain = preg_replace('/^#+\s.*$/m', '', $plain);
        $plain = strip_tags($plain);
        $plain = preg_replace('/\s+/', ' ', $plain);
        $plain = trim($plain);
        if ($plain === '') {
            return null;
        }
        return Str::words($plain, 10, '...');
    }

    /**
     * Store an uploaded image in public/image/portfolio and return relative path.
     */
    private function storeImage($file, string $slug): string
    {
        $folder = public_path('image/portfolio');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = uniqid($slug . '_') . '.' . $ext;
        $file->move($folder, $filename);

        return 'image/portfolio/' . $filename;
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
