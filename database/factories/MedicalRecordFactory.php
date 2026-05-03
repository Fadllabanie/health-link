<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Enums\VisitType;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'hospital_id' => Hospital::factory(),
            'visit_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'visit_type' => fake()->randomElement(VisitType::cases())->value,
            'notes' => fake()->paragraph(),
            'status' => RecordStatus::Finalized->value,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RecordStatus::Draft->value,
        ]);
    }
}
