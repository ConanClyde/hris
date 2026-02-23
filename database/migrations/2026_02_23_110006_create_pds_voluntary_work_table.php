<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pds_voluntary_work', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();
            $table->string('org_name_address');
            $table->date('volunteer_from');
            $table->date('volunteer_to')->nullable();
            $table->unsignedInteger('number_of_hours')->default(0);
            $table->string('nature_of_work');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['pds_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pds_voluntary_work');
    }
};
