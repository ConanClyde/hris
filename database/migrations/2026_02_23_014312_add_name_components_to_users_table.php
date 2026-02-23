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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('name_extension', 50)->nullable()->after('last_name');
            $table->index('first_name');
            $table->index('last_name');
            $table->index(['last_name', 'first_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['last_name', 'first_name']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['first_name']);
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'name_extension']);
        });
    }
};
