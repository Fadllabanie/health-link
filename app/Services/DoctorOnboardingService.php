<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorOnboardingService
{
    public function create(array $userData, array $doctorData, int $hospitalId): Doctor
    {
        return DB::transaction(function () use ($userData, $doctorData, $hospitalId) {
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => Hash::make($userData['password']),
                'status' => 'active',
            ]);

            $user->assignRoleInHospital('doctor', $hospitalId, auth()->id());

            $doctor = Doctor::withoutGlobalScopes()->create(array_merge($doctorData, [
                'user_id' => $user->id,
                'hospital_id' => $hospitalId,
            ]));

            if (! empty($doctorData['secondary_specialties'])) {
                $doctor->specialties()->sync($doctorData['secondary_specialties']);
            }

            return $doctor;
        });
    }

    public function update(Doctor $doctor, array $userData, array $doctorData): Doctor
    {
        return DB::transaction(function () use ($doctor, $userData, $doctorData) {
            $doctor->user->update([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone' => $userData['phone'],
            ]);

            $doctor->update($doctorData);

            $doctor->specialties()->sync($doctorData['secondary_specialties'] ?? []);

            return $doctor->fresh();
        });
    }

    public function toggleStatus(Doctor $doctor): Doctor
    {
        return DB::transaction(function () use ($doctor) {
            $newStatus = $doctor->status->value === 'active' ? 'inactive' : 'active';

            $doctor->update(['status' => $newStatus]);
            $doctor->user->update(['status' => $newStatus]);

            return $doctor->fresh();
        });
    }
}
