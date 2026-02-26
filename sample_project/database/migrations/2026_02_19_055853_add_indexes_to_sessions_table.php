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
        Schema::table('sessions', function (Blueprint $table) {
            // Only add indexes if they don't already exist
            $existingIndexes = Schema::getIndexes('sessions');
            $indexNames = array_column($existingIndexes, 'name');

            if (! in_array('sessions_user_id_index', $indexNames)) {
                $table->index('user_id');
            }
            if (! in_array('sessions_last_activity_index', $indexNames)) {
                $table->index('last_activity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Drop indexes if they exist
            try {
                $table->dropIndex(['user_id']);
            } catch (\Exception $e) {
                // Index may not exist
            }
            try {
                $table->dropIndex(['last_activity']);
            } catch (\Exception $e) {
                // Index may not exist
            }
        });
    }
};
