<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TreatmentSubstep;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\TreatmentSubstepService;
use App\Http\Requests\V1\TreatmentSubstep\StoreTreatmentSubstepRequest;
use App\Http\Requests\V1\TreatmentSubstep\UpdateTreatmentSubstepRequest;

class TreatmentSubstepController extends Controller
{
    use HandlesServiceResponse;

    private $treatmentSubstepService;

    public function __construct(TreatmentSubstepService $treatmentSubstepService)
    {
        $this->treatmentSubstepService = $treatmentSubstepService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreatmentSubstepRequest $request)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentSubstepService->store($request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentSubstepRequest $request, TreatmentSubstep $treatmentSubstep)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentSubstepService->update($request, $treatmentSubstep)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentSubstep $treatmentSubstep)
    {
        return $this->handleService(
            fn() =>
            $this->treatmentSubstepService->destroy($treatmentSubstep)
        );
    }
}
