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
            DB::table('users')->where('role', 'applicant')->update(['role' => 'pendaftar']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pendaftar') NOT NULL DEFAULT 'pendaftar'");

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
