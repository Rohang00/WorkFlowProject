<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, ProjectController, TaskController, OrganizationController};

// Public Routes
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Protected Routes with Sanctum Middleware
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth-related routes
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Project routes
    Route::apiResource('/projects', ProjectController::class);

    // Task routes
    Route::apiResource('/tasks', TaskController::class);

    //Organization routes
    Route::apiResource('/organizations',OrganizationController::class);
});
