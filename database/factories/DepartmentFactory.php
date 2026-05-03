<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    private static array $departments = [
        'Emergency', 'Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics',
        'ICU', 'Surgery', 'Radiology', 'Laboratory', 'Pharmacy',
        'Oncology', 'Gynecology', 'Dermatology', 'Ophthalmology', 'ENT',
    ];

    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::factory(),
            'name' => fake()->unique()->randomElement(self::$departments).' '.fake()->numberBetween(1, 99),
            'code' => strtoupper(fake()->lexify('???')).fake()->numberBetween(10, 99),
            'description' => fake()->sentence(),
            'head_doctor_id' => null,
            'is_active' => true,
        ];
    }
}
