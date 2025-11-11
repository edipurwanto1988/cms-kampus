<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menu_translations')) {
            Schema::create('menu_translations', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('menu_id')->unsigned();
                $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
                $table->string('locale', 10);
                $table->string('name');
                $table->timestamps();
                
                $table->unique(['menu_id', 'locale']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_translations');
    }
};