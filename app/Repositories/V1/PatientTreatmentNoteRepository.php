<?php

namespace App\Repositories\V1;

use App\Models\Patient;
use App\Models\PatientTreatmentNote;
use App\Queries\V1\PatientTreatmentsNoteQuery;
use Carbon\Carbon;

class PatientTreatmentNoteRepository
{
    public function all(Patient $patient)
    {
        $patientTreatmentNotes =new PatientTreatmentsNoteQuery($patient->treatmentNotes());
        return $patientTreatmentNotes->latest();
    }

    public function create($request, Patient $patient)
    {
        $untilDate = Carbon::now()->add($request['duration_unit'], (int)$request['duration_value']);

        $treatmentNote = $patient->treatmentNotes()->create([
            'title' => $request['title'],
            'text' => $request['text'],
            'until_date' => $untilDate,
        ]);

        return $treatmentNote;
    }

    public function delete(PatientTreatmentNote $patientTreatmentNote)
    {
        return $patientTreatmentNote->delete();
    }
}
