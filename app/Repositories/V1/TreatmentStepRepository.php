<?php

namespace App\Repositories\V1;

use App\Models\TreatmentStep;

class TreatmentStepRepository
{
    public function create($request)
    {
        return TreatmentStep::create($request->validated());
    }

    public function update($request, TreatmentStep $treatmentStep)
    {
        $treatmentStep->update($request->validated());
        return $treatmentStep;
    }

    public function delete(TreatmentStep $treatmentStep)
    {
        return $treatmentStep->delete();
    }

    public function getPlan(TreatmentStep $treatmentStep)
    {
        return $treatmentStep->treatmentPlan;
    }
}
