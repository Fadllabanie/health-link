<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
{
    public function definition(): array
    {
        $startHour = fake()->numberBetween(8, 14);

        return [
            'doctor_id' => Doctor::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', $startHour + fake()->numberBetween(3, 6)),
            'slot_duration_minutes' => fake()->randomElement([15, 20, 30, 45, 60]),
            'is_active' => true,
        ];
    }
}
