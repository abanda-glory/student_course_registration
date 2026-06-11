<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public course routes
Route::apiResource('courses', CourseController::class)->only(['index', 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('courses', CourseController::class)->only(['store', 'update', 'destroy']);
    Route::post('/enrollments', [EnrollmentController::class, 'enroll']);
    Route::get('/enrollments', [EnrollmentController::class, 'myEnrollments']);
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'cancel']);
    Route::get('/profile', [AuthController::class, 'profile']);
});
