<?php

namespace App\Rules\V1;

use Closure;
use App\Models\PatientTreatment;
use Illuminate\Contracts\Validation\ValidationRule;

class StepBelongsToTreatment implements ValidationRule
{
    protected $patientTreatment;

    public function __construct($patientTreatment)
    {
        $this->patientTreatment = $patientTreatment;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == -1) {
            return; // new step
        }

        if (!$this->patientTreatment || !$this->patientTreatment->steps->pluck('id')->contains($value)) {
            $fail('validation.exists')->translate();
        }
    }
}
