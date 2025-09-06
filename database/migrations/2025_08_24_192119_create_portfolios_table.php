<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->text('short_description');
            $table->longText('description')->nullable();
            $table->json('tech_stack')->nullable(); // ["Laravel","MySQL","Tailwind"]
            $table->string('thumbnail_url')->nullable();
            $table->json('gallery_urls')->nullable(); // ["img1.jpg","img2.jpg"]
            $table->boolean('featured')->default(false)->index();
            $table->enum('status', ['draft','published','archived'])->default('draft')->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
