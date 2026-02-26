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
        Schema::create('pds_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->onDelete('cascade');
            $table->string('level'); // Elementary, Secondary, Vocational, College, Graduate Studies
            $table->string('school_name')->nullable();
            $table->string('degree_course')->nullable();
            $table->string('period_from')->nullable();
            $table->string('period_to')->nullable();
            $table->string('highest_level')->nullable(); // Units earned
            $table->string('year_graduated')->nullable();
            $table->string('scholarship_honors')->nullable();
            $table->string('awards')->nullable(); // For frontend compatibility (c1 uses 'awards')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_education');
    }
};
