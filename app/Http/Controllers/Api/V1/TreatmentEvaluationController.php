<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Models\TreatmentEvaluation;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\TreatmentEvaluationService;
use App\Http\Requests\V1\TreatmentEvaluation\RateEvaluationRequest;
use App\Models\Employee;

class TreatmentEvaluationController extends Controller
{
    use HandlesServiceResponse;

    private TreatmentEvaluationService $treatmentEvaluationService;

    public function __construct(TreatmentEvaluationService $treatmentEvaluationService)
    {
        $this->treatmentEvaluationService = $treatmentEvaluationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function myEvaluations(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->myEvaluations()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentEvaluation $treatmentEvaluation)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->show($treatmentEvaluation)
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function doctors(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->doctors()
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function doctorReviews(Employee $employee): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->doctorReviews($employee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function rate(RateEvaluationRequest $request, TreatmentEvaluation $treatmentEvaluation): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->rate($request, $treatmentEvaluation)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function dismes(TreatmentEvaluation $treatmentEvaluation)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentEvaluationService->dismes($treatmentEvaluation)
        );
    }
}
