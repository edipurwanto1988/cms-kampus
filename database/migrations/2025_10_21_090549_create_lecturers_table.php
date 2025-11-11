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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('NUPTK')->nullable();
            $table->string('nip')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('photo_media_id')->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->string('position_title')->nullable();
            $table->string('expertise')->nullable();
            $table->string('scholar_url')->nullable();
            $table->string('researchgate_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('featured')->default('0');
            $table->boolean('is_active')->default('1');
            $table->timestamps();
            $table->index(['photo_media_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
