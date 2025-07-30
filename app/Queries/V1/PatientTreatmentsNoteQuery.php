<?php

namespace App\Queries\V1;

use App\Filters\V1\ActiveFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PatientTreatmentsNoteQuery extends QueryBuilder
{
    public function __construct($patientTreatmentNotes)
    {
        parent::__construct($patientTreatmentNotes);

        $this
            ->allowedFilters([
                AllowedFilter::custom('active', new ActiveFilter),
            ]);
    }
}
