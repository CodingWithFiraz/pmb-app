<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->string('jenjang')->default('S1');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('kuota')->default(0);
            $table->timestamps();

            $table->index('fakultas_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
