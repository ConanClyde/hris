<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('app_notifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Legacy table is intentionally removed; no rollback.
    }
};
