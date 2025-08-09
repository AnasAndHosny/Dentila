<?php

namespace App\Repositories\V1;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\PatientMedicationPlan;
use App\Queries\V1\PatientMedicationPlansQuery;

class PatientMedicationPlanRepository
{
    public function all(Patient $patient)
    {
        $patientMedicationPlans =new PatientMedicationPlansQuery($patient->medicationPlans());
        return $patientMedicationPlans->latest();
    }

    public function create($request, Patient $patient)
    {
        $untilDate = Carbon::now()->add($request['duration_unit'], (int)$request['duration_value']);

        $patientMedicationPlan = $patient->medicationPlans()->create([
            'medication_id' => $request['medication_id'],
            'dose' => $request['dose'],
            'until_date' => $untilDate,
        ]);

        return $patientMedicationPlan;
    }

    public function delete(PatientMedicationPlan $patientMedicationPlan)
    {
        return $patientMedicationPlan->delete();
    }
}
