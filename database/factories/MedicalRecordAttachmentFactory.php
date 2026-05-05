<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\MedicalRecordAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalRecordAttachment>
 */
class MedicalRecordAttachmentFactory extends Factory
{
    public function definition(): array
    {
        $types = ['pdf', 'jpg', 'png', 'doc', 'docx'];
        $type = fake()->randomElement($types);

        return [
            'medical_record_id' => MedicalRecord::factory(),
            'file_path' => 'medical-records/'.fake()->uuid().'.'.$type,
            'file_name' => fake()->word().'.'.$type,
            'file_type' => $type,
            'file_size' => fake()->numberBetween(10000, 5000000),
            'description' => fake()->optional()->sentence(),
            'uploaded_by' => User::factory(),
        ];
    }
}
