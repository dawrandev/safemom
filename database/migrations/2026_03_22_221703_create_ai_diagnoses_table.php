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
        Schema::create('ai_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vitals_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['green', 'yellow', 'red']);
            $table->text('analysis_text');
            $table->text('nutrition_advice')->nullable();
            $table->boolean('is_critical')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_diagnoses');
    }
};
