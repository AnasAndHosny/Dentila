<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\PatientMedicationPlan;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\PatientMedicationPlan\StorePatientMedicationPlanRequest;
use App\Services\V1\PatientMedicationPlanService;

class PatientMedicationPlanController extends Controller
{
    use HandlesServiceResponse;

    private PatientMedicationPlanService $patientMedicationPlanService;

    public function __construct(PatientMedicationPlanService $patientMedicationPlanService)
    {
        $this->patientMedicationPlanService = $patientMedicationPlanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientMedicationPlanService->index($patient)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientMedicationPlanRequest $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientMedicationPlanService->store($request, $patient)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientMedicationPlan $patientMedicationPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientMedicationPlanService->show($patientMedicationPlan)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientMedicationPlan $patientMedicationPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientMedicationPlanService->destroy($patientMedicationPlan)
        );
    }
}
