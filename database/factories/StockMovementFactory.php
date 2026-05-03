<?php

namespace Database\Factories;

use App\Enums\StockMovementType;
use App\Models\PharmacyInventory;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pharmacy_inventory_id' => PharmacyInventory::factory(),
            'type' => fake()->randomElement(StockMovementType::cases())->value,
            'quantity' => fake()->numberBetween(-50, 100),
            'reference_type' => null,
            'reference_id' => null,
            'unit_price' => fake()->randomFloat(2, 5, 200),
            'notes' => fake()->optional()->sentence(),
            'performed_by' => User::factory(),
        ];
    }
}
