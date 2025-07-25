<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use App\Services\V1\PatientService;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Patient\StorePatientRequest;
use App\Http\Requests\V1\Patient\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    use HandlesServiceResponse;

    private PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientService->index($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        return $this->handleService(
            fn() =>
            $this->patientService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return $this->handleService(
            fn() =>
            $this->patientService->show($patient)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        return $this->handleService(
            fn() =>
            $this->patientService->update($request, $patient)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        return $this->handleService(
            fn() =>
            $this->patientService->destroy($patient)
        );
    }
}
