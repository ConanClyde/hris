<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pds_csc_eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();
            $table->string('license_name');
            $table->string('rating')->nullable();
            $table->date('date_of_examination');
            $table->string('place_of_examination');
            $table->string('license_no')->nullable();
            $table->date('date_of_validity')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['pds_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pds_csc_eligibility');
    }
};
