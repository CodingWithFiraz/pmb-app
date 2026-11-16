<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_ukt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pmb')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalur_masuk')->cascadeOnDelete();
            $table->foreignId('prodi_id')->constrained('prodi')->cascadeOnDelete();
            $table->foreignId('golongan_id')->constrained('golongan_ukt')->cascadeOnDelete();
            $table->unsignedBigInteger('nominal');
            $table->timestamps();

            $table->unique(['periode_id', 'jalur_id', 'prodi_id', 'golongan_id'], 'tarif_ukt_unique_kombinasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_ukt');
    }
};
