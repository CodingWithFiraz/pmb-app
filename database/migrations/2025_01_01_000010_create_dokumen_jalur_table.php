<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_jalur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumen_master')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalur_masuk')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dokumen_id', 'jalur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_jalur');
    }
};
