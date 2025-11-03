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
        Schema::create('settings', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('key_name')->unique();
            $table->string('group_name')->nullable()->default('general');
            $table->string('input_type')->nullable()->default('text');
            $table->boolean('is_multilang')->default('1');
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
        Schema::dropIfExists('settings');
    }
};
