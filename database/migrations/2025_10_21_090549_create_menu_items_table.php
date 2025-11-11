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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('menu_id');
            $table->bigInteger('parent_id')->nullable();
            $table->string('type')->default('internal');
            $table->string('target_ref')->nullable();
            $table->string('url_external')->nullable();
            $table->integer('sort_order')->default('0');
            $table->boolean('is_active')->default('1');
            $table->timestamps();
            $table->index(['menu_id']);
            $table->index(['parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
