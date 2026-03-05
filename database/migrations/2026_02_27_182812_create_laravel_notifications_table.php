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
        // Preserve the app's existing custom notifications table by renaming it.
        if (Schema::hasTable('notifications') && ! Schema::hasTable('app_notifications')) {
            Schema::rename('notifications', 'app_notifications');
        }

        // Create the table Laravel's built-in database notifications expect.
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::drop('notifications');
        }

        if (Schema::hasTable('app_notifications') && ! Schema::hasTable('notifications')) {
            Schema::rename('app_notifications', 'notifications');
        }
    }
};
