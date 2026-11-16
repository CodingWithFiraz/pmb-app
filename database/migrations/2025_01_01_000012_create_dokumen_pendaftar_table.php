<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->foreignId('dokumen_id')->constrained('dokumen_master')->restrictOnDelete();
            $table->string('file_path');
            $table->enum('status', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->unique(['pendaftaran_id', 'dokumen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendaftar');
    }
};
