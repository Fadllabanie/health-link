<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'appointment_number' => fake()->unique()->numerify('APT-######'),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'hospital_id' => Hospital::factory(),
            'department_id' => null,
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'duration_minutes' => fake()->randomElement([15, 30, 45, 60]),
            'type' => fake()->randomElement(AppointmentType::cases())->value,
            'reason' => fake()->sentence(),
            'status' => AppointmentStatus::Scheduled->value,
            'cancellation_reason' => null,
            'fee' => fake()->randomFloat(2, 100, 500),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppointmentStatus::Completed->value,
            'scheduled_at' => fake()->dateTimeBetween('-3 months', '-1 day'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppointmentStatus::Cancelled->value,
            'cancellation_reason' => fake()->sentence(),
        ]);
    }
}
