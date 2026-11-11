<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('image_url')->nullable();
            $table->text('deskripsi');
            $table->string('author');
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
