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
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\QueueTurnController;
use App\Http\Controllers\Api\V1\MedicationController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ToothStatusController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use App\Http\Controllers\Api\V1\TreatmentNoteController;
use App\Http\Controllers\Api\V1\TreatmentPlanController;
use App\Http\Controllers\Api\V1\TreatmentStepController;
use App\Http\Controllers\Api\V1\MedicationPlanController;
use App\Http\Controllers\Api\V1\PatientAccountController;
use App\Http\Controllers\Api\V1\IntakeMedicationController;
use App\Http\Controllers\Api\V1\PatientTreatmentController;
use App\Http\Controllers\Api\V1\TreatmentSubstepController;
use App\Http\Controllers\Api\V1\DoctorWorkingHourController;
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

        Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

    Route::middleware(['auth:sanctum', UserBanned::class, UserVerified::class])->group(function () {
        Route::post('password/change', [ChangePasswordController::class, 'passwordChange']);

        Route::controller(AuthController::class)->group(function () {
            Route::post('employee/{employee}/ban', 'employeeBan')->middleware('can:user.ban');
            Route::get('employee/{employee}/unban', 'employeeUnban')->middleware('can:user.unban');
            Route::post('patient/{patient}/ban', 'patientBan')->middleware('can:user.ban');
            Route::get('patient/{patient}/unban', 'patientUnban')->middleware('can:user.unban');
            Route::get('employee/profile', 'employeeProfile')->middleware('role:doctor|receptionist');
            Route::get('patient/profile', 'patientProfile')->middleware('role:patient');
        });

        Route::prefix('medication')->controller(MedicationController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:medication.index');
            Route::post('/', 'store')->middleware('can:medication.store');
            Route::get('{medication}', 'show')->middleware('can:medication.show');
            Route::patch('{medication}', 'update')->middleware('can:medication.update');
            Route::delete('{medication}', 'destroy')->middleware('can:medication.destroy');
            Route::get('{medication}/plans', 'showPlans')->middleware('can:medication.showPlans');
        });

        Route::prefix('medication-plan')->controller(MedicationPlanController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:medicationPlan.index');
            Route::post('/', 'store')->middleware('can:medication.store');
            Route::get('{medicationPlan}', 'show')->middleware('can:medication.show');
            Route::patch('{medicationPlan}', 'update')->middleware('can:medication.update');
            Route::delete('{medicationPlan}', 'destroy')->middleware('can:medication.destroy');
        });

        Route::prefix('treatment-note')->controller(TreatmentNoteController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:treatmentNote.index');
            Route::post('/', 'store')->middleware('can:treatmentNote.store');
            Route::get('{treatmentNote}', 'show')->middleware('can:treatmentNote.show');
            Route::patch('{treatmentNote}', 'update')->middleware('can:treatmentNote.update');
            Route::delete('{treatmentNote}', 'destroy')->middleware('can:treatmentNote.destroy');
        });

        Route::prefix('category')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:category.index');
            Route::post('/', 'store')->middleware('can:category.store');
            Route::get('{category}', 'show')->middleware('can:category.show');
            Route::patch('{category}', 'update')->middleware('can:category.update');
            Route::delete('{category}', 'destroy')->middleware('can:category.destroy');
            Route::get('{category}/plans', 'showPlans')->middleware('can:category.showPlans');
        });

        Route::prefix('tooth-status')->controller(ToothStatusController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:toothStatus.index');
        });

        Route::prefix('treatment-plan')->controller(TreatmentPlanController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:treatmentPlan.index');
            Route::post('/', 'store')->middleware('can:treatmentPlan.store');
            Route::get('{treatmentPlan}', 'show')->middleware('can:treatmentPlan.show');
            Route::patch('{treatmentPlan}', 'update')->middleware('can:treatmentPlan.update');
            Route::delete('{treatmentPlan}', 'destroy')->middleware('can:treatmentPlan.destroy');
        });

        Route::prefix('treatment-step')->controller(TreatmentStepController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:treatmentPlan.store');
            Route::patch('{treatmentStep}', 'update')->middleware('can:treatmentPlan.update');
            Route::delete('{treatmentStep}', 'destroy')->middleware('can:treatmentPlan.destroy');
        });

        Route::prefix('treatment-substep')->controller(TreatmentSubstepController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:treatmentPlan.store');
            Route::patch('{treatmentSubstep}', 'update')->middleware('can:treatmentPlan.update');
            Route::delete('{treatmentSubstep}', 'destroy')->middleware('can:treatmentPlan.destroy');
        });

        Route::prefix('disease')->controller(DiseaseController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:disease.store');
            Route::patch('{disease}', 'update')->middleware('can:disease.update');
            Route::delete('{disease}', 'destroy')->middleware('can:disease.destroy');
        });

        Route::prefix('intake-medication')->controller(IntakeMedicationController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:intakeMedication.store');
            Route::patch('{intakeMedication}', 'update')->middleware('can:intakeMedication.update');
            Route::delete('{intakeMedication}', 'destroy')->middleware('can:intakeMedication.destroy');
        });

        Route::prefix('patient')->controller(PatientController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:patient.index');
            Route::post('/', 'store')->middleware('can:patient.store');
            Route::get('{patient}', 'show')->middleware('can:patient.show');
            Route::patch('{patient}', 'update')->middleware('can:patient.update');
            Route::delete('{patient}', 'destroy')->middleware('can:patient.destroy');
        });

        Route::prefix('patient')->group(function () {
            Route::patch('{patient}/tooth/{tooth}', [ToothController::class, 'update'])->middleware('can:patientTreatment.update');

            Route::get('{patient}/treatment', [PatientTreatmentController::class, 'index'])->middleware('can:patientTreatment.index');

            Route::get('me/note', [PatientTreatmentNoteController::class, 'myIndex'])->middleware('can:patientNote.index.my');
            Route::get('{patient}/note', [PatientTreatmentNoteController::class, 'index'])->middleware('can:patientNote.index');
            Route::post('{patient}/note', [PatientTreatmentNoteController::class, 'store'])->middleware('can:patientNote.store');

            Route::get('me/medication', [PatientMedicationPlanController::class, 'myIndex'])->middleware('can:patientMedication.index.my');
            Route::get('{patient}/medication', [PatientMedicationPlanController::class, 'index'])->middleware('can:patientMedication.index');
            Route::post('{patient}/medication', [PatientMedicationPlanController::class, 'store'])->middleware('can:patientMedication.store');

            Route::get('me/account/transactions', [PatientAccountController::class, 'myTransactions'])->middleware('can:account.index.my');
            Route::get('{patient}/account/transactions', [PatientAccountController::class, 'transactions'])->middleware('can:account.index');
            Route::post('{patient}/account/deposit', [PatientAccountController::class, 'deposit'])->middleware('can:account.deposit');
            Route::post('{patient}/account/withdraw', [PatientAccountController::class, 'withdraw'])->middleware('can:account.withdraw');

            Route::get('me/treatment/evaluation', [TreatmentEvaluationController::class, 'myEvaluations'])->middleware('can:treatmentEvaluation.index.my');
        });

        Route::prefix('patient-treatment')->controller(PatientTreatmentController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:patientTreatment.store');
            Route::get('{patientTreatment}', 'show')->middleware('can:patientTreatment.show');
            Route::put('{patientTreatment}', 'update')->middleware('can:patientTreatment.update');
            Route::patch('{patientTreatment}/note', 'updateNote')->middleware('can:patientTreatment.update');
            Route::patch('{patientTreatment}/check', 'updateCheck')->middleware('can:patientTreatment.update');
            Route::delete('{patientTreatment}', 'destroy')->middleware('can:patientTreatment.destroy');
        });

        Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:employee.index');
            Route::post('/', 'store')->middleware('can:employee.store');
            Route::get('{employee}', 'show')->middleware('can:employee.show');
            Route::patch('{employee}', 'update')->middleware('can:employee.update');
            Route::delete('{employee}', 'destroy')->middleware('can:employee.destroy');
        });

        Route::prefix('patient-note')->controller(PatientTreatmentNoteController::class)->group(function () {
            Route::get('{patientTreatmentNote}', 'show')->middleware('can:view,patientTreatmentNote');
            Route::delete('{patientTreatmentNote}', 'destroy')->middleware('can:patientNote.destroy');
        });

        Route::prefix('patient-medication')->controller(PatientMedicationPlanController::class)->group(function () {
            Route::get('{patientMedicationPlan}', 'show')->middleware('can:view,patientMedicationPlan');
            Route::delete('{patientMedicationPlan}', 'destroy')->middleware('can:patientMedication.destroy');
        });

        Route::prefix('treatment/evaluation')->controller(TreatmentEvaluationController::class)->group(function () {
            Route::get('{treatmentEvaluation}', 'show')->middleware('can:view,treatmentEvaluation');
            Route::patch('{treatmentEvaluation}/rate', 'rate')->middleware('can:rate,treatmentEvaluation');
            Route::delete('{treatmentEvaluation}/dismes', 'dismes')->middleware('can:dismes,treatmentEvaluation');
        });

        Route::get('doctor', [TreatmentEvaluationController::class, 'doctors'])->middleware('can:doctor.index');
        Route::get('doctor/{employee}/review', [TreatmentEvaluationController::class, 'doctorReviews'])->middleware('can:doctor.showReviews');

        Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('mark-all', 'markAllAsRead');
        });

        Route::prefix('appointment')->controller(AppointmentController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:appointment.index');
            Route::post('/', 'store')->middleware('can:create,App\Models\Appointment');
            Route::patch('{appointment}', 'update')->middleware('can:appointment.update');
            Route::delete('{appointment}', 'delete')->middleware('can:delete,appointment');
        });

        Route::get('patient/me/appointments', [AppointmentController::class, 'getPatientAppointments'])->middleware('can:appointment.patient.my');
        Route::get('patient/{patient}/appointments', [AppointmentController::class, 'getAppointmentsByPatient'])->middleware('can:appointment.index');
        Route::get('doctor/me/appointments', [AppointmentController::class, 'getDoctorAppointments'])->middleware('can:appointment.doctor.my');
        Route::get('doctor/{employee}/appointments', [AppointmentController::class, 'getAppointmentsByDoctor'])->middleware('can:appointment.index');
        Route::post('doctor/{employee}/appointments/shift', [AppointmentController::class, 'shiftAppointments'])->middleware('can:appointment.update');
        Route::post('/appointments/available-slots', [AppointmentController::class, 'getAvailableSlots']);
        Route::get('appointments/check-in/{code}', [AppointmentController::class, 'checkIn'])->middleware('can:queue.checkIn');

        Route::prefix('queue-turns')->controller(QueueTurnController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:queue.store');
            Route::patch('/{queueTurn}', 'update')->middleware('can:queue.update');
            Route::get('/', 'index')->middleware('can:queue.index');
            Route::get('/history', 'history')->middleware('can:queue.index');
        });

        Route::get('doctor/my/working-hours', [DoctorWorkingHourController::class, 'myIndex'])->middleware('can:working-hours.index.my');
        Route::get('doctor/{employee}/working-hours', [DoctorWorkingHourController::class, 'index'])->middleware('can:working-hours.index');

        Route::prefix('doctor-working-hours')->controller(DoctorWorkingHourController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:working-hours.store');
            Route::put('/{doctorWorkingHour}', 'update')->middleware('can:update,doctorWorkingHour');
            Route::delete('/{doctorWorkingHour}', 'destroy')->middleware('can:delete,doctorWorkingHour');
        });

        Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent'])->middleware('role:patient');
    });
});
