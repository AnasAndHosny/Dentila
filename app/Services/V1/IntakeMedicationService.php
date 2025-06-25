<?php

namespace App\Services\V1;

use App\Http\Resources\V1\IntakeMedicationResource;
use App\Models\IntakeMedication;
use App\Repositories\V1\IntakeMedicationRepository;

class IntakeMedicationService
{
    protected $diseaseRepository;

    public function __construct(IntakeMedicationRepository $diseaseRepository)
    {
        $this->diseaseRepository = $diseaseRepository;
    }

    public function index(): array
    {
        $intakeMedications = $this->diseaseRepository->all();
        $intakeMedications = IntakeMedicationResource::collection($intakeMedications);
        $message = __('messages.index_success', ['class' => __('intake medications')]);
        $code = 200;
        return ['data' => $intakeMedications, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $this->diseaseRepository->create($request);

        $intakeMedications = $this->diseaseRepository->all();
        $intakeMedications = IntakeMedicationResource::collection($intakeMedications);

        $message = __('messages.store_success', ['class' => __('intake medication')]);
        $code = 201;
        return ['data' =>  $intakeMedications, 'message' => $message, 'code' => $code];
    }

    public function update($request, IntakeMedication $intakeMedication): array
    {
        $this->diseaseRepository->update($request, $intakeMedication);

        $intakeMedications = $this->diseaseRepository->all();
        $intakeMedications = IntakeMedicationResource::collection($intakeMedications);

        $message = __('messages.update_success', ['class' => __('intake medication')]);
        $code = 200;
        return ['data' => $intakeMedications, 'message' => $message, 'code' => $code];
    }

    public function destroy(IntakeMedication $intakeMedication): array
    {
        $this->diseaseRepository->delete($intakeMedication);

        $intakeMedications = $this->diseaseRepository->all();
        $intakeMedications = IntakeMedicationResource::collection($intakeMedications);

        $message = __('messages.destroy_success', ['class' => __('intake medication')]);
        $code = 200;
        return ['data' => $intakeMedications, 'message' => $message, 'code' => $code];
    }
}
