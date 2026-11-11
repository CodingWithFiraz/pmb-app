<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil pendaftar diisi bertahap (multi-step) dan disimpan sebagai draft,
     * sehingga kolom yang belum diisi pada tahap tertentu harus nullable.
     */
    public function up(): void
    {
        Schema::table('pendaftar_profiles', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->change();
            $table->string('nisn', 10)->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('provinsi')->nullable()->change();
            $table->string('kota')->nullable()->change();
            $table->string('asal_sekolah')->nullable()->change();
            $table->year('tahun_lulus')->nullable()->change();
            $table->string('nama_ortu')->nullable()->change();
            $table->string('pekerjaan_ortu')->nullable()->change();
            $table->foreignId('golongan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftar_profiles', function (Blueprint $table) {
            $table->string('nik', 16)->nullable(false)->change();
            $table->string('nisn', 10)->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('provinsi')->nullable(false)->change();
            $table->string('kota')->nullable(false)->change();
            $table->string('asal_sekolah')->nullable(false)->change();
            $table->year('tahun_lulus')->nullable(false)->change();
            $table->string('nama_ortu')->nullable(false)->change();
            $table->string('pekerjaan_ortu')->nullable(false)->change();
            $table->foreignId('golongan_id')->nullable(false)->change();
        });
    }
};
