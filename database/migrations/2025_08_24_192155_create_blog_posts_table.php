<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('category_id')->constrained('blog_categories')->cascadeOnDelete();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->enum('status', ['draft','scheduled','published','archived'])->default('draft')->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->string('cover_image_url')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->unsignedTinyInteger('reading_time_minutes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
