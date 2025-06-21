<?php

namespace App\Services\V1;

use App\Http\Resources\V1\MedicationPlanResource;
use App\Models\MedicationPlan;
use App\Repositories\V1\MedicationPlanRepository;

class MedicationPlanService
{
    protected $medPlanRepo;

    public function __construct(MedicationPlanRepository $medPlanRepo)
    {
        $this->medPlanRepo = $medPlanRepo;
    }

    public function index(): array
    {
        $medicationPlans = $this->medPlanRepo->all();
        $medicationPlans = MedicationPlanResource::collection($medicationPlans);
        $message = __('messages.index_success', ['class' => __('medication plans')]);
        $code = 200;
        return ['data' => $medicationPlans, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $medicationPlan = $this->medPlanRepo->create($request);
        $medicationPlan = new MedicationPlanResource($medicationPlan);

        $message = __('messages.store_success', ['class' => __('medication plan')]);
        $code = 201;
        return ['data' =>  $medicationPlan, 'message' => $message, 'code' => $code];
    }

    public function show(MedicationPlan $medicationPlan): array
    {
        $medicationPlan = new MedicationPlanResource($medicationPlan);

        $message = __('messages.show_success', ['class' => __('medication plan')]);
        $code = 200;
        return ['data' => $medicationPlan, 'message' => $message, 'code' => $code];
    }

    public function update($request, MedicationPlan $medicationPlan): array
    {
        $medicationPlan = $this->medPlanRepo->update($request, $medicationPlan);
        $medicationPlan = new MedicationPlanResource($medicationPlan);

        $message = __('messages.update_success', ['class' => __('medication plan')]);
        $code = 200;
        return ['data' => $medicationPlan, 'message' => $message, 'code' => $code];
    }

    public function destroy(MedicationPlan $medicationPlan): array
    {
        $medicationPlan = $this->medPlanRepo->delete($medicationPlan);

        $message = __('messages.destroy_success', ['class' => __('medication plan')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
