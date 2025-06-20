<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\HandlesServiceResponse;
use Throwable;
use App\Models\Medication;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\V1\MedicationService;
use App\Http\Requests\V1\Medication\StoreMedicationRequest;
use App\Http\Requests\V1\Medication\UpdateMedicationRequest;

class MedicationController extends Controller
{
    use HandlesServiceResponse;

    private MedicationService $medicationService;

    public function __construct(MedicationService $medicationService)
    {
        $this->medicationService = $medicationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationService->show($medication)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationRequest $request, Medication $medication): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationService->update($request, $medication)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationService->destroy($medication)
        );
    }
}
