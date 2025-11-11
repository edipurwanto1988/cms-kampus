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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('language_code', 10)->nullable()->after('category_id');
            $table->foreign('language_code')->references('code')->on('languages')->onDelete('set null');
            $table->index(['language_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['language_code']);
            $table->dropIndex(['language_code']);
            $table->dropColumn('language_code');
        });
    }
};

