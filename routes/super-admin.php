<?php

use App\Http\Controllers\SuperAdmin\CityController;
use App\Http\Controllers\SuperAdmin\CountryController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\HospitalAdminController;
use App\Http\Controllers\SuperAdmin\HospitalController;
use App\Http\Controllers\SuperAdmin\SpecialtyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Hospitals CRUD
        Route::resource('hospitals', HospitalController::class);
        Route::patch('hospitals/{hospital}/status', [HospitalController::class, 'updateStatus'])
            ->name('hospitals.update-status');

        // Master Data
        Route::prefix('master-data')->name('master-data.')->group(function () {

            // Countries
            Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
            Route::get('countries/create', [CountryController::class, 'create'])->name('countries.create');
            Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
            Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
            Route::patch('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
            Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');
            Route::post('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
            Route::post('countries/{country}/toggle', [CountryController::class, 'toggle'])->name('countries.toggle');

            // Cities
            Route::get('cities', [CityController::class, 'index'])->name('cities.index');
            Route::get('cities/create', [CityController::class, 'create'])->name('cities.create');
            Route::post('cities', [CityController::class, 'store'])->name('cities.store');
            Route::get('cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
            Route::patch('cities/{city}', [CityController::class, 'update'])->name('cities.update');
            Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
            Route::post('cities/{city}/toggle', [CityController::class, 'toggle'])->name('cities.toggle');

            // Specialties
            Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
            Route::get('specialties/create', [SpecialtyController::class, 'create'])->name('specialties.create');
            Route::post('specialties', [SpecialtyController::class, 'store'])->name('specialties.store');
            Route::get('specialties/{specialty}/edit', [SpecialtyController::class, 'edit'])->name('specialties.edit');
            Route::patch('specialties/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
            Route::delete('specialties/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');
            Route::post('specialties/{id}/restore', [SpecialtyController::class, 'restore'])->name('specialties.restore');
            Route::post('specialties/{specialty}/toggle', [SpecialtyController::class, 'toggle'])->name('specialties.toggle');

            // Departments
            Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
            Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
            Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
            Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
            Route::patch('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
            Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
            Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
            Route::post('departments/{department}/toggle', [DepartmentController::class, 'toggle'])->name('departments.toggle');
        });

        // Hospital Admin management
        Route::prefix('hospitals/{hospital}/admins')->name('hospitals.admins.')->group(function () {
            Route::get('/', [HospitalAdminController::class, 'index'])->name('index');
            Route::get('create', [HospitalAdminController::class, 'create'])->name('create');
            Route::post('/', [HospitalAdminController::class, 'store'])->name('store');
            Route::get('{user}/edit', [HospitalAdminController::class, 'edit'])->name('edit');
            Route::put('{user}', [HospitalAdminController::class, 'update'])->name('update');
            Route::patch('{user}/disable', [HospitalAdminController::class, 'disable'])->name('disable');
            Route::post('{user}/reset-password', [HospitalAdminController::class, 'resetPassword'])->name('reset-password');
        });
    });
