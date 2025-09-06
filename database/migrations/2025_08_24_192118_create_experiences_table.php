<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 150);
            $table->string('role_title', 150);
            $table->string('location', 150)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false)->index();
            $table->text('summary');
            $table->json('responsibilities')->nullable(); // ["Did X","Built Y"]
            $table->string('logo_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->index(['start_date','end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
