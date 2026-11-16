<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admission_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique()->nullable();

            // Data Pribadi
            $table->string('full_name');
            $table->string('nik', 16)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('religion')->nullable();
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 10)->nullable();

            // Data Pendidikan
            $table->string('school_name');
            $table->string('school_city');
            $table->string('major')->nullable(); // jurusan SMA
            $table->year('graduation_year');
            $table->decimal('gpa', 5, 2)->nullable(); // nilai rata-rata

            // Status
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'revision_required',
                'passed',
                'not_passed'
            ])->default('draft');

            $table->enum('payment_status', [
                'not_required',
                'unpaid',
                'pending_verification',
                'verified',
                'rejected'
            ])->default('not_required');

            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable();

            $table->unique(['user_id', 'admission_period_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};