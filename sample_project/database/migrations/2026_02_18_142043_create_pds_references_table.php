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
        Schema::create('pds_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();
            $table->string('reference_name');
            $table->string('reference_address');
            $table->string('reference_telno')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['pds_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_references');
    }
};
