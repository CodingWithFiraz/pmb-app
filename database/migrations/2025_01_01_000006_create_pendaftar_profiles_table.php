<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftar_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nisn', 10)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('asal_sekolah');
            $table->year('tahun_lulus');
            $table->string('nama_ortu');
            $table->string('pekerjaan_ortu');
            $table->unsignedBigInteger('penghasilan_ortu')->nullable();
            $table->foreignId('golongan_id')->constrained('golongan_ukt')->restrictOnDelete();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftar_profiles');
    }
};
