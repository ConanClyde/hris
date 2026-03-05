<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->foreignId('employee_fk')->nullable()->after('employee_id')->constrained('employees')->nullOnDelete();
            $table->index('employee_fk');
        });

        // Best-effort backfill: if legacy employee_id contains numeric employee primary keys, copy them.
        // This is safe because it only fills employee_fk when an employees.id exists.
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(<<<'SQL'
                UPDATE leave_applications la
                JOIN employees e ON e.id = la.employee_id
                SET la.employee_fk = e.id
                WHERE la.employee_fk IS NULL
                SQL);
        } elseif ($driver === 'sqlite') {
            DB::statement(<<<'SQL'
                UPDATE leave_applications
                SET employee_fk = (SELECT id FROM employees WHERE employees.id = leave_applications.employee_id)
                WHERE employee_fk IS NULL
                AND EXISTS (SELECT 1 FROM employees WHERE employees.id = leave_applications.employee_id)
                SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_fk');
        });
    }
};
