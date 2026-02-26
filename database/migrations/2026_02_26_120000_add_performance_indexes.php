<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->index(['status', 'role', 'is_active', 'created_at'], 'users_status_role_active_created_at_idx');
        });

        Schema::table('leave_applications', function (Blueprint $table): void {
            $table->index(['employee_id', 'status', 'date_from', 'created_at'], 'leave_emp_status_date_created_idx');
        });

        Schema::table('trainings', function (Blueprint $table): void {
            $table->index(['employee_id', 'status', 'date_from', 'created_at'], 'trainings_emp_status_date_created_idx');
        });

        Schema::table('notices', function (Blueprint $table): void {
            $table->index(['is_active', 'expires_at', 'created_at'], 'notices_active_expires_created_idx');
        });

        Schema::table('pds', function (Blueprint $table): void {
            $table->index(['employee_id', 'status', 'created_at'], 'pds_employee_status_created_idx');
        });

        Schema::table('notice_reads', function (Blueprint $table): void {
            $table->index(['user_id', 'notice_id'], 'notice_reads_user_notice_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_status_role_active_created_at_idx');
        });

        Schema::table('leave_applications', function (Blueprint $table): void {
            $table->dropIndex('leave_emp_status_date_created_idx');
        });

        Schema::table('trainings', function (Blueprint $table): void {
            $table->dropIndex('trainings_emp_status_date_created_idx');
        });

        Schema::table('notices', function (Blueprint $table): void {
            $table->dropIndex('notices_active_expires_created_idx');
        });

        Schema::table('pds', function (Blueprint $table): void {
            $table->dropIndex('pds_employee_status_created_idx');
        });

        Schema::table('notice_reads', function (Blueprint $table): void {
            $table->dropIndex('notice_reads_user_notice_idx');
        });
    }
};
