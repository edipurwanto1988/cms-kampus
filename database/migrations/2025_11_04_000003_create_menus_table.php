<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('name');
                $table->string('location')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('menu_id');
                $table->bigInteger('parent_id')->nullable();
                $table->string('title');
                $table->string('url');
                $table->integer('position')->default(0);
                $table->string('target')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['menu_id']);
                $table->index(['parent_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};


