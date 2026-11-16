<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel pendaftaran (sesuaikan nama tabel jika bukan 'applications')
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade'); 
            $table->string('order_id')->unique(); // Contoh: UKT-1-1700000000
            $table->decimal('amount', 10, 2); // Nominal UKT
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
