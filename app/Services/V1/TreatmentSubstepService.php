<?php

namespace App\Services\V1;

use App\Http\Resources\V1\TreatmentPlanResource;
use App\Models\TreatmentSubstep;
use App\Repositories\V1\TreatmentSubstepRepository;

class TreatmentSubstepService
{
    protected $treatmentSubstepRepository;

    public function __construct(TreatmentSubstepRepository $treatmentSubstepRepository)
    {
        $this->treatmentSubstepRepository = $treatmentSubstepRepository;
    }

    public function store($request): array
    {
        $treatmentSubstep = $this->treatmentSubstepRepository->create($request);
        $treatmentPlan = $this->treatmentSubstepRepository->getPlan($treatmentSubstep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.store_success', ['class' => __('treatment step')]);
        $code = 201;
        return ['data' =>  $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function update($request, TreatmentSubstep $treatmentSubstep): array
    {
        $treatmentSubstep = $this->treatmentSubstepRepository->update($request, $treatmentSubstep);
        $treatmentPlan = $this->treatmentSubstepRepository->getPlan($treatmentSubstep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.update_success', ['class' => __('treatment step')]);
        $code = 200;
        return ['data' => $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function destroy(TreatmentSubstep $treatmentSubstep): array
    {
        $this->treatmentSubstepRepository->delete($treatmentSubstep);
        $treatmentPlan = $this->treatmentSubstepRepository->getPlan($treatmentSubstep);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.destroy_success', ['class' => __('treatment step')]);
        $code = 200;
        return ['data' => $treatmentPlan, 'message' => $message, 'code' => $code];
    }
}
