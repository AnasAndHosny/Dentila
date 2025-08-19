<?php

namespace App\Services\V1;

use App\Http\Resources\V1\TreatmentEvaluationResource;
use App\Repositories\V1\TreatmentEvaluationRepository;
use App\Http\Requests\V1\TreatmentEvaluation\RateEvaluationRequest;
use App\Http\Resources\V1\BasePaginatedCollection;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Models\TreatmentEvaluation;

class TreatmentEvaluationService
{
    protected $treatmentEvaluationRepo;

    public function __construct(TreatmentEvaluationRepository $treatmentEvaluationRepo)
    {
        $this->treatmentEvaluationRepo = $treatmentEvaluationRepo;
    }

    public function myEvaluations(): array
    {
        $treatmentEvaluations = $this->treatmentEvaluationRepo->myEvaluations();
        $treatmentEvaluations = TreatmentEvaluationResource::collection($treatmentEvaluations);

        $message = __('messages.index_success', ['class' => __('treatment evaluations')]);
        $code = 200;
        return ['data' => $treatmentEvaluations, 'message' => $message, 'code' => $code];
    }

    public function show(TreatmentEvaluation $treatmentEvaluation): array
    {
        $treatmentEvaluation = new TreatmentEvaluationResource($treatmentEvaluation);

        $message = __('messages.show_success', ['class' => __('treatment evaluation')]);
        $code = 200;
        return ['data' => $treatmentEvaluation, 'message' => $message, 'code' => $code];
    }

    public function doctors(): array
    {
        $doctors = $this->treatmentEvaluationRepo->doctors();
        $doctors = EmployeeResource::collection($doctors);

        $message = __('messages.index_success', ['class' => __('doctors')]);
        $code = 200;
        return ['data' => $doctors, 'message' => $message, 'code' => $code];
    }

    public function doctorReviews(Employee $employee): array
    {
        $doctorReviews = $this->treatmentEvaluationRepo->doctorReviews($employee);
        $doctorReviews = new BasePaginatedCollection($doctorReviews, TreatmentEvaluationResource::class);

        $message = __('messages.index_success', ['class' => __('doctor reviews')]);
        $code = 200;
        return ['data' => $doctorReviews, 'message' => $message, 'code' => $code];
    }

    public function rate(RateEvaluationRequest $request, TreatmentEvaluation $treatmentEvaluation): array
    {
        $treatmentEvaluation = $this->treatmentEvaluationRepo->rate($request, $treatmentEvaluation);
        $treatmentEvaluation = new TreatmentEvaluationResource ($treatmentEvaluation);

        $message = __('messages.update_success', ['class' => __('treatment evaluation')]);
        $code = 200;
        return ['data' => $treatmentEvaluation, 'message' => $message, 'code' => $code];
    }

    public function dismes(TreatmentEvaluation $treatmentEvaluation): array
    {
        $treatmentEvaluation = $this->treatmentEvaluationRepo->dismes($treatmentEvaluation);

        $message = __('messages.destroy_success', ['class' => __('treatment evaluation')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
