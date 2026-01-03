<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisinfectionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDocumentController;
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

        Route::prefix('/disinfection')->group(function () {
            Route::get('/', [DashboardController::class, 'disinfection'])->name('dashboard.disinfection');
            Route::get('/get', [DisinfectionController::class, 'index'])->name('dashboard.disinfection.index');
            Route::get('/get/{disinfection}', [DisinfectionController::class, 'show'])->name('dashboard.disinfection.show');
            Route::get('/vehicles', [DisinfectionController::class, 'getVehicles'])->name('dashboard.disinfection.vehicles');
            Route::post('/store', [DisinfectionController::class, 'store'])->name('dashboard.disinfection.store');
            Route::delete('/destroy/{disinfection}', [DisinfectionController::class, 'destroy'])->name('dashboard.disinfection.destroy');
            Route::put('/update/{disinfection}', [DisinfectionController::class, 'update'])->name('dashboard.disinfection.update');
        });

        // Stock routes
        Route::prefix('/stock')->group(function () {
            Route::get('/', [DashboardController::class, 'stock'])->name('dashboard.stock');
            Route::get('/get', [StockController::class, 'index'])->name('dashboard.stock.index');
            Route::get('/get/{stockItem}', [StockController::class, 'show'])->name('dashboard.stock.show');
            Route::get('/movements/{stockItem}', [StockController::class, 'movements'])->name('dashboard.stock.movements');
            Route::post('/store', [StockController::class, 'store'])->name('dashboard.stock.store');
            Route::post('/movement/{stockItem}', [StockController::class, 'addMovement'])->name('dashboard.stock.movement');
            Route::put('/update/{stockItem}', [StockController::class, 'update'])->name('dashboard.stock.update');
            Route::delete('/destroy/{stockItem}', [StockController::class, 'destroy'])->name('dashboard.stock.destroy');
        });

        // Mes documents (user's own documents)
        Route::prefix('/my-documents')->group(function () {
            Route::get('/', [DashboardController::class, 'myDocuments'])->name('dashboard.my-documents');
            Route::get('/get', [UserDocumentController::class, 'index'])->name('dashboard.my-documents.index');
            Route::get('/download/{userDocument}', [UserDocumentController::class, 'download'])->name('dashboard.my-documents.download');
            Route::post('/store', [UserDocumentController::class, 'store'])->name('dashboard.my-documents.store');
            Route::put('/update/{userDocument}', [UserDocumentController::class, 'update'])->name('dashboard.my-documents.update');
            Route::delete('/destroy/{userDocument}', [UserDocumentController::class, 'destroy'])->name('dashboard.my-documents.destroy');
        });

        // Documents entreprise (admin/owner only)
        Route::prefix('/documents')->group(function () {
            Route::get('/', [DashboardController::class, 'documents'])->name('dashboard.documents');
            Route::get('/all', [DocumentController::class, 'index'])->name('dashboard.documents.all');
            Route::get('/company', [DocumentController::class, 'companyDocuments'])->name('dashboard.documents.company');
            Route::get('/users', [DocumentController::class, 'allUserDocuments'])->name('dashboard.documents.users');
            Route::get('/company/download/{companyDocument}', [DocumentController::class, 'downloadCompanyDocument'])->name('dashboard.documents.company.download');
            Route::get('/user/download/{userDocument}', [DocumentController::class, 'downloadUserDocument'])->name('dashboard.documents.user.download');
            Route::post('/company/store', [DocumentController::class, 'storeCompanyDocument'])->name('dashboard.documents.company.store');
            Route::put('/company/update/{companyDocument}', [DocumentController::class, 'updateCompanyDocument'])->name('dashboard.documents.company.update');
            Route::delete('/company/destroy/{companyDocument}', [DocumentController::class, 'destroyCompanyDocument'])->name('dashboard.documents.company.destroy');
        });

        Route::get('/documentation', [DashboardController::class, 'documentation'])->name('dashboard.documentation');

    });

});