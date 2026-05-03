<?php

namespace Database\Factories;

use App\Enums\PharmacyStatus;
use App\Enums\PharmacyType;
use App\Models\City;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Pharmacy>
 */
class PharmacyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company().' Pharmacy';

        return [
            'uuid' => Str::uuid()->toString(),
            'hospital_id' => null,
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'license_number' => fake()->unique()->numerify('PH-#####'),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->unique()->numerify('+966#########'),
            'country_id' => Country::factory(),
            'city_id' => City::factory(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'logo' => null,
            'type' => PharmacyType::External->value,
            'is_24_hours' => false,
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
            'status' => PharmacyStatus::Active->value,
        ];
    }

    public function inHospital(Hospital $hospital): static
    {
        return $this->state(fn (array $attributes) => [
            'hospital_id' => $hospital->id,
            'type' => PharmacyType::InHospital->value,
        ]);
    }
}
