<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TreatmentPlan;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\TreatmentPlanService;
use App\Http\Requests\V1\TreatmentPlan\StoreTreatmentPlanRequest;
use App\Http\Requests\V1\TreatmentPlan\UpdateTreatmentPlanRequest;

class TreatmentPlanController extends Controller
{
    use HandlesServiceResponse;

    private TreatmentPlanService $treatmentPlanService;

    public function __construct(TreatmentPlanService $treatmentPlanService)
    {
        $this->treatmentPlanService = $treatmentPlanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentPlanService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreatmentPlanRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentPlanService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentPlan $treatmentPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentPlanService->show($treatmentPlan)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentPlanService->update($request, $treatmentPlan)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentPlan $treatmentPlan): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentPlanService->destroy($treatmentPlan)
        );
    }
}
