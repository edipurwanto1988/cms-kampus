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
        // Clean up any category translations with invalid locale values
        DB::table('category_translations')
            ->where('locale', 'id')
            ->delete();
            
        // Also clean up any other potential invalid locale values
        $validLocales = ['en', 'id', 'fr', 'de', 'es', 'pt', 'it', 'nl', 'pl', 'ru', 'ja', 'ko', 'zh'];
        
        DB::table('category_translations')
            ->whereNotIn('locale', $validLocales)
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as this is just data cleanup
    }
};