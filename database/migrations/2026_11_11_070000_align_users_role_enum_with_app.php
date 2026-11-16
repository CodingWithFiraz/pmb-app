<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'role')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'applicant', 'pendaftar') NOT NULL DEFAULT 'pendaftar'");
            if ($driver === 'sqlite') {
            try {
                DB::table('users')->where('role', 'applicant')->update(['role' => 'pendaftar']);
            } catch (\Throwable $e) {
                // SQLite check constraint allows 'applicant'; User::isPendaftar() handles both.
            }
            return;
        }

        DB::table('users')->where('role', 'applicant')->update(['role' => 'pendaftar']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pendaftar') NOT NULL DEFAULT 'pendaftar'");

            return;
        }

        if ($driver === 'sqlite') {
            try {
                DB::table('users')->where('role', 'applicant')->update(['role' => 'pendaftar']);
            } catch (\Throwable $e) {
                // SQLite check constraint allows 'applicant'; User::isPendaftar() handles both.
            }
            return;
        }

        DB::table('users')->where('role', 'applicant')->update(['role' => 'pendaftar']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'role')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'applicant', 'pendaftar') NOT NULL DEFAULT 'applicant'");
            DB::table('users')->where('role', 'pendaftar')->update(['role' => 'applicant']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'applicant') NOT NULL DEFAULT 'applicant'");
        }
    }
};
