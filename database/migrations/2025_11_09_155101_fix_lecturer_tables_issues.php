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
        Schema::table('lecturers', function (Blueprint $table) {
            // Drop the existing primary key and recreate it with auto_increment
            $table->dropPrimary('lecturers_id_primary');
            $table->bigIncrements('id')->change();
        });

        Schema::table('lecturer_translations', function (Blueprint $table) {
            // Drop the existing primary key and recreate it with auto_increment
            $table->dropPrimary('lecturer_translations_id_primary');
            $table->bigIncrements('id')->change();
            
            // Drop the incorrect unique constraints
            $table->dropUnique('lecturer_translations_lecturer_id_unique');
            $table->dropUnique('lecturer_translations_locale_unique');
            
            // Add the correct composite unique constraint
            $table->unique(['lecturer_id', 'locale'], 'lecturer_translations_lecturer_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropPrimary();
            $table->bigInteger('id')->primary()->change();
        });

        Schema::table('lecturer_translations', function (Blueprint $table) {
            $table->dropPrimary();
            $table->bigInteger('id')->primary()->change();
            $table->dropUnique('lecturer_translations_lecturer_id_locale_unique');
            $table->unique(['lecturer_id']);
            $table->unique(['locale']);
        });
    }
};
