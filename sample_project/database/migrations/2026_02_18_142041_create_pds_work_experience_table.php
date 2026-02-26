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
        Schema::create('pds_work_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();
            $table->date('employed_from');
            $table->date('employed_to')->nullable();
            $table->string('position_title');
            $table->string('department');
            $table->decimal('salary', 12, 2)->nullable();
            $table->unsignedSmallInteger('salary_grade')->nullable();
            $table->string('appointment_status')->nullable();
            $table->boolean('is_government')->default(false);
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
        Schema::dropIfExists('pds_work_experience');
    }
};
