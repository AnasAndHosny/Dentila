<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\PatientTreatmentNote;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\PatientTreatmentNoteService;
use App\Http\Requests\V1\PatientTreatmentNote\StorePatientTreatmentNoteRequest;
use App\Http\Requests\V1\PatientTreatmentNote\UpdatePatientTreatmentNoteRequest;

class PatientTreatmentNoteController extends Controller
{
    use HandlesServiceResponse;

    private PatientTreatmentNoteService $patientTreatmentNoteService;

    public function __construct(PatientTreatmentNoteService $patientTreatmentNoteService)
    {
        $this->patientTreatmentNoteService = $patientTreatmentNoteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentNoteService->index($patient)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientTreatmentNoteRequest $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentNoteService->store($request, $patient)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientTreatmentNote $patientTreatmentNote): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentNoteService->show($patientTreatmentNote)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientTreatmentNote $patientTreatmentNote): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientTreatmentNoteService->destroy($patientTreatmentNote)
        );
    }
}
