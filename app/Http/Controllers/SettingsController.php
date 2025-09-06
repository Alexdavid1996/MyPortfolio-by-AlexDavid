<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $user = auth()->user();

        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function updateGeneral(Request $request)
    {
        $settings = Setting::first() ?? new Setting;

        $validated = $request->validate([
            'site_name' => 'sometimes|required|string|max:255',
            'favicon' => 'nullable|mimes:jpg,jpeg,png,ico|max:1024',
            'default_share_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'social_links' => 'sometimes|array',
            'social_links.*.url' => 'nullable|url',
            'footer_copyright' => 'sometimes|nullable|string|max:255',
            'home_page_h1' => 'sometimes|nullable|string|max:255',
            'home_page_description' => 'sometimes|nullable|string',
        ]);
        $socialLinks = null;
        if (array_key_exists('social_links', $validated)) {
            $socialLinks = collect($validated['social_links'] ?? [])
                ->map(function ($link) {
                    $url = trim($link['url'] ?? '');
                    if ($url === '') {
                        return null;
                    }
                    $domain = parse_url($url, PHP_URL_HOST) ?: '';
                    $name = Str::of($domain)->replace('www.', '')->before('.');
                    return [
                        'name' => Str::slug($name),
                        'url' => $url,
                    ];
                })
                ->filter()
                ->values()
                ->all();
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = uniqid('favicon_').'.'.$file->getClientOriginalExtension();
            $folder = public_path('favicon');
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $file->move($folder, $filename);

            if ($settings->favicon) {
                $old = public_path($settings->favicon);
                if ($old && file_exists($old)) {
                    @unlink($old);
                }
            }

            $validated['favicon'] = 'favicon/'.$filename;
        }

        if ($request->hasFile('default_share_image')) {
            $file = $request->file('default_share_image');
            $filename = uniqid('share_').'.'.$file->getClientOriginalExtension();
            $folder = public_path('shared');
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $file->move($folder, $filename);
            if ($settings->default_share_image) {
                $old = public_path($settings->default_share_image);
                if ($old && file_exists($old)) {
                    @unlink($old);
                }
            }
            $validated['default_share_image'] = 'shared/'.$filename;
        }

        if (array_key_exists('site_name', $validated)) {
            $settings->site_name = $validated['site_name'];
        }

        if (array_key_exists('favicon', $validated)) {
            $settings->favicon = $validated['favicon'];
        }

        if (array_key_exists('default_share_image', $validated)) {
            $settings->default_share_image = $validated['default_share_image'];
        }

        if (! is_null($socialLinks)) {
            $settings->social_links = $socialLinks;
        }

        if (array_key_exists('footer_copyright', $validated)) {
            $settings->footer_copyright = $validated['footer_copyright'];
        }

        if (array_key_exists('home_page_h1', $validated)) {
            $settings->home_page_h1 = $validated['home_page_h1'];
        }

        if (array_key_exists('home_page_description', $validated)) {
            $settings->home_page_description = $validated['home_page_description'];
        }

        $settings->save();

        return redirect()->route('admin.settings')->with('status', 'Settings updated');
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->email = $validated['email'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.settings')->with('status', 'Account updated');
    }

    public function updateContactEmail(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email',
        ]);

        $settings = Setting::first();
        if (! $settings) {
            $settings = Setting::create($validated);
        } else {
            $settings->update($validated);
        }

        return redirect()->route('admin.cv')->with('status', 'Contact email updated');
    }
}
