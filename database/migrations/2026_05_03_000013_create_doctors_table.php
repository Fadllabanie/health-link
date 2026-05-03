<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('hospital_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('primary_specialty_id')->constrained('specialties');
            $table->string('license_number', 100)->unique();
            $table->date('license_expires_at')->nullable();
            $table->text('qualifications')->nullable();
            $table->unsignedTinyInteger('years_of_experience')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->string('signature')->nullable();
            $table->boolean('is_available')->default(true);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->date('joined_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('hospital_id');
            $table->index('department_id');
            $table->index('primary_specialty_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
