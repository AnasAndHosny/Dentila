<?php

namespace App\Services\V1;

use App\Http\Resources\V1\PatientToothResource;
use App\Models\Patient;
use App\Repositories\V1\ToothRepository;

class ToothService
{
    protected $toothRepo;

    public function __construct(ToothRepository $toothRepo)
    {
        $this->toothRepo = $toothRepo;
    }

    public function update($request, Patient $patient, int $tooth): array
    {
        $tooth = $this->toothRepo->update($request, $patient, $tooth);
        $tooth = new PatientToothResource($tooth);

        $message = __('messages.update_success', ['class' => __('tooth')]);
        $code = 200;
        return ['data' => $tooth, 'message' => $message, 'code' => $code];
    }
}
