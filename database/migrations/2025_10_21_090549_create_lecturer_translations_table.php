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
        Schema::create('lecturer_translations', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('lecturer_id');
            $table->string('locale');
            $table->string('full_name');
            $table->string('bio_html')->nullable();
            $table->text('research_interests')->nullable();
            $table->text('achievement')->nullable();
            $table->unique(['lecturer_id']);
            $table->unique(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturer_translations');
    }
};
