<?php

namespace App\Repositories\V1;

use App\Models\Tooth;
use App\Models\Patient;

class ToothRepository
{
    public function update($request, Patient $patient, int $tooth)
    {
        $toothId = Tooth::where('number', $tooth)->first()->id;
        $patient->originalTeeth()->syncWithoutDetaching($toothId);
        $patient->originalTeeth()->updateExistingPivot($toothId,[
            'note' => $request['note']
        ]);
        $tooth = $patient->teeth()->where('tooth_id', $toothId)->first();
        return $tooth;
    }
}
