<?php

namespace App\Http\Controllers;

use App\Models\Setting;

abstract class Controller
{
    protected function absoluteUrl(string $path): string
    {
        return str_starts_with($path, 'http') ? $path : asset($path);
    }

    protected function resolveShareImage(?string $primary = null): string
    {
        $settings = Setting::first();
        $path = $primary;

        if (!$path && $settings?->default_share_image) {
            $path = $settings->default_share_image;
        }

        if (!$path && $settings?->favicon) {
            $path = $settings->favicon;
        }

        return $path ? $this->absoluteUrl($path) : asset('favicon.ico');
    }
}
