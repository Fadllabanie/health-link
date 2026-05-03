<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\Pharmacist;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PharmacyOnboardingService
{
    /**
     * Create User + Pharmacist record + assign role in a single transaction.
     *
     * @param  array<string, mixed>  $data
     */
    public function onboard(array $data, Pharmacy $pharmacy): Pharmacist
    {
        return DB::transaction(function () use ($data, $pharmacy): Pharmacist {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'status' => UserStatus::Active,
            ]);

            $user->assignRoleInHospital('pharmacist', $pharmacy->hospital_id, auth()->id());

            $pharmacist = Pharmacist::create([
                'user_id' => $user->id,
                'pharmacy_id' => $pharmacy->id,
                'license_number' => $data['license_number'] ?? null,
                'position' => $data['position'] ?? null,
                'is_active' => true,
            ]);

            return $pharmacist->load('user');
        });
    }
}
