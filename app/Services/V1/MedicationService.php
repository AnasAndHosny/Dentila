<?php

namespace App\Services\V1;

use App\Http\Resources\V1\MedicationResource;
use App\Models\Medication;
use App\Repositories\V1\MedicationRepository;

class MedicationService
{
    protected $medicationRepo;

    public function __construct(MedicationRepository $medicationRepo)
    {
        $this->medicationRepo = $medicationRepo;
    }

    public function index(): array
    {
        $medications = $this->medicationRepo->all();
        $medications = MedicationResource::collection($medications);
        $message = __('messages.index_success', ['class' => __('medications')]);
        $code = 200;
        return ['data' => $medications, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $medication = $this->medicationRepo->create($request);
        $medication = new MedicationResource($medication);

        $message = __('messages.store_success', ['class' => __('medication')]);
        $code = 201;
        return ['data' =>  $medication, 'message' => $message, 'code' => $code];
    }

    public function show(Medication $medication): array
    {
        $medication = new MedicationResource($medication);

        $message = __('messages.show_success', ['class' => __('medication')]);
        $code = 200;
        return ['data' => $medication, 'message' => $message, 'code' => $code];
    }

    public function update($request, Medication $medication): array
    {
        $medication = $this->medicationRepo->update($request, $medication);
        $medication = new MedicationResource($medication);

        $message = __('messages.update_success', ['class' => __('medication')]);
        $code = 200;
        return ['data' => $medication, 'message' => $message, 'code' => $code];
    }

    public function destroy(Medication $medication): array
    {
        $medication = $this->medicationRepo->delete($medication);

        $message = __('messages.destroy_success', ['class' => __('medication')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
