<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MedicationController;
use App\Http\Controllers\Api\V1\TreatmentNoteController;
use App\Http\Controllers\Api\V1\MedicationPlanController;
use App\Http\Controllers\Api\V1\ToothStatusController;
use App\Http\Controllers\Api\V1\TreatmentPlanController;
use App\Http\Controllers\Api\V1\TreatmentStepController;

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

    Route::prefix('treatment-note')->controller(TreatmentNoteController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{treatmentNote}', 'show');
        Route::patch('{treatmentNote}', 'update');
        Route::delete('{treatmentNote}', 'destroy');
    });

    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{category}', 'show');
        Route::patch('{category}', 'update');
        Route::delete('{category}', 'destroy');
        Route::get('{category}/plans', 'showPlans');
    });

    Route::prefix('tooth-status')->controller(ToothStatusController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('treatment-plan')->controller(TreatmentPlanController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{treatmentPlan}', 'show');
        Route::patch('{treatmentPlan}', 'update');
        Route::delete('{treatmentPlan}', 'destroy');
    });

    Route::prefix('treatment-step')->controller(TreatmentStepController::class)->group(function () {
        Route::post('/', 'store');
        Route::patch('{treatmentStep}', 'update');
        Route::delete('{treatmentStep}', 'destroy');
    });
});
