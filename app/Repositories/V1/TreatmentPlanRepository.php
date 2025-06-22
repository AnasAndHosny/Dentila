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
        return TreatmentPlan::create([
            'name' => $request['name'],
            'category_id' => $request['category_id'],
            'cost' => $request['cost'],
            'tooth_status_id' => $request['tooth_status_id'],
        ]);
    }

    public function update($request, TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->update([
            'name' => $request['name'] ?? $treatmentPlan['name'],
            'category_id' => $request['category_id'] ?? $treatmentPlan['category_id'],
            'cost' => $request['cost'] ?? $treatmentPlan['cost'],
            'tooth_status_id' => $request['tooth_status_id'] ?? $treatmentPlan['tooth_status_id'],
        ]);
        return $treatmentPlan;
    }

    public function delete(TreatmentPlan $treatmentPlan)
    {
        return $treatmentPlan->delete();
    }
}
