<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $users = DB::table('users')
                ->select(['id', 'username', 'email'])
                ->whereNull('username')
                ->orWhere('username', '')
                ->orderBy('id')
                ->get();

            foreach ($users as $u) {
                $base = $u->email ? Str::before((string) $u->email, '@') : 'user'.$u->id;
                $candidate = Str::lower(preg_replace('/[^a-zA-Z0-9._-]/', '', (string) $base) ?: 'user'.$u->id);

                $username = $candidate;
                $suffix = 1;
                while (DB::table('users')->where('username', $username)->where('id', '!=', $u->id)->exists()) {
                    $username = $candidate.$suffix;
                    $suffix++;
                }

                DB::table('users')->where('id', $u->id)->update(['username' => $username]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
        });
    }
};
