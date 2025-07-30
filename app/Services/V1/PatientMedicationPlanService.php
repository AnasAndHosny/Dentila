<?php

namespace App\Services\V1;

use App\Http\Resources\V1\PatientMedicationPlanCollection;
use App\Http\Resources\V1\PatientMedicationPlanResource;
use App\Models\Patient;
use App\Models\PatientMedicationPlan;
use App\Repositories\V1\PatientMedicationPlanRepository;

class PatientMedicationPlanService
{
    protected $patientMedicationPlanRepo;

    public function __construct(PatientMedicationPlanRepository $patientMedicationPlanRepo)
    {
        $this->patientMedicationPlanRepo = $patientMedicationPlanRepo;
    }

    public function index(Patient $patient): array
    {
        $medicationPlans = $this->patientMedicationPlanRepo->all($patient);
        $medicationPlans = new PatientMedicationPlanCollection($medicationPlans);
        $message = __('messages.index_success', ['class' => __('medication plans')]);
        $code = 200;
        return ['data' => $medicationPlans, 'message' => $message, 'code' => $code];
    }

    public function store($request, Patient $patient): array
    {
        $medicationPlan = $this->patientMedicationPlanRepo->create($request, $patient);
        $medicationPlan = new PatientMedicationPlanResource($medicationPlan);

        $message = __('messages.store_success', ['class' => __('medication plan')]);
        $code = 201;
        return ['data' =>  $medicationPlan, 'message' => $message, 'code' => $code];
    }

    public function show(PatientMedicationPlan $patientMedicationPlan): array
    {
        $patientMedicationPlan = new PatientMedicationPlanResource($patientMedicationPlan);

        $message = __('messages.show_success', ['class' => __('medication plan')]);
        $code = 200;
        return ['data' => $patientMedicationPlan, 'message' => $message, 'code' => $code];
    }

    public function destroy(PatientMedicationPlan $patientMedicationPlan): array
    {
        $patientMedicationPlan = $this->patientMedicationPlanRepo->delete($patientMedicationPlan);

        $message = __('messages.destroy_success', ['class' => __('medication plan')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
