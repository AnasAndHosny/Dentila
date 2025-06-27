<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use App\Services\V1\ToothService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Tooth\UpdateToothRequest;

class ToothController extends Controller
{
    use HandlesServiceResponse;

    private ToothService $toothService;

    public function __construct(ToothService $toothService)
    {
        $this->toothService = $toothService;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToothRequest $request, Patient $patient, int $tooth): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->toothService->update($request, $patient, $tooth)
        );
    }
}
