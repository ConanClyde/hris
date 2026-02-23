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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('subdivisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdivision_id')->nullable()->constrained('subdivisions')->nullOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->after('user_id')->constrained('divisions')->nullOnDelete();
            $table->foreignId('subdivision_id')->nullable()->after('division_id')->constrained('subdivisions')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->after('subdivision_id')->constrained('sections')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropForeign(['subdivision_id']);
            $table->dropForeign(['division_id']);
            $table->dropColumn(['division_id', 'subdivision_id', 'section_id']);
        });

        Schema::dropIfExists('sections');
        Schema::dropIfExists('subdivisions');
        Schema::dropIfExists('divisions');
    }
};
