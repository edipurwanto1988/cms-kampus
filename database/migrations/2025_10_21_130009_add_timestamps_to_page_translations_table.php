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
        if (Schema::hasTable('page_translations')) {
            $hasCreatedAt = Schema::hasColumn('page_translations', 'created_at');
            $hasUpdatedAt = Schema::hasColumn('page_translations', 'updated_at');
            
            if (!$hasCreatedAt || !$hasUpdatedAt) {
                Schema::table('page_translations', function (Blueprint $table) use ($hasCreatedAt, $hasUpdatedAt) {
                    if (!$hasCreatedAt) {
                        $table->timestamp('created_at')->nullable();
                    }
                    if (!$hasUpdatedAt) {
                        $table->timestamp('updated_at')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
