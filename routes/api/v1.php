<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MedicationController;
use App\Http\Controllers\Api\V1\MedicationPlanController;

Route::prefix('v1')->group(function () {
    Route::get('test', function () {
        return response()->json(['message' => 'API V1 working']);
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('logout', 'logout')->middleware('auth:sanctum');
    });

    Route::prefix('medication')->controller(MedicationController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{medication}', 'show');
        Route::patch('{medication}', 'update');
        Route::delete('{medication}', 'destroy');
        Route::get('{medication}/plans', 'showPlans');
    });

    Route::prefix('medication-plan')->controller(MedicationPlanController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{medicationPlan}', 'show');
        Route::patch('{medicationPlan}', 'update');
        Route::delete('{medicationPlan}', 'destroy');
    });
});
