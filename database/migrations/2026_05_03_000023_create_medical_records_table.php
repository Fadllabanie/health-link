<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->foreignId('hospital_id')->constrained()->restrictOnDelete();
            $table->dateTime('visit_date');
            $table->enum('visit_type', ['consultation', 'follow_up', 'emergency', 'surgery', 'checkup'])->default('consultation');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'finalized', 'amended'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('hospital_id');
            $table->index('visit_date');
            $table->index(['patient_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
