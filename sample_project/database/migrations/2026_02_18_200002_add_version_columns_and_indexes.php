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
        // Add version columns for optimistic locking
        // This prevents concurrent update conflicts
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status')->comment('Optimistic locking version');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status')->comment('Optimistic locking version');
        });

        Schema::table('pds', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status')->comment('Optimistic locking version');
        });

        // Add index on commonly queried columns for performance
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->index('created_at', 'idx_leave_created_at');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->index('created_at', 'idx_training_created_at');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_activity_created_at');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->index(['is_active', 'expires_at'], 'idx_notices_active_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('version');
            $table->dropIndex('idx_leave_created_at');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('version');
            $table->dropIndex('idx_training_created_at');
        });

        Schema::table('pds', function (Blueprint $table) {
            $table->dropColumn('version');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_created_at');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->dropIndex('idx_notices_active_expiry');
        });
    }
};
