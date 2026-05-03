<?php

namespace Database\Factories;

use App\Enums\HospitalStatus;
use App\Enums\SubscriptionPlan;
use App\Models\City;
use App\Models\Country;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Hospital>
 */
class HospitalFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company().' Hospital';

        return [
            'uuid' => Str::uuid()->toString(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'license_number' => fake()->unique()->numerify('LIC-#####'),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->unique()->numerify('+966#########'),
            'alternate_phone' => null,
            'country_id' => Country::factory(),
            'city_id' => City::factory(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'logo' => null,
            'website' => fake()->url(),
            'description' => fake()->paragraph(),
            'established_date' => fake()->dateTimeBetween('-50 years', '-1 year')->format('Y-m-d'),
            'bed_capacity' => fake()->numberBetween(50, 1000),
            'subscription_plan' => SubscriptionPlan::Basic->value,
            'subscription_expires_at' => now()->addYear(),
            'status' => HospitalStatus::Active->value,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HospitalStatus::Active->value,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HospitalStatus::Suspended->value,
        ]);
    }
}
