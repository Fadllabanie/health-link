<?php

namespace Database\Factories;

use App\Enums\PrescriptionStatus;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Prescription>
 */
class PrescriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'prescription_number' => fake()->unique()->numerify('RX-######'),
            'medical_record_id' => null,
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'hospital_id' => Hospital::factory(),
            'pharmacy_id' => null,
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'valid_until' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
            'diagnosis_summary' => fake()->sentence(),
            'status' => PrescriptionStatus::Pending->value,
            'dispensed_at' => null,
            'dispensed_by' => null,
            'total_amount' => null,
        ];
    }

    public function dispensed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PrescriptionStatus::Dispensed->value,
            'dispensed_at' => now(),
        ]);
    }
}
