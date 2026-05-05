<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;

class MedicalRecordPolicy
{
    public function viewAny(User $user, Patient $patient): bool
    {
        $doctor = $user->doctor;

        return $doctor && $doctor->hospital_id === $patient->hospital_id;
    }

    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        $doctor = $user->doctor;

        return $doctor && $doctor->hospital_id === $medicalRecord->hospital_id;
    }

    public function create(User $user, Patient $patient): bool
    {
        $doctor = $user->doctor;

        return $doctor && $doctor->hospital_id === $patient->hospital_id;
    }

    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        $doctor = $user->doctor;

        return $doctor
            && $doctor->id === $medicalRecord->doctor_id
            && $medicalRecord->canBeEdited();
    }
}
