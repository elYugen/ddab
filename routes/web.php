<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
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

        Route::prefix('/vehicle')->group(function () {
            Route::get('/', [DashboardController::class, 'vehicle'])->name('dashboard.vehicle');
            Route::get('/get', [VehicleController::class, 'index'])->name('dashboard.vehicle.index');
            Route::get('/get/{vehicle}', [VehicleController::class, 'show'])->name('dashboard.vehicle.show');
            Route::post('/store', [VehicleController::class, 'store'])->name('dashboard.vehicle.store');
            Route::put('/destroy/{vehicle}', [VehicleController::class, 'destroy'])->name('dashboard.vehicle.destroy');
            Route::put('/update/{vehicle}', [VehicleController::class, 'update'])->name('dashboard.vehicle.update');
        });

        Route::prefix('/user')->group(function () {
            Route::get('/', [DashboardController::class, 'user'])->name('dashboard.user');
            Route::get('/get', [UserController::class, 'index'])->name('dashboard.user.index');
            Route::get('/get/{user}', [UserController::class, 'show'])->name('dashboard.user.show');
            Route::post('/store', [UserController::class, 'store'])->name('dashboard.user.store');
            Route::put('/destroy/{user}', [UserController::class, 'destroy'])->name('dashboard.user.destroy');
            Route::put('/update/{user}', [UserController::class, 'update'])->name('dashboard.user.update');
        });


    });
    
});