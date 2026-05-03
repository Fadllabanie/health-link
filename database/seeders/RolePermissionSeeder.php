<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin',    'display_name' => 'مدير النظام',     'guard_name' => 'web'],
            ['name' => 'hospital_admin', 'display_name' => 'مدير المستشفى',   'guard_name' => 'web'],
            ['name' => 'doctor',         'display_name' => 'طبيب',             'guard_name' => 'web'],
            ['name' => 'pharmacist',     'display_name' => 'صيدلاني',          'guard_name' => 'web'],
            ['name' => 'patient',        'display_name' => 'مريض',             'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                array_merge($role, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $permissions = [
            // hospitals
            ['name' => 'hospitals.view',          'module' => 'hospitals',     'description' => 'عرض المستشفيات'],
            ['name' => 'hospitals.create',         'module' => 'hospitals',     'description' => 'إنشاء مستشفى'],
            ['name' => 'hospitals.edit',           'module' => 'hospitals',     'description' => 'تعديل مستشفى'],
            ['name' => 'hospitals.delete',         'module' => 'hospitals',     'description' => 'حذف مستشفى'],
            ['name' => 'hospitals.toggle-status',  'module' => 'hospitals',     'description' => 'تغيير حالة مستشفى'],
            // doctors
            ['name' => 'doctors.view',             'module' => 'doctors',       'description' => 'عرض الأطباء'],
            ['name' => 'doctors.create',           'module' => 'doctors',       'description' => 'إضافة طبيب'],
            ['name' => 'doctors.edit',             'module' => 'doctors',       'description' => 'تعديل طبيب'],
            ['name' => 'doctors.disable',          'module' => 'doctors',       'description' => 'تعطيل طبيب'],
            // patients
            ['name' => 'patients.view',            'module' => 'patients',      'description' => 'عرض المرضى'],
            ['name' => 'patients.view-details',    'module' => 'patients',      'description' => 'عرض تفاصيل المريض'],
            // prescriptions
            ['name' => 'prescriptions.view',       'module' => 'prescriptions', 'description' => 'عرض الوصفات'],
            ['name' => 'prescriptions.create',     'module' => 'prescriptions', 'description' => 'إنشاء وصفة'],
            ['name' => 'prescriptions.edit',       'module' => 'prescriptions', 'description' => 'تعديل وصفة'],
            ['name' => 'prescriptions.cancel',     'module' => 'prescriptions', 'description' => 'إلغاء وصفة'],
            ['name' => 'prescriptions.dispense',   'module' => 'prescriptions', 'description' => 'صرف وصفة'],
            // medicines
            ['name' => 'medicines.view',           'module' => 'medicines',     'description' => 'عرض الأدوية'],
            ['name' => 'medicines.create',         'module' => 'medicines',     'description' => 'إضافة دواء'],
            ['name' => 'medicines.edit',           'module' => 'medicines',     'description' => 'تعديل دواء'],
            // inventory
            ['name' => 'inventory.view',           'module' => 'inventory',     'description' => 'عرض المخزون'],
            ['name' => 'inventory.manage',         'module' => 'inventory',     'description' => 'إدارة المخزون'],
            // master-data
            ['name' => 'master-data.view',         'module' => 'master-data',   'description' => 'عرض البيانات الأساسية'],
            ['name' => 'master-data.manage',       'module' => 'master-data',   'description' => 'إدارة البيانات الأساسية'],
            // audit-logs
            ['name' => 'audit-logs.view',          'module' => 'audit-logs',    'description' => 'عرض سجلات التدقيق'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                array_merge($perm, ['guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // Assign ALL permissions to super_admin
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->value('id');
        $allPermIds = DB::table('permissions')->pluck('id');
        foreach ($allPermIds as $permId) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $superAdminId, 'permission_id' => $permId]
            );
        }

        // hospital_admin permissions
        $hospitalAdminId = DB::table('roles')->where('name', 'hospital_admin')->value('id');
        $hospitalAdminPerms = [
            'hospitals.view',
            'doctors.view', 'doctors.create', 'doctors.edit', 'doctors.disable',
            'patients.view', 'patients.view-details',
            'medicines.view', 'medicines.create', 'medicines.edit',
            'inventory.view', 'inventory.manage',
            'prescriptions.view',
            'audit-logs.view',
        ];
        $this->assignPermissions($hospitalAdminId, $hospitalAdminPerms);

        // doctor permissions
        $doctorId = DB::table('roles')->where('name', 'doctor')->value('id');
        $doctorPerms = [
            'patients.view', 'patients.view-details',
            'prescriptions.view', 'prescriptions.create', 'prescriptions.edit', 'prescriptions.cancel',
            'medicines.view',
        ];
        $this->assignPermissions($doctorId, $doctorPerms);

        // pharmacist permissions
        $pharmacistId = DB::table('roles')->where('name', 'pharmacist')->value('id');
        $pharmacistPerms = [
            'prescriptions.view', 'prescriptions.dispense',
            'medicines.view', 'medicines.create', 'medicines.edit',
            'inventory.view', 'inventory.manage',
            'patients.view',
        ];
        $this->assignPermissions($pharmacistId, $pharmacistPerms);

        // patient permissions — view own data only (enforced via policies)
        $patientId = DB::table('roles')->where('name', 'patient')->value('id');
        $patientPerms = ['prescriptions.view', 'patients.view'];
        $this->assignPermissions($patientId, $patientPerms);
    }

    private function assignPermissions(int $roleId, array $permNames): void
    {
        $ids = DB::table('permissions')->whereIn('name', $permNames)->pluck('id');
        foreach ($ids as $permId) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $roleId, 'permission_id' => $permId]
            );
        }
    }
}
