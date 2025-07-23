<?php

namespace App\Rules\V1;

use App\Models\PatientTreatmentSubstep;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SubStepBelongsToTreatment implements ValidationRule
{
    protected $patientTreatment;

    public function __construct($patientTreatment)
    {
        $this->patientTreatment = $patientTreatment;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == -1) {
            return; // new step
        }

        $subStep = PatientTreatmentSubstep::find($value);
        if(!$subStep || $subStep->patientTreatmentStep->patientTreatment->id != $this->patientTreatment->id) {
            $fail('validation.exists')->translate();
        }
    }
}
