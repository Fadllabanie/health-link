<?php

namespace Database\Factories;

use App\Enums\DoctorStatus;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'hospital_id' => Hospital::factory(),
            'department_id' => Department::factory(),
            'primary_specialty_id' => Specialty::factory(),
            'license_number' => fake()->unique()->numerify('DR-#####'),
            'license_expires_at' => fake()->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
            'qualifications' => fake()->sentence(),
            'years_of_experience' => fake()->numberBetween(1, 40),
            'bio' => fake()->paragraph(),
            'consultation_fee' => fake()->randomFloat(2, 100, 1000),
            'signature' => null,
            'is_available' => true,
            'rating' => fake()->randomFloat(2, 3, 5),
            'total_reviews' => fake()->numberBetween(0, 500),
            'status' => DoctorStatus::Active->value,
            'joined_at' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DoctorStatus::Active->value,
            'is_available' => true,
        ]);
    }

    public function onLeave(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DoctorStatus::OnLeave->value,
            'is_available' => false,
        ]);
    }
}
