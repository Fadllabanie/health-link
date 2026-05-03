<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('hospital_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name', 191);
            $table->string('slug', 191)->unique();
            $table->string('license_number', 100)->unique();
            $table->string('email', 191)->unique();
            $table->string('phone', 20);
            $table->foreignId('country_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('logo')->nullable();
            $table->enum('type', ['in_hospital', 'external', 'chain'])->default('external');
            $table->boolean('is_24_hours')->default(false);
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('hospital_id');
            $table->index('city_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
