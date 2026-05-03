<?php

use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor', 'hospital.context'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('prescriptions', PrescriptionController::class)->except(['destroy']);
    Route::post('prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])
        ->name('prescriptions.cancel');
});
