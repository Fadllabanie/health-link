<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->country(),
            'code' => fake()->unique()->countryCode(),
            'code3' => strtoupper(fake()->unique()->lexify('???')),
            'phone_code' => '+'.fake()->numberBetween(1, 999),
            'currency_code' => strtoupper(fake()->currencyCode()),
            'is_active' => true,
        ];
    }
}
