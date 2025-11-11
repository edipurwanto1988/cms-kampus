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
        Schema::create('menu_item_translations', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('menu_item_id');
            $table->string('locale');
            $table->string('label');
            $table->string('slug_override')->nullable();
            $table->string('source_lang')->nullable();
            $table->boolean('is_machine_translated')->default('0');
            $table->string('mt_provider')->nullable();
            $table->string('mt_confidence')->nullable();
            $table->boolean('human_reviewed')->default('0');
            $table->dateTime('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['menu_item_id']);
            $table->unique(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_translations');
    }
};
