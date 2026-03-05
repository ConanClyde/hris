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
        Schema::table('trainings', function (Blueprint $table) {
            $table->foreignId('employee_fk')->nullable()->after('employee_id')->constrained('employees')->nullOnDelete();
            $table->index('employee_fk');
        });

        // Best-effort backfill: if legacy employee_id contains employee PKs, copy them.
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(<<<'SQL'
                UPDATE trainings t
                JOIN employees e ON e.id = t.employee_id
                SET t.employee_fk = e.id
                WHERE t.employee_fk IS NULL
                SQL);
        } elseif ($driver === 'sqlite') {
            DB::statement(<<<'SQL'
                UPDATE trainings
                SET employee_fk = (SELECT id FROM employees WHERE employees.id = trainings.employee_id)
                WHERE employee_fk IS NULL
                AND EXISTS (SELECT 1 FROM employees WHERE employees.id = trainings.employee_id)
                SQL);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_fk');
        });
    }
};
