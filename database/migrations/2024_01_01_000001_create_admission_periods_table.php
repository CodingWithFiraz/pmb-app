<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedBigInteger('registration_fee')->default(0);
            $table->enum('status', ['open', 'closed'])->default('closed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_periods');
    }
};