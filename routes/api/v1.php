<?php

use App\Http\Middleware\Cors;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\V1\UserBanned;
use App\Http\Middleware\V1\UserVerified;
use App\Http\Controllers\Api\V1\OtpController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ToothController;
use App\Http\Controllers\Api\V1\DiseaseController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\MedicationController;
use App\Http\Controllers\Api\V1\ToothStatusController;
use App\Http\Controllers\Api\V1\TreatmentNoteController;
use App\Http\Controllers\Api\V1\TreatmentPlanController;
use App\Http\Controllers\Api\V1\TreatmentStepController;
use App\Http\Controllers\Api\V1\MedicationPlanController;
use App\Http\Controllers\Api\V1\PatientAccountController;
use App\Http\Controllers\Api\V1\IntakeMedicationController;
use App\Http\Controllers\Api\V1\PatientTreatmentController;
use App\Http\Controllers\Api\V1\TreatmentSubstepController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\V1\TreatmentEvaluationController;
use App\Http\Controllers\Api\V1\PatientTreatmentNoteController;
use App\Http\Controllers\Api\V1\PatientMedicationPlanController;
use App\Http\Controllers\Api\V1\Auth\PhoneVerificationController;

Route::prefix('v1')->middleware([Cors::class])->group(function () {
    Route::get('test', function () {
        return response()->json(['message' => 'API V1 working']);
    });

    Route::post('{role}/login', [AuthController::class, 'login'])->whereIn('role', ['manager', 'doctor', 'patient', 'receptionist']);
    Route::post('signup', [PatientController::class, 'signup']);
    Route::post('auth/check-phone', [AuthController::class, 'checkPhone']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::prefix('otp')->group(function () {
        Route::get('phone-verification', [PhoneVerificationController::class, 'sendPhoneVerification'])->middleware('auth:sanctum');
        Route::post('phone-verification', [PhoneVerificationController::class, 'phoneVerification'])->middleware('auth:sanctum');
        Route::post('password/forget-password', [ForgetPasswordController::class, 'forgetPassword']);
        Route::post('password/reset', [ResetPasswordController::class, 'passwordReset']);
        Route::post('check', [OtpController::class, 'check']);
    });

    Route::get('disease', [DiseaseController::class, 'index']);
    Route::get('intake-medication', [IntakeMedicationController::class, 'index']);

    Route::middleware(['auth:sanctum', UserBanned::class, UserVerified::class])->group(function () {
        Route::post('password/change', [ChangePasswordController::class, 'passwordChange']);

        Route::controller(AuthController::class)->group(function () {
            Route::post('employee/{employee}/ban', 'employeeBan');
            Route::get('employee/{employee}/unban', 'employeeUnban');
            Route::post('patient/{patient}/ban', 'patientBan');
            Route::get('patient/{patient}/unban', 'patientUnban');
            Route::get('employee/profile', 'employeeProfile');
            Route::get('patient/profile', 'patientProfile');
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

        Route::prefix('treatment-substep')->controller(TreatmentSubstepController::class)->group(function () {
            Route::post('/', 'store');
            Route::patch('{treatmentSubstep}', 'update');
            Route::delete('{treatmentSubstep}', 'destroy');
        });

        Route::prefix('disease')->controller(DiseaseController::class)->group(function () {
            Route::post('/', 'store');
            Route::patch('{disease}', 'update');
            Route::delete('{disease}', 'destroy');
        });

        Route::prefix('intake-medication')->controller(IntakeMedicationController::class)->group(function () {
            Route::post('/', 'store');
            Route::patch('{intakeMedication}', 'update');
            Route::delete('{intakeMedication}', 'destroy');
        });

        Route::prefix('patient')->controller(PatientController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{patient}', 'show');
            Route::patch('{patient}', 'update');
            Route::delete('{patient}', 'destroy');
        });

        Route::prefix('patient')->group(function () {
            Route::patch('{patient}/tooth/{tooth}', [ToothController::class, 'update']);

            Route::get('{patient}/treatment', [PatientTreatmentController::class, 'index']);

            Route::get('me/note', [PatientTreatmentNoteController::class, 'myIndex']);
            Route::get('{patient}/note', [PatientTreatmentNoteController::class, 'index']);
            Route::post('{patient}/note', [PatientTreatmentNoteController::class, 'store']);

            Route::get('me/medication', [PatientMedicationPlanController::class, 'myIndex']);
            Route::get('{patient}/medication', [PatientMedicationPlanController::class, 'index']);
            Route::post('{patient}/medication', [PatientMedicationPlanController::class, 'store']);

            Route::get('me/account/transactions', [PatientAccountController::class, 'myTransactions']);
            Route::get('{patient}/account/transactions', [PatientAccountController::class, 'transactions']);
            Route::post('{patient}/account/deposit', [PatientAccountController::class, 'deposit']);
            Route::post('{patient}/account/withdraw', [PatientAccountController::class, 'withdraw']);

            Route::get('me/treatment/evaluation', [TreatmentEvaluationController::class, 'myEvaluations']);
        });

        Route::prefix('patient-treatment')->controller(PatientTreatmentController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('{patientTreatment}', 'show');
            Route::put('{patientTreatment}', 'update');
            Route::patch('{patientTreatment}/note', 'updateNote');
            Route::patch('{patientTreatment}/check', 'updateCheck');
            Route::delete('{patientTreatment}', 'destroy');
        });

        Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{employee}', 'show');
            Route::patch('{employee}', 'update');
            Route::delete('{employee}', 'destroy');
        });

        Route::prefix('patient-note')->controller(PatientTreatmentNoteController::class)->group(function () {
            Route::get('{patientTreatmentNote}', 'show');
            Route::delete('{patientTreatmentNote}', 'destroy');
        });

        Route::prefix('patient-medication')->controller(PatientMedicationPlanController::class)->group(function () {
            Route::get('{patientMedicationPlan}', 'show');
            Route::delete('{patientMedicationPlan}', 'destroy');
        });

        Route::prefix('treatment/evaluation')->controller(TreatmentEvaluationController::class)->group(function () {
            Route::get('{treatmentEvaluation}', 'show');
            Route::patch('{treatmentEvaluation}/rate', 'rate');
            Route::delete('{treatmentEvaluation}/dismes', 'dismes');
        });

        Route::get('doctor', [TreatmentEvaluationController::class, 'doctors']);
        Route::get('doctor/{employee}/review', [TreatmentEvaluationController::class, 'doctorReviews']);
    });
});
