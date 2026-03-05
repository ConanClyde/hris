<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chatbot_chunks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_id')->constrained('ai_chatbot_documents')->cascadeOnDelete();
            $table->string('source', 255)->index();
            $table->unsignedInteger('chunk_index')->default(0);
            $table->longText('content');
            $table->json('embedding')->nullable();
            $table->unsignedInteger('token_count')->default(0);
            $table->json('visibility')->nullable();
            $table->string('checksum', 64)->index();
            $table->timestamps();

            $table->unique(['document_id', 'chunk_index']);
            $table->index(['source', 'chunk_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_chunks');
    }
};
