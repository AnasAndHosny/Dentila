<?php

namespace App\Services\V1;

use App\Http\Resources\V1\TreatmentPlanResource;
use App\Models\TreatmentStep;
use App\Repositories\V1\TreatmentStepRepository;

class TreatmentStepService
{
    protected $treatmentStepRepository;

    public function __construct(TreatmentStepRepository $treatmentStepRepository)
    {
        $this->treatmentStepRepository = $treatmentStepRepository;
    }

    public function store($request): array
    {
        $treatmentStep = $this->treatmentStepRepository->create($request);
        $treatmentPlan = $this->treatmentStepRepository->getPlan($treatmentStep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.store_success', ['class' => __('treatment step')]);
        $code = 201;
        return ['data' =>  $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function update($request, TreatmentStep $treatmentStep): array
    {
        $treatmentStep = $this->treatmentStepRepository->update($request, $treatmentStep);
        $treatmentPlan = $this->treatmentStepRepository->getPlan($treatmentStep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.update_success', ['class' => __('treatment step')]);
        $code = 200;
        return ['data' => $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function destroy(TreatmentStep $treatmentStep): array
    {
        $this->treatmentStepRepository->delete($treatmentStep);
        $treatmentPlan = $this->treatmentStepRepository->getPlan($treatmentStep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.destroy_success', ['class' => __('treatment step')]);
        $code = 200;
        return ['data' => $treatmentPlan, 'message' => $message, 'code' => $code];
    }
}
