<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TreatmentStep;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\TreatmentStepService;
use App\Http\Requests\V1\TreatmentStep\StoreTreatmentStepRequest;
use App\Http\Requests\V1\TreatmentStep\UpdateTreatmentStepRequest;

class TreatmentStepController extends Controller
{
    use HandlesServiceResponse;

    private TreatmentStepService $treatmentStepService;

    public function __construct(TreatmentStepService $treatmentStepService)
    {
        $this->treatmentStepService = $treatmentStepService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreatmentStepRequest $request)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentStepService->store($request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentStepRequest $request, TreatmentStep $treatmentStep)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentStepService->update($request, $treatmentStep)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentStep $treatmentStep)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentStepService->destroy($treatmentStep)
        );
    }
}
