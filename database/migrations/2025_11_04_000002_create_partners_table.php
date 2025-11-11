<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('name');
                $table->bigInteger('logo_media_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['logo_media_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};


