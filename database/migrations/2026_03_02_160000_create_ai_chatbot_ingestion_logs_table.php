<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chatbot_ingestion_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->boolean('embed')->default(true)->index();
            $table->unsignedInteger('documents_indexed')->default(0);
            $table->unsignedInteger('chunks_created')->default(0);
            $table->unsignedInteger('duration_ms')->default(0);
            $table->string('status', 20)->default('success')->index();
            $table->string('error_message', 255)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_ingestion_logs');
    }
};
