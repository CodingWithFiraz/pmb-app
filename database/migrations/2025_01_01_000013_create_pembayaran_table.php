<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->enum('jenis', ['pendaftaran', 'ukt']);
            $table->unsignedBigInteger('nominal');
            $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');
            $table->string('order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->text('snap_url')->nullable();
            $table->text('qris_url')->nullable();
            $table->string('metode')->nullable();
            $table->string('bukti_path')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['pendaftaran_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
