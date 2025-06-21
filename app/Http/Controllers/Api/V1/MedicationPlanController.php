<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MedicationPlan\StoreMedicationPlanRequest;
use App\Http\Requests\V1\MedicationPlan\UpdateMedicationPlanRequest;
use App\Models\MedicationPlan;
use App\Services\V1\MedicationPlanService;
use App\Traits\HandlesServiceResponse;
use Illuminate\Http\JsonResponse;

class MedicationPlanController extends Controller
{
    use HandlesServiceResponse;

    private MedicationPlanService $medicationPlanService;

    public function __construct(MedicationPlanService $medicationPlanService)
    {
        $this->medicationPlanService = $medicationPlanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationPlanService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationPlanRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationPlanService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicationPlan $medicationPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationPlanService->show($medicationPlan)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationPlanRequest $request, MedicationPlan $medicationPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationPlanService->update($request, $medicationPlan)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicationPlan $medicationPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->medicationPlanService->destroy($medicationPlan)
        );
    }
}
