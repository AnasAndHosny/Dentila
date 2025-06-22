<?php

namespace App\Services\V1;

use App\Http\Resources\V1\TreatmentPlanResource;
use App\Models\TreatmentPlan;
use App\Repositories\V1\TreatmentPlanRepository;

class TreatmentPlanService
{
    protected $treatmentPlanRepo;

    public function __construct(TreatmentPlanRepository $treatmentPlanRepo)
    {
        $this->treatmentPlanRepo = $treatmentPlanRepo;
    }

    public function index(): array
    {
        $treatmentPlans = $this->treatmentPlanRepo->all();
        $treatmentPlans = TreatmentPlanResource::collection($treatmentPlans);
        $message = __('messages.index_success', ['class' => __('treatment plans')]);
        $code = 200;
        return ['data' => $treatmentPlans, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $treatmentPlan = $this->treatmentPlanRepo->create($request);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.store_success', ['class' => __('treatment plan')]);
        $code = 201;
        return ['data' =>  $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function show(TreatmentPlan $treatmentPlan): array
    {
        $treatmentNote = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.show_success', ['class' => __('treatment plan')]);
        $code = 200;
        return ['data' => $treatmentNote, 'message' => $message, 'code' => $code];
    }

    public function update($request, TreatmentPlan $treatmentPlan): array
    {
        $treatmentPlan = $this->treatmentPlanRepo->update($request, $treatmentPlan);
        $treatmentPlan = new TreatmentPlanResource($treatmentPlan);

        $message = __('messages.update_success', ['class' => __('treatment plan')]);
        $code = 200;
        return ['data' => $treatmentPlan, 'message' => $message, 'code' => $code];
    }

    public function destroy(TreatmentPlan $treatmentPlan): array
    {
        $treatmentPlan = $this->treatmentPlanRepo->delete($treatmentPlan);

        $message = __('messages.destroy_success', ['class' => __('treatment plan')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
