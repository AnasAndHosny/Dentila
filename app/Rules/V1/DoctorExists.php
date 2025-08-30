<?php

namespace App\Rules\V1;

use Closure;
use App\Models\Employee;
use Illuminate\Contracts\Validation\ValidationRule;

use function PHPUnit\Framework\returnSelf;

class DoctorExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employee = Employee::find($value);
        if ($employee && $employee->user->hasRole('doctor')) return;

        $fail('validation.exists')->translate();
    }
}
