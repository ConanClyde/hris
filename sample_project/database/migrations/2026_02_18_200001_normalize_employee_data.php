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
        // Remove denormalized employee_name from leave_applications
        // Data is redundant - can be fetched via employee relationship
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('employee_name');
        });

        // Remove denormalized employee_name from trainings
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('employee_name');
        });

        // Remove unused legacy_attachment column from leave_applications
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('legacy_attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore employee_name columns
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('employee_id');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('employee_id');
        });

        // Restore legacy_attachment column
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->json('legacy_attachment')->nullable()->after('attachments');
        });
    }
};
