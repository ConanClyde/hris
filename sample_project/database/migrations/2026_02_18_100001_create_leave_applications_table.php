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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name')->nullable();
            $table->string('type');
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->decimal('total_days', 5, 2)->default(0);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('attachments')->nullable();
            $table->json('legacy_attachment')->nullable();
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
        Schema::dropIfExists('leave_applications');
    }
};
