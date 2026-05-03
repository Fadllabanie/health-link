<?php

namespace Database\Factories;

use App\Enums\BloodType;
use App\Enums\MaritalStatus;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'hospital_id' => Hospital::factory(),
            'qr_code_id' => null,
            'city_id' => null,
            'medical_record_number' => fake()->unique()->numerify('MRN-######'),
            'blood_type' => fake()->randomElement(BloodType::cases())->value,
            'height_cm' => fake()->randomFloat(2, 140, 210),
            'weight_kg' => fake()->randomFloat(2, 40, 150),
            'allergies' => fake()->optional()->sentence(),
            'chronic_conditions' => fake()->optional()->sentence(),
            'current_medications' => null,
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->numerify('+966#########'),
            'emergency_contact_relation' => fake()->randomElement(['Parent', 'Spouse', 'Sibling', 'Child']),
            'insurance_provider' => fake()->optional()->company(),
            'insurance_policy_number' => fake()->optional()->numerify('INS-######'),
            'marital_status' => fake()->randomElement(MaritalStatus::cases())->value,
            'occupation' => fake()->jobTitle(),
        ];
    }
}
