<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->string('batch_number', 100);
            $table->integer('quantity_in_stock')->default(0);
            $table->unsignedInteger('reorder_level')->default(10);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date');
            $table->string('supplier', 191)->nullable();
            $table->string('location', 100)->nullable();
            $table->enum('status', ['available', 'low_stock', 'out_of_stock', 'expired'])->default('available');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pharmacy_id', 'medicine_id', 'batch_number']);
            $table->index('pharmacy_id');
            $table->index('medicine_id');
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_inventories');
    }
};
