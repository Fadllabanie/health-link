<?php

namespace Database\Factories;

use App\Models\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QrCode>
 */
class QrCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => Str::random(64),
            'qrable_type' => 'App\\Models\\Patient',
            'qrable_id' => fake()->numberBetween(1, 1000),
            'image_path' => null,
            'scan_count' => 0,
            'last_scanned_at' => null,
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ];
    }
}
