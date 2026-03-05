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
        Schema::create('ai_chatbot_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('conversation_id')->constrained('ai_chatbot_conversations')->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('content_encrypted');
            $table->string('content_hash', 64)->nullable();
            $table->longText('sources_encrypted')->nullable();
            $table->string('tool_used', 100)->nullable();
            $table->longText('tool_data_encrypted')->nullable();
            $table->integer('sequence_number');
            $table->timestamps();

            $table->index(['conversation_id', 'sequence_number']);
            $table->index(['conversation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_messages');
    }
};
