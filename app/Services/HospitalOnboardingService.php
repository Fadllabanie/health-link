<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HospitalOnboardingService
{
    /**
     * Create a hospital + first hospital admin atomically.
     *
     * @param  array<string, mixed>  $hospitalData
     * @param  array<string, mixed>  $adminData
     */
    public static function create(array $hospitalData, array $adminData): Hospital
    {
        return DB::transaction(function () use ($hospitalData, $adminData): Hospital {
            $hospital = Hospital::create($hospitalData);

            $admin = User::create([
                'first_name' => $adminData['first_name'],
                'last_name' => $adminData['last_name'],
                'email' => $adminData['email'],
                'phone' => $adminData['phone'] ?? null,
                'password' => Hash::make($adminData['password']),
                'status' => UserStatus::Active,
            ]);

            $admin->assignRoleInHospital('hospital_admin', $hospital->id, auth()->user()?->getKey());

            static::writeAuditLog('create_hospital', $hospital);

            return $hospital->load('admins');
        });
    }

    /**
     * Update hospital fields.
     *
     * @param  array<string, mixed>  $data
     */
    public static function update(Hospital $hospital, array $data): Hospital
    {
        return DB::transaction(function () use ($hospital, $data): Hospital {
            $hospital->update($data);

            static::writeAuditLog('update_hospital', $hospital);

            return $hospital;
        });
    }

    /**
     * Change hospital status and log out affected users when suspended/inactive.
     */
    public static function updateStatus(Hospital $hospital, string $status): Hospital
    {
        return DB::transaction(function () use ($hospital, $status): Hospital {
            $hospital->update(['status' => $status]);

            if (in_array($status, ['inactive', 'suspended'])) {
                // Invalidate all sessions for users of this hospital
                DB::table('sessions')
                    ->whereIn('user_id', function ($q) use ($hospital) {
                        $q->select('model_id')
                            ->from('user_roles')
                            ->where('hospital_id', $hospital->id);
                    })
                    ->delete();
            }

            static::writeAuditLog('update_hospital_status', $hospital);

            return $hospital;
        });
    }

    /**
     * Soft-delete (archive) hospital.
     */
    public static function archive(Hospital $hospital): void
    {
        DB::transaction(function () use ($hospital): void {
            static::writeAuditLog('archive_hospital', $hospital);
            $hospital->delete();
        });
    }

    private static function writeAuditLog(string $event, Hospital $hospital): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => auth()->user()?->getKey(),
            'auditable_type' => Hospital::class,
            'auditable_id' => $hospital->id,
            'action' => $event,
            'new_values' => json_encode($hospital->toArray()),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
