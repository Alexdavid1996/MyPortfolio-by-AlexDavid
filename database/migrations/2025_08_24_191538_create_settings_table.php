<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name', 120);
            $table->enum('theme', ['light', 'dark'])->default('light');

            // Example value:
            // [{"name":"facebook","url":"https://facebook.com/you"}]
            $table->json('social_links')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('footer_copyright', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
