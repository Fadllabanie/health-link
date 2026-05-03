<?php

namespace Database\Factories;

use App\Enums\MedicineForm;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicine>
 */
class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'generic_name' => fake()->words(2, true),
            'brand_name' => fake()->company(),
            'barcode' => fake()->unique()->ean13(),
            'category_id' => MedicineCategory::factory(),
            'manufacturer' => fake()->company(),
            'form' => fake()->randomElement(MedicineForm::cases())->value,
            'strength' => fake()->randomElement(['250mg', '500mg', '1g', '10mg', '25mg', '50mg']),
            'unit' => fake()->randomElement(['mg', 'ml', 'g', 'mcg']),
            'description' => fake()->sentence(),
            'side_effects' => fake()->sentence(),
            'contraindications' => fake()->sentence(),
            'dosage_instructions' => fake()->sentence(),
            'requires_prescription' => true,
            'is_controlled' => false,
            'image' => null,
            'is_active' => true,
        ];
    }

    public function otc(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_prescription' => false,
        ]);
    }
}
