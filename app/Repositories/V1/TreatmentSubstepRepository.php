<?php

namespace App\Repositories\V1;

use App\Models\TreatmentSubstep;

class TreatmentSubstepRepository
{
    public function create($request)
    {
        return TreatmentSubstep::create($request->validated());
    }

    public function update($request, TreatmentSubstep $treatmentSubstep)
    {
        $treatmentSubstep->update($request->validated());
        return $treatmentSubstep;
    }

    public function delete(TreatmentSubstep $treatmentSubstep)
    {
        return $treatmentSubstep->delete();
    }

    public function getPlan(TreatmentSubstep $treatmentSubstep)
    {
        return $treatmentSubstep->treatmentStep->treatmentPlan;
    }
}
