<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds separated name component columns to the users table
     * to standardize with the employees table structure.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add name component columns after the existing 'name' column
            $table->string('first_name')->nullable()->after('name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('name_extension', 50)->nullable()->after('last_name');

            // Add indexes for performance on common search operations
            $table->index('first_name');
            $table->index('last_name');
            $table->index(['last_name', 'first_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * Removes the separated name component columns.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['last_name', 'first_name']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['first_name']);

            // Drop columns
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'name_extension',
            ]);
        });
    }
};
