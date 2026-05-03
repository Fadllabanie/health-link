<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientRegistrationService
{
    public function __construct(private QrCodeService $qrCodeService) {}

    /**
     * Create User + Patient + QR code in one transaction.
     *
     * @param  array<string, mixed>  $data
     * @param  Hospital  $hospital  The hospital registering this patient.
     */
    public function register(array $data, Hospital $hospital): Patient
    {
        return DB::transaction(function () use ($data, $hospital): Patient {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'national_id' => $data['national_id'] ?? null,
                'status' => UserStatus::Active,
            ]);

            $user->assignRoleInHospital('patient', $hospital->id, auth()->id());

            $patient = Patient::create([
                'user_id' => $user->id,
                'hospital_id' => $hospital->id,
                'city_id' => $data['city_id'] ?? null,
                'blood_type' => $data['blood_type'] ?? null,
                'height_cm' => $data['height_cm'] ?? null,
                'weight_kg' => $data['weight_kg'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'chronic_conditions' => $data['chronic_conditions'] ?? null,
                'current_medications' => $data['current_medications'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'emergency_contact_relation' => $data['emergency_contact_relation'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'insurance_policy_number' => $data['insurance_policy_number'] ?? null,
                'marital_status' => $data['marital_status'] ?? null,
                'occupation' => $data['occupation'] ?? null,
            ]);

            // Register the patient–hospital pivot record.
            $patient->hospitals()->attach($hospital->id, ['registered_at' => now()]);

            // Generate QR code.
            $this->qrCodeService->generateForPatient($patient);

            return $patient->load('user', 'qrCode');
        });
    }
}
