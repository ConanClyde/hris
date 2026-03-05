<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE leave_applications MODIFY status ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending'"
            );
        }
    }

    public function down(): void
    {
        DB::statement("UPDATE leave_applications SET status = 'rejected' WHERE status = 'cancelled'");

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE leave_applications MODIFY status ENUM('pending','approved','rejected') DEFAULT 'pending'"
            );
        }
    }
};
