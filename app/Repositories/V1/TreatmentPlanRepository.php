<?php

namespace App\Repositories\V1;

use App\Models\TreatmentPlan;

class TreatmentPlanRepository
{
    public function all()
    {
        return TreatmentPlan::latest()->get();
    }

    public function create($request)
    {
        return TreatmentPlan::create($request->validated());
    }

    public function update($request, TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->update($request->validated());
        return $treatmentPlan;
    }

    public function delete(TreatmentPlan $treatmentPlan)
    {
        return $treatmentPlan->delete();
    }
}
