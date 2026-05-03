<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('prescription_number', 50)->unique();
            $table->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->foreignId('hospital_id')->constrained()->restrictOnDelete();
            $table->foreignId('pharmacy_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('issued_at');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->text('diagnosis_summary')->nullable();
            $table->enum('status', ['pending', 'partially_dispensed', 'dispensed', 'cancelled', 'expired'])->default('pending');
            $table->dateTime('dispensed_at')->nullable();
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('hospital_id');
            $table->index('pharmacy_id');
            $table->index('status');
            $table->index('issued_at');
            $table->index('prescription_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
