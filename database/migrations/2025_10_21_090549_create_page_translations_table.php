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
        Schema::create('page_translations', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('page_id');
            $table->string('locale');
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->string('content_html')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('source_lang')->nullable();
            $table->boolean('is_machine_translated')->default('0');
            $table->string('mt_provider')->nullable();
            $table->string('mt_confidence')->nullable();
            $table->boolean('human_reviewed')->default('0');
            $table->dateTime('translated_at')->nullable();
            $table->unique(['page_id']);
            $table->unique(['locale']);
            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_translations');
    }
};
