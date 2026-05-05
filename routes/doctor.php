<?php

use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor', 'hospital.context'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
