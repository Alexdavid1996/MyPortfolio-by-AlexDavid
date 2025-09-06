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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();

            // If you’ll have only one owner, this still keeps it tidy and future-proof
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name', 100); // e.g., English, Portuguese, Chinese
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'conversational', 'fluent', 'native'])
                  ->default('fluent')
                  ->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
