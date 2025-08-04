<?php

namespace App\Queries\V1;

use App\Filters\V1\HasDueFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\V1\HasTreatmentFilter;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\V1\PatientSearchFilter;

class PatientsQuery extends QueryBuilder
{
    public function __construct($patients, $request)
    {
        parent::__construct($patients);

        $this
            ->allowedFilters([
                AllowedFilter::custom('search', new PatientSearchFilter),
                AllowedFilter::custom('has_treatment', new HasTreatmentFilter),
                AllowedFilter::custom('has_due', new HasDueFilter),
            ]);
    }
}
