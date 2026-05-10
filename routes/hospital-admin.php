<?php

use App\Http\Controllers\HospitalAdmin\DashboardController;
use App\Http\Controllers\HospitalAdmin\DoctorController;
use App\Http\Controllers\HospitalAdmin\DoctorScheduleController;
use App\Http\Controllers\HospitalAdmin\InventoryController;
use App\Http\Controllers\HospitalAdmin\MedicineController;
use App\Http\Controllers\HospitalAdmin\PatientController;
use App\Http\Controllers\HospitalAdmin\PharmacistController;
use App\Http\Controllers\HospitalAdmin\PharmacyController;
use App\Http\Controllers\HospitalAdmin\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:hospital_admin', 'hospital.context'])
    ->prefix('hospital-admin')
    ->name('hospital-admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Doctors CRUD
        Route::resource('doctors', DoctorController::class);
        Route::patch('doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])
            ->name('doctors.toggle-status');

        // Doctor Schedules
        Route::get('doctors/{doctor}/schedules', [DoctorScheduleController::class, 'edit'])
            ->name('doctors.schedules.edit');
        Route::put('doctors/{doctor}/schedules', [DoctorScheduleController::class, 'update'])
            ->name('doctors.schedules.update');

        // Patients (read-only list + create by admin/doctor)
        Route::resource('patients', PatientController::class)
            ->only(['index',
                // 'create', 'store',
                'show']);

        // Medicine catalog (global, no hospital_id scoping)
        Route::resource('medicines', MedicineController::class);
        Route::patch('medicines/{medicine}/toggle-status', [MedicineController::class, 'toggleStatus'])
            ->name('medicines.toggle-status');

        // Pharmacies CRUD
        Route::resource('pharmacies', PharmacyController::class);

        // Pharmacists (nested under pharmacy)
        Route::resource('pharmacies.pharmacists', PharmacistController::class)
            ->only(['create', 'store', 'destroy']);

        // Inventory management (nested under pharmacy) — T9.10
        Route::resource('pharmacies.inventory', InventoryController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

        // Prescription orders monitoring (read-only) — T9.11
        Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    });
