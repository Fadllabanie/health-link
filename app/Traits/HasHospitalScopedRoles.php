<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

trait HasHospitalScopedRoles
{
    public function hasRoleInHospital(string $role, int $hospitalId): bool
    {
        return DB::table('user_roles')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.model_id', $this->id)
            ->where('user_roles.model_type', static::class)
            ->where('roles.name', $role)
            ->where('user_roles.hospital_id', $hospitalId)
            ->exists();
    }

    public function hasPermissionInHospital(string $permission, int $hospitalId): bool
    {
        return DB::table('user_roles')
            ->join('role_permissions', 'role_permissions.role_id', '=', 'user_roles.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('user_roles.model_id', $this->id)
            ->where('user_roles.model_type', static::class)
            ->where('user_roles.hospital_id', $hospitalId)
            ->where('permissions.name', $permission)
            ->exists();
    }

    public function assignRoleInHospital(string $role, ?int $hospitalId, ?int $assignedBy = null): void
    {
        $roleId = DB::table('roles')->where('name', $role)->value('id');

        if (! $roleId) {
            return;
        }

        DB::table('user_roles')->updateOrInsert(
            [
                'model_id' => $this->id,
                'model_type' => static::class,
                'role_id' => $roleId,
                'hospital_id' => $hospitalId,
            ],
            [
                'user_id' => $this->id,
                'assigned_by' => $assignedBy,
                'assigned_at' => now(),
            ]
        );
    }

    public function currentHospital(): ?int
    {
        return Session::get('current_hospital_id');
    }

    public function hospitalIds(): array
    {
        return DB::table('user_roles')
            ->where('model_id', $this->id)
            ->where('model_type', static::class)
            ->whereNotNull('hospital_id')
            ->pluck('hospital_id')
            ->unique()
            ->values()
            ->toArray();
    }
}
