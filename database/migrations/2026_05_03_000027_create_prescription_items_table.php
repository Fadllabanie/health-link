<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->string('dosage', 100);
            $table->string('frequency', 100);
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_dispensed')->default(0);
            $table->string('route', 50)->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->boolean('is_dispensed')->default(false);
            $table->timestamps();

            $table->index('prescription_id');
            $table->index('medicine_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
