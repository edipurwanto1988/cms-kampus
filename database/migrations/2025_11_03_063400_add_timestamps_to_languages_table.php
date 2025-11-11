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
        if (Schema::hasTable('languages')) {
            $hasCreatedAt = Schema::hasColumn('languages', 'created_at');
            $hasUpdatedAt = Schema::hasColumn('languages', 'updated_at');

            if (!$hasCreatedAt || !$hasUpdatedAt) {
                Schema::table('languages', function (Blueprint $table) use ($hasCreatedAt, $hasUpdatedAt) {
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
        if (Schema::hasTable('languages')) {
            if (Schema::hasColumn('languages', 'created_at') || Schema::hasColumn('languages', 'updated_at')) {
                Schema::table('languages', function (Blueprint $table) {
                    $columnsToDrop = [];
                    if (Schema::hasColumn('languages', 'created_at')) {
                        $columnsToDrop[] = 'created_at';
                    }
                    if (Schema::hasColumn('languages', 'updated_at')) {
                        $columnsToDrop[] = 'updated_at';
                    }
                    if (!empty($columnsToDrop)) {
                        $table->dropColumn($columnsToDrop);
                    }
                });
            }
        }
    }
};



