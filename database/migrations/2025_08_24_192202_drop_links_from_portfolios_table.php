<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('portfolios', 'links')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->dropColumn('links');
            });
        }
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->json('links')->nullable();
        });
    }
};
