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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('title');
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->decimal('hours', 5, 2)->default(0);
            $table->string('type')->nullable();
            $table->string('provider')->nullable();
            $table->string('category')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('participants')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index('date_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
