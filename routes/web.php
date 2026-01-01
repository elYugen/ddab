<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerForm'])->name('registerForm');

Route::prefix('/dashboard')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::get('', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('/patient')->group(function () {
            Route::get('/', [DashboardController::class, 'patient'])->name('dashboard.patient');
            Route::get('/get', [PatientController::class, 'index'])->name('dashboard.patient.index');
            Route::get('/get/{patient}', [PatientController::class, 'show'])->name('dashboard.patient.show');
            Route::post('/store', [PatientController::class, 'store'])->name('dashboard.patient.store');
            Route::put('/destroy/{patient}', [PatientController::class, 'destroy'])->name('dashboard.patient.destroy');
            Route::put('/update/{patient}', [PatientController::class, 'update'])->name('dashboard.patient.update');
        });


    });
    
});