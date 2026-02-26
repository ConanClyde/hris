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
        Schema::create('perf_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('route', 255)->index();

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role', 50)->nullable()->index();

            $table->unsignedInteger('fcp')->nullable();
            $table->unsignedInteger('lcp')->nullable();
            $table->decimal('cls', 6, 3)->nullable();

            $table->unsignedInteger('ttfb')->nullable();
            $table->unsignedInteger('dom_ready')->nullable();
            $table->unsignedInteger('page_load')->nullable();
            $table->unsignedInteger('nav_transition_ms')->nullable();

            $table->string('user_agent', 512)->nullable();
            $table->string('ip', 45)->nullable();

            $table->boolean('budget_exceeded')->default(false)->index();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'budget_exceeded']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perf_metrics');
    }
};
