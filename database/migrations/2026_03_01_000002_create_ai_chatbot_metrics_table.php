<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chatbot_metrics', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role', 50)->nullable()->index();
            $table->string('query_hash', 64)->index();
            $table->unsignedInteger('context_ms')->nullable();
            $table->unsignedInteger('llm_ms')->nullable();
            $table->unsignedInteger('total_ms')->nullable();
            $table->unsignedInteger('policy_sources_count')->default(0);
            $table->decimal('max_confidence', 5, 2)->nullable();
            $table->boolean('cache_hit')->default(false)->index();
            $table->string('error_type', 100)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_metrics');
    }
};
