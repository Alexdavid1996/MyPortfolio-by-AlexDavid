<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('images:migrate-folders', function () {
    $disk = Storage::disk('public');
    $move = function (string $oldBase, string $newBase) use ($disk) {
        if (!$disk->exists($oldBase)) {
            return;
        }
        foreach ($disk->directories($oldBase) as $year) {
            foreach ($disk->directories($year) as $month) {
                foreach ($disk->directories($month) as $slugDir) {
                    $slug = basename($slugDir);
                    $target = $newBase . '/' . $slug;
                    $disk->makeDirectory($target);
                    foreach ($disk->files($slugDir) as $file) {
                        $filename = basename($file);
                        $disk->move($file, $target . '/' . $filename);
                    }
                    $disk->deleteDirectory($slugDir);
                }
                $disk->deleteDirectory($month);
            }
            $disk->deleteDirectory($year);
        }
    };

    $move('blog', 'blog_posts');
    $move('portfolio', 'portfolio');
    $this->info('Image folders migrated.');
})->purpose('Move existing images into new slug-based folders');
