<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrescriptionItem>
 */
class PrescriptionItemFactory extends Factory
{
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 10, 200);
        $quantity = fake()->numberBetween(1, 30);

        return [
            'prescription_id' => Prescription::factory(),
            'medicine_id' => Medicine::factory(),
            'dosage' => fake()->randomElement(['1 tablet', '2 tablets', '5ml', '10ml', '1 capsule']),
            'frequency' => fake()->randomElement(['once daily', 'twice daily', 'three times daily', 'every 8 hours']),
            'duration_days' => fake()->numberBetween(3, 30),
            'quantity' => $quantity,
            'quantity_dispensed' => 0,
            'route' => fake()->randomElement(['oral', 'IV', 'topical', 'inhalation']),
            'instructions' => fake()->optional()->sentence(),
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'is_dispensed' => false,
        ];
    }
}
