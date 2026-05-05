<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrResolverController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public QR code resolver — rate limited
Route::get('/qr/{code}', QrResolverController::class)
    ->name('qr.resolve')
    ->middleware('throttle:30,1');

require __DIR__.'/auth.php';
require __DIR__.'/super-admin.php';
require __DIR__.'/hospital-admin.php';
require __DIR__.'/doctor.php';
require __DIR__.'/pharmacy.php';
require __DIR__.'/patient.php';
