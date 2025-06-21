<?php

namespace App\Repositories\V1;

use App\Models\MedicationPlan;

class MedicationPlanRepository
{
    public function all()
    {
        return MedicationPlan::latest()->get();
    }

    public function create($request)
    {
        return MedicationPlan::create([
            'medication_id' => $request['medication_id'],
            'dose' => $request['dose'],
            'duration_value' => $request['duration_value'],
            'duration_unit' => $request['duration_unit'],
        ]);
    }

    public function update($request, MedicationPlan $medicationPlan)
    {
        $medicationPlan->update([
            'medication_id' => $request['medication_id'] ?? $medicationPlan['medication_id'],
            'dose' => $request['dose'] ?? $medicationPlan['dose'],
            'duration_value' => $request['duration_value'] ?? $medicationPlan['duration_value'],
            'duration_unit' => $request['duration_unit'] ?? $medicationPlan['duration_unit'],
        ]);
        return $medicationPlan;
    }

    public function delete(MedicationPlan $medicationPlan)
    {
        return $medicationPlan->delete();
    }
}
