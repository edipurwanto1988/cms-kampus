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
        Schema::table('category_translations', function (Blueprint $table) {
            // Drop the unique constraint on category_id to allow multiple translations per category
            $table->dropUnique(['category_id']);
            
            // Add composite unique constraint for category_id + locale
            $table->unique(['category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique(['category_id', 'locale']);
            
            // Restore the original unique constraint on category_id
            $table->unique(['category_id']);
        });
    }
};