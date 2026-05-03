<?php

use App\Http\Controllers\Pharmacy\DashboardController;
use App\Http\Controllers\Pharmacy\InventoryController;
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Pharmacy\PrescriptionController;
use App\Http\Controllers\Pharmacy\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth', 'role:pharmacist', 'hospital.context'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Medicine catalog (global, no hospital_id scoping)
    Route::resource('medicines', MedicineController::class);
    Route::patch('medicines/{medicine}/toggle-status', [MedicineController::class, 'toggleStatus'])
        ->name('medicines.toggle-status');

    Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'show']);
    Route::post('prescriptions/{prescription}/dispense', [PrescriptionController::class, 'dispense'])
        ->name('prescriptions.dispense');
    Route::post('prescriptions/{prescription}/reject', [PrescriptionController::class, 'reject'])
        ->name('prescriptions.reject');

    // Inventory
    Route::get('inventory/expiring', [InventoryController::class, 'expiring'])->name('inventory.expiring');
    Route::resource('inventory', InventoryController::class);

    // Reports
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/movements', [ReportController::class, 'movements'])->name('reports.movements');
    Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
});
