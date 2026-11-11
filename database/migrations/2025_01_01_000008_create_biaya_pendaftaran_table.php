<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pmb')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalur_masuk')->cascadeOnDelete();
            $table->unsignedBigInteger('nominal')->nullable();
            $table->timestamps();

            $table->unique(['periode_id', 'jalur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_pendaftaran');
    }
};
