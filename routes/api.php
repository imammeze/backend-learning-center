<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ParentDashboardController;
use App\Http\Controllers\Api\StudentDashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-parent', [RegistrationController::class, 'registerParent']);
Route::post('/register-student', [RegistrationController::class, 'registerStudent']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('parent')->group(function () {
        Route::get('/children', [ParentDashboardController::class, 'children']);
        Route::get('/children/{student}/registrations', [ParentDashboardController::class, 'registrations']);
        Route::get('/children/{student}/grades', [ParentDashboardController::class, 'grades']);
        Route::get('/children/{student}/schedules', [ParentDashboardController::class, 'schedules']);
    });

    Route::prefix('student')->group(function () {
        Route::get('/profile', [StudentDashboardController::class, 'profile']);
        Route::get('/modules', [StudentDashboardController::class, 'modules']);
        Route::get('/registrations', [StudentDashboardController::class, 'registrations']);
    });
});
