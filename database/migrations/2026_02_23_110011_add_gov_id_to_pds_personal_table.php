<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pds_personal', function (Blueprint $table) {
            $table->string('gov_id_type')->nullable();
            $table->string('gov_id_no')->nullable();
            $table->string('gov_id_issuance')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pds_personal', function (Blueprint $table) {
            $table->dropColumn(['gov_id_type', 'gov_id_no', 'gov_id_issuance']);
        });
    }
};
