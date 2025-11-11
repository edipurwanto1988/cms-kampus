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
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('category_id')->nullable();
                $table->bigInteger('cover_media_id')->nullable();
                $table->dateTime('published_at')->nullable();
                $table->boolean('is_pinned')->default('0');
                $table->boolean('is_active')->default('1');
                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();
                $table->index(['category_id']);
                $table->index(['cover_media_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
