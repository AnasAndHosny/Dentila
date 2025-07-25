<?php

namespace App\Queries\V1;

use App\Models\PatientTreatment;
use App\Filters\V1\ToothNumberFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\V1\TreatmentCaseFilter;

class PatientTreatmentsQuery extends QueryBuilder
{
    public function __construct($patientTreatment, $request)
    {
        parent::__construct($patientTreatment);

        $this
            ->allowedFilters([
                AllowedFilter::custom('case', new TreatmentCaseFilter),
                AllowedFilter::custom('tooth', new ToothNumberFilter),
            ])
            ->with(['patientTeeth.tooth']); // eager load for performance
    }
}
