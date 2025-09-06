<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('home_page_h1')->nullable()->after('favicon');
            $table->text('home_page_description')->nullable()->after('home_page_h1');
        });

        DB::table('settings')->update([
            'home_page_h1' => 'Hey there!',
            'home_page_description' => 'Welcome to my portfolio glad to have you here 🚀',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['home_page_h1', 'home_page_description']);
        });
    }
};
