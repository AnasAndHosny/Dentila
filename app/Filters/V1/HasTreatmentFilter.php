<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class HasTreatmentFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($value) {
            // المرضى الذين لديهم أسنان بعلاج غير مكتمل
            $query->whereHas('teeth.patientTreatmentTooth.patientTreatment', function ($q) {
                $q->where('finished', false);
            });
        } else {
            // المرضى الذين لا يملكون أي أسنان بعلاج غير مكتمل، أو لا يملكون أسنان أصلاً
            $query->where(function ($q) {
                $q->whereDoesntHave('teeth.patientTreatmentTooth.patientTreatment', function ($subQ) {
                    $subQ->where('finished', false);
                });
            });
        }
    }
}
