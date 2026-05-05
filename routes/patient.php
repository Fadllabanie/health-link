<?php

use App\Http\Controllers\Patient\DashboardController;
use App\Http\Controllers\Patient\MedicalHistoryController;
use App\Http\Controllers\Patient\PrescriptionController;
use App\Http\Controllers\Patient\QrCodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('qr-code', [QrCodeController::class, 'show'])->name('qr-code.show');
    Route::post('qr-code/regenerate', [QrCodeController::class, 'regenerate'])->name('qr-code.regenerate');

    Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('prescriptions/latest', [PrescriptionController::class, 'latest'])->name('prescriptions.latest');
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    Route::get('medical-history', [MedicalHistoryController::class, 'index'])->name('medical-history.index');
});
