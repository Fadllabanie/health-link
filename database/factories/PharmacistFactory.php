<?php

namespace Database\Factories;

use App\Models\Pharmacist;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pharmacist>
 */
class PharmacistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pharmacy_id' => Pharmacy::factory(),
            'license_number' => fake()->unique()->numerify('PHR-#####'),
            'license_expires_at' => fake()->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
            'position' => fake()->randomElement(['Senior Pharmacist', 'Junior Pharmacist', 'Head Pharmacist']),
            'is_active' => true,
        ];
    }
}
