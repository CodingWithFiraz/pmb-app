<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periode_pmb')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalur_masuk')->restrictOnDelete();
            $table->foreignId('prodi_1_id')->constrained('prodi')->restrictOnDelete();
            $table->foreignId('prodi_2_id')->nullable()->constrained('prodi')->nullOnDelete();
            $table->foreignId('golongan_id')->constrained('golongan_ukt')->restrictOnDelete();
            $table->enum('status', [
                'draft',
                'menunggu_verifikasi',
                'terverifikasi',
                'ditolak',
            ])->default('draft');
            $table->string('nomor_pendaftaran')->unique()->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->enum('hasil_kelulusan', ['belum', 'lulus', 'tidak_lulus'])->default('belum');
            $table->timestamps();

            $table->unique(['user_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
