<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $purifierPath = storage_path('app/purifier');
        File::ensureDirectoryExists($purifierPath);
        @chmod($purifierPath, 0755);

        if (app()->runningInConsole()) {
            return;
        }

        if (!File::exists(base_path('.env')) || !File::exists(storage_path('installer.lock'))) {
            return;
        }

        // Share common data with every Blade view
        View::composer('*', function ($view) {
            try {
                // Only query if tables exist. This avoids "no such table" errors in tests or fresh installs.
                $settings = Schema::hasTable('settings') ? Setting::first() : null;
                $user     = Schema::hasTable('users') ? User::first() : null;
            } catch (\Throwable $e) {
                $settings = null;
                $user = null;
            }

            $view->with('settings', $settings);
            $view->with('user', $user);
        });
    }
}
