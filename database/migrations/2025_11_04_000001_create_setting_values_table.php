<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('setting_values')) {
            Schema::create('setting_values', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('setting_id');
                $table->string('locale')->nullable();
                $table->text('value_text')->nullable();
                $table->json('value_json')->nullable();
                $table->timestamps();
                $table->index(['setting_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_values');
    }
};


