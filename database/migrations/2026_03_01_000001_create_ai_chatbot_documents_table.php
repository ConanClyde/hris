<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chatbot_documents', function (Blueprint $table): void {
            $table->id();
            $table->string('source', 255)->unique();
            $table->longText('content');
            $table->json('tokens')->nullable();
            $table->unsignedInteger('term_count')->default(0);
            $table->string('checksum', 64)->index();
            $table->timestamps();

            $table->index(['updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_documents');
    }
};
