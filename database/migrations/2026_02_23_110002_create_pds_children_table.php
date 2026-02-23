<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pds_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->onDelete('cascade');
            $table->string('name');
            $table->date('dob')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pds_children');
    }
};
