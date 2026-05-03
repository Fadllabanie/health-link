<?php

namespace Database\Factories;

use App\Enums\InventoryStatus;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PharmacyInventory>
 */
class PharmacyInventoryFactory extends Factory
{
    public function definition(): array
    {
        $unitCost = fake()->randomFloat(2, 5, 200);

        return [
            'pharmacy_id' => Pharmacy::factory(),
            'medicine_id' => Medicine::factory(),
            'batch_number' => fake()->unique()->numerify('BATCH-######'),
            'quantity_in_stock' => fake()->numberBetween(10, 500),
            'reorder_level' => fake()->numberBetween(5, 50),
            'unit_cost' => $unitCost,
            'selling_price' => round($unitCost * fake()->randomFloat(2, 1.2, 2.5), 2),
            'manufacturing_date' => fake()->dateTimeBetween('-2 years', '-6 months')->format('Y-m-d'),
            'expiry_date' => fake()->dateTimeBetween('+6 months', '+3 years')->format('Y-m-d'),
            'supplier' => fake()->company(),
            'location' => 'Shelf '.fake()->randomLetter().fake()->numberBetween(1, 20),
            'status' => InventoryStatus::Available->value,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_in_stock' => fake()->numberBetween(1, 9),
            'status' => InventoryStatus::LowStock->value,
        ]);
    }
}
