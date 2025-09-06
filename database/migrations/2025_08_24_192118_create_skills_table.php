<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('category', 100)->nullable()->index();
            $table->enum('level', ['beginner','intermediate','advanced','expert'])->default('intermediate')->index();
            $table->unsignedTinyInteger('years_experience')->nullable();
            $table->string('icon_key', 100)->nullable(); // e.g. "laravel", "mysql"
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
