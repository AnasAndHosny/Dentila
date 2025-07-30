<?php

namespace App\Queries\V1;

use App\Filters\V1\ActiveFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PatientMedicationPlansQuery extends QueryBuilder
{
    public function __construct($patientMedicationPlans)
    {
        parent::__construct($patientMedicationPlans);

        $this
            ->allowedFilters([
                AllowedFilter::custom('active', new ActiveFilter),
            ]);
    }
}
