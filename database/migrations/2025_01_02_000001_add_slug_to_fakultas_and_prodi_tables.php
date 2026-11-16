<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fakultas', function (Blueprint $table) {
            $table->string('slug')->unique()->after('kode');
        });

        Schema::table('prodi', function (Blueprint $table) {
            $table->string('slug')->unique()->after('kode');
        });
    }

    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('fakultas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
