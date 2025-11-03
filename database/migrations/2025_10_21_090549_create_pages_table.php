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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('key_name')->unique()->nullable();
            $table->dateTime('published_at')->nullable();
            $table->boolean('is_active')->default('1');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->nullable()->default('CURRENT_TIMESTAMP');
            $table->timestamp('updated_at')->nullable()->default('CURRENT_TIMESTAMP');
            $table->unique(['key_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
