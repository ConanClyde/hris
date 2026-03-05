<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chatbot_feedback', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role', 50)->nullable()->index();
            $table->string('query_hash', 64)->nullable()->index();
            $table->string('message_id', 64)->nullable()->index();
            $table->smallInteger('rating')->default(0)->index();
            $table->string('response_excerpt', 500)->nullable();
            $table->json('sources')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_feedback');
    }
};
