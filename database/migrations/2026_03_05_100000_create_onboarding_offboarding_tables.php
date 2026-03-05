<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->string('category')->default('general'); // general, documents, it, hr
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('offboarding_clearances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('department'); // HR, IT, Finance, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'cleared', 'flagged'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offboarding_clearances');
        Schema::dropIfExists('onboarding_checklists');
    }
};
