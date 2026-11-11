<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->text('jawaban');
            $table->string('kategori');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
