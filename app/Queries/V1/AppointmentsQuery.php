<?php

namespace App\Queries\V1;

use App\Filters\V1\BetweenDateFilter;
use App\Filters\V1\HasDueFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\V1\HasTreatmentFilter;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\V1\PatientSearchFilter;
use Carbon\Carbon;

class AppointmentsQuery extends QueryBuilder
{
    public function __construct($patients, $request)
    {
        parent::__construct($patients);

        $this
            ->allowedFilters([
                AllowedFilter::custom('date_range', new BetweenDateFilter),
                AllowedFilter::scope('status'),
            ]);
    }
}
