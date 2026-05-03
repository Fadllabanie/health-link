<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('generic_name', 191)->nullable();
            $table->string('brand_name', 191)->nullable();
            $table->string('barcode', 100)->unique()->nullable();
            $table->foreignId('category_id')->nullable()->constrained('medicine_categories')->nullOnDelete();
            $table->string('manufacturer', 191)->nullable();
            $table->enum('form', ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'inhaler', 'other']);
            $table->string('strength', 50)->nullable();
            $table->string('unit', 20)->nullable();
            $table->text('description')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('dosage_instructions')->nullable();
            $table->boolean('requires_prescription')->default(true);
            $table->boolean('is_controlled')->default(false);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('generic_name');
            $table->index('barcode');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
