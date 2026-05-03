<?php

namespace Database\Seeders;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $saId = DB::table('countries')->where('code', 'SA')->value('id');
        $cityId = DB::table('cities')->where('name', 'الرياض')->value('id');

        // ── Hospital ──────────────────────────────────────────────────────────
        $hospitalId = DB::table('hospitals')->insertGetId([
            'uuid' => Str::uuid(),
            'name' => 'مستشفى الرعاية التجريبي',
            'slug' => 'demo-hospital',
            'license_number' => 'HOSP-DEMO-001',
            'email' => 'hospital@health.test',
            'phone' => '+966500000001',
            'country_id' => $saId,
            'city_id' => $cityId,
            'address' => 'حي النزهة، الرياض',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Specialty ─────────────────────────────────────────────────────────
        $specialtyId = DB::table('specialties')->insertGetId([
            'name' => 'طب عام',
            'slug' => 'general-medicine',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Department ────────────────────────────────────────────────────────
        $departmentId = DB::table('departments')->insertGetId([
            'hospital_id' => $hospitalId,
            'name' => 'قسم الطوارئ',
            'code' => 'ER',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Pharmacy ──────────────────────────────────────────────────────────
        $pharmacyId = DB::table('pharmacies')->insertGetId([
            'uuid' => Str::uuid(),
            'hospital_id' => $hospitalId,
            'name' => 'صيدلية المستشفى',
            'slug' => 'demo-pharmacy',
            'license_number' => 'PH-DEMO-001',
            'email' => 'pharmacy@health.test',
            'phone' => '+966500000002',
            'country_id' => $saId,
            'city_id' => $cityId,
            'address' => 'حي النزهة، الرياض',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Roles ─────────────────────────────────────────────────────────────
        $roles = DB::table('roles')->pluck('id', 'name');

        // ── Shared base attributes ────────────────────────────────────────────
        $password = Hash::make('password');
        $base = [
            'password' => $password,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'gender' => UserGender::Male->value,
            'status' => UserStatus::Active->value,
            'two_factor_enabled' => false,
            'country_id' => $saId,
            'city_id' => $cityId,
            'date_of_birth' => '1985-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1. Super Admin ───────────────────────────────────────────────────────
        $superAdminId = DB::table('users')->insertGetId(array_merge($base, [
            'uuid' => Str::uuid(),
            'first_name' => 'مدير',
            'last_name' => 'النظام',
            'email' => 'superadmin@health.test',
            'phone' => '+966500000010',
        ]));
        $this->assignRole($superAdminId, $roles['super_admin'], null, $superAdminId);

        // 2. Hospital Admin ────────────────────────────────────────────────────
        $hospitalAdminId = DB::table('users')->insertGetId(array_merge($base, [
            'uuid' => Str::uuid(),
            'first_name' => 'مدير',
            'last_name' => 'المستشفى',
            'email' => 'hospital-admin@health.test',
            'phone' => '+966500000020',
        ]));
        $this->assignRole($hospitalAdminId, $roles['hospital_admin'], $hospitalId, $superAdminId);

        // 3. Doctor ───────────────────────────────────────────────────────────
        $doctorUserId = DB::table('users')->insertGetId(array_merge($base, [
            'uuid' => Str::uuid(),
            'first_name' => 'أحمد',
            'last_name' => 'الطبيب',
            'email' => 'doctor@health.test',
            'phone' => '+966500000030',
        ]));
        $this->assignRole($doctorUserId, $roles['doctor'], $hospitalId, $superAdminId);

        DB::table('doctors')->insert([
            'user_id' => $doctorUserId,
            'hospital_id' => $hospitalId,
            'department_id' => $departmentId,
            'primary_specialty_id' => $specialtyId,
            'license_number' => 'DR-DEMO-001',
            'license_expires_at' => '2030-01-01',
            'years_of_experience' => 10,
            'consultation_fee' => 200.00,
            'is_available' => true,
            'status' => 'active',
            'joined_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Pharmacist ────────────────────────────────────────────────────────
        $pharmacistUserId = DB::table('users')->insertGetId(array_merge($base, [
            'uuid' => Str::uuid(),
            'first_name' => 'سارة',
            'last_name' => 'الصيدلانية',
            'email' => 'pharmacist@health.test',
            'phone' => '+966500000040',
            'gender' => UserGender::Female->value,
        ]));
        $this->assignRole($pharmacistUserId, $roles['pharmacist'], $hospitalId, $superAdminId);

        DB::table('pharmacists')->insert([
            'user_id' => $pharmacistUserId,
            'pharmacy_id' => $pharmacyId,
            'license_number' => 'PH-DR-DEMO-001',
            'license_expires_at' => '2030-01-01',
            'position' => 'صيدلاني',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Patient ───────────────────────────────────────────────────────────
        $patientUserId = DB::table('users')->insertGetId(array_merge($base, [
            'uuid' => Str::uuid(),
            'first_name' => 'محمد',
            'last_name' => 'المريض',
            'email' => 'patient@health.test',
            'phone' => '+966500000050',
        ]));
        $this->assignRole($patientUserId, $roles['patient'], null, $superAdminId);

        DB::table('patients')->insert([
            'user_id' => $patientUserId,
            'hospital_id' => $hospitalId,
            'city_id' => $cityId,
            'medical_record_number' => 'MRN-DEMO-001',
            'blood_type' => 'O+',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✓ تم إنشاء المستخدمين:');
        $this->command->table(
            ['الدور', 'البريد الإلكتروني', 'كلمة المرور'],
            [
                ['مدير النظام',   'superadmin@health.test',    'password'],
                ['مدير المستشفى', 'hospital-admin@health.test', 'password'],
                ['طبيب',          'doctor@health.test',         'password'],
                ['صيدلاني',       'pharmacist@health.test',     'password'],
                ['مريض',          'patient@health.test',        'password'],
            ]
        );
    }

    private function assignRole(int $userId, int $roleId, ?int $hospitalId, int $assignedBy): void
    {
        DB::table('user_roles')->updateOrInsert(
            ['user_id' => $userId, 'role_id' => $roleId],
            [
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId,
                'user_id' => $userId,
                'role_id' => $roleId,
                'hospital_id' => $hospitalId,
                'assigned_by' => $assignedBy,
                'assigned_at' => now(),
            ]
        );
    }
}
