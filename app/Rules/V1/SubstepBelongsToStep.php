<?php

namespace App\Rules\V1;

use App\Models\PatientTreatment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SubstepBelongsToStep implements ValidationRule
{
    public function __construct(protected PatientTreatment $patientTreatment, protected int $stepId) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == -1) {
            return; // new step
        }

        $step = $this->patientTreatment->steps()->find($this->stepId);

        if (!$step || !$step->substeps->pluck('id')->contains($value)) {
            $fail('validation.exists')->translate();
        }
    }
}
