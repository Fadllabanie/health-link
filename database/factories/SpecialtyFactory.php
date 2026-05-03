<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Specialty>
 */
class SpecialtyFactory extends Factory
{
    private static array $specialties = [
        'Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'Oncology',
        'Dermatology', 'Ophthalmology', 'Psychiatry', 'Radiology', 'Surgery',
        'Internal Medicine', 'Endocrinology', 'Gastroenterology', 'Nephrology',
        'Pulmonology', 'Rheumatology', 'Urology', 'Hematology',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::$specialties).' '.fake()->numberBetween(1, 999);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => null,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
