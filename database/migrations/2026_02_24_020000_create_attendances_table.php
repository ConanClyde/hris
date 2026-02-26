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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date')->index();

            // Standard Philippine Government DTR format (AM/PM)
            $table->time('time_in_am')->nullable();
            $table->time('time_out_am')->nullable();
            $table->time('time_in_pm')->nullable();
            $table->time('time_out_pm')->nullable();

            $table->integer('overtime_minutes')->default(0);
            $table->integer('undertime_minutes')->default(0);
            $table->integer('late_minutes')->default(0);

            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave', 'holiday'])->default('present');
            $table->text('remarks')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
