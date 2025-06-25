<?php

namespace App\Repositories\V1;

use App\Models\Disease;

class DiseaseRepository
{
    public function all()
    {
        return Disease::all();
    }

    public function create($request)
    {
        return Disease::create($request->validated());
    }

    public function update($request, Disease $disease)
    {
        $disease->update($request->validated());
        return $disease;
    }

    public function delete(Disease $disease)
    {
        return $disease->delete();
    }
}
