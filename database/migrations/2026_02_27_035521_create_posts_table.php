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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('body');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('role_scope', 20)->default('all');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['created_by']);
            $table->index(['is_published', 'role_scope', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
