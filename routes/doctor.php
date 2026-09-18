<?php

use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor', 'hospital.context'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('patients/{patient}/medical-history', [MedicalRecordController::class, 'history'])
        ->name('patients.medical-history');
    Route::get('patients/{patient}/medical-records/create', [MedicalRecordController::class, 'create'])
        ->name('patients.medical-records.create');
    Route::post('patients/{patient}/medical-records', [MedicalRecordController::class, 'store'])
        ->name('patients.medical-records.store');
    Route::get('patients/{patient}/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])
        ->name('patients.medical-records.show');
    Route::get('patients/{patient}/medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])
        ->name('patients.medical-records.edit');
    Route::put('patients/{patient}/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])
        ->name('patients.medical-records.update');

    Route::resource('prescriptions', PrescriptionController::class)->except(['destroy']);
    Route::post('prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])
        ->name('prescriptions.cancel');
});
