<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type');
            $table->decimal('balance', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_credits');
    }
};
