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
        Schema::create('pds_background_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();
            // Questions 34-40 - stored as JSON for flexibility
            $table->json('answers')->nullable();
            $table->text('details_34')->nullable();
            $table->text('details_35')->nullable();
            $table->text('details_36')->nullable();
            $table->text('details_37')->nullable();
            $table->text('details_38')->nullable();
            $table->text('details_39')->nullable();
            $table->text('details_40')->nullable();
            $table->timestamps();
            $table->unique('pds_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_background_info');
    }
};
