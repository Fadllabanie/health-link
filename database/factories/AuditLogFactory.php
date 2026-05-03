<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'hospital_id' => Hospital::factory(),
            'action' => fake()->randomElement(['created', 'updated', 'deleted', 'viewed', 'login', 'logout']),
            'auditable_type' => fake()->randomElement(['App\\Models\\Patient', 'App\\Models\\Prescription', 'App\\Models\\Doctor']),
            'auditable_id' => fake()->numberBetween(1, 1000),
            'old_values' => null,
            'new_values' => ['status' => 'active'],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'url' => fake()->url(),
            'method' => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
