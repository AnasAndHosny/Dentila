<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\PatientTreatment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\PatientTreatmentService;
use App\Http\Requests\V1\PatientTreatment\StorePatientTreatmentRequest;
use App\Http\Requests\V1\PatientTreatment\UpdatePatientStepNoteRequest;
use App\Http\Requests\V1\PatientTreatment\UpdatePatientStepCheckRequest;
use App\Http\Requests\V1\PatientTreatment\UpdatePatientTreatmentRequest;

class PatientTreatmentController extends Controller
{
    use HandlesServiceResponse;

    private PatientTreatmentService $patientTreatmentService;

    public function __construct(PatientTreatmentService $patientTreatmentService)
    {
        $this->patientTreatmentService = $patientTreatmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->index($request, $patient)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientTreatmentRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientTreatment $patientTreatment): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->show($patientTreatment)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientTreatmentRequest $request, PatientTreatment $patientTreatment): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->update($request, $patientTreatment)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateNote(UpdatePatientStepNoteRequest $request, PatientTreatment $patientTreatment): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->updateNote($request, $patientTreatment)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCheck(UpdatePatientStepCheckRequest $request, PatientTreatment $patientTreatment): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->updateCheck($request, $patientTreatment)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientTreatment $patientTreatment): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentService->destroy($patientTreatment)
        );
    }
}
