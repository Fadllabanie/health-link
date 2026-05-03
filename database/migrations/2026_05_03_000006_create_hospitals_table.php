<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 191);
            $table->string('slug', 191)->unique();
            $table->string('license_number', 100)->unique();
            $table->string('email', 191)->unique();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->date('established_date')->nullable();
            $table->unsignedInteger('bed_capacity')->nullable();
            $table->enum('subscription_plan', ['free', 'basic', 'premium', 'enterprise'])->default('basic');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('city_id');
            $table->index('subscription_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
