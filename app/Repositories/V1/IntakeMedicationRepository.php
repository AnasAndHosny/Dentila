<?php

namespace App\Repositories\V1;

use App\Models\IntakeMedication;

class IntakeMedicationRepository
{
    public function all()
    {
        return IntakeMedication::all();
    }

    public function create($request)
    {
        return IntakeMedication::create($request->validated());
    }

    public function update($request, IntakeMedication $intakeMedication)
    {
        $intakeMedication->update($request->validated());
        return $intakeMedication;
    }

    public function delete(IntakeMedication $intakeMedication)
    {
        return $intakeMedication->delete();
    }
}
