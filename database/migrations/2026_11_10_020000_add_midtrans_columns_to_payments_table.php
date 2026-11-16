<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Tambahkan kolom golongan setelah application_id jika belum ada
            if (! Schema::hasColumn('payments', 'golongan')) {
                $table->unsignedTinyInteger('golongan')->nullable()->after('application_id');
            }

            // Tambahkan kolom payment_type, midtrans_transaction_id, paid_at jika belum ada
            if (! Schema::hasColumn('payments', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('snap_token');
            }
            if (! Schema::hasColumn('payments', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('payment_type');
            }
            if (! Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('midtrans_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['golongan', 'payment_type', 'midtrans_transaction_id', 'paid_at']);
        });
    }
};
