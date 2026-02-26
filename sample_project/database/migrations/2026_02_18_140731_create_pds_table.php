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
        Schema::create('pds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
        });

        Schema::create('pds_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pds_id')->constrained('pds')->cascadeOnDelete();

            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();

            $table->date('dob')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();
            $table->decimal('height', 4, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('blood_type')->nullable();

            $table->string('citizenship_type')->nullable();
            $table->string('citizenship_nature')->nullable();
            $table->string('citizenship_country')->nullable();

            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();

            // Official IDs
            $table->string('cs_id')->nullable();
            $table->string('agency_employee_no')->nullable();
            $table->string('gsis')->nullable();
            $table->string('pag_ibig')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('sss')->nullable();
            $table->string('tin')->nullable();

            // Addresses (structured)
            $table->json('residential_address')->nullable();
            $table->json('permanent_address')->nullable();

            $table->timestamps();

            $table->unique('pds_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_personal');
        Schema::dropIfExists('pds');
    }
};
