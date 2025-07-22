<?php

namespace App\Rules\V1;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

use function PHPUnit\Framework\returnSelf;

class UniqueEmployeePhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneExist = User::where('phone_number', $value)->whereHas('employee')->exists();
        if ($phoneExist) $fail('validation.unique')->translate();
    }
}
