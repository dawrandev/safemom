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
        Schema::create('telegram_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_id')->index();
            $table->string('current_step');
            $table->json('data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['telegram_id', 'current_step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_conversations');
    }
};
