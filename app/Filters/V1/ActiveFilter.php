<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Str;

class ActiveFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value == true) {
            $query->active();
        } elseif ($value == false) {
            $query->expired();
        }
    }
}
