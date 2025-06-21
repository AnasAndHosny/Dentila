<?php

namespace App\Repositories\V1;

use App\Models\TreatmentNote;

class TreatmentNoteRepository
{
    public function all()
    {
        return TreatmentNote::latest()->get();
    }

    public function create($request)
    {
        return TreatmentNote::create([
            'title' => $request['title'],
            'text' => $request['text'],
            'duration_value' => $request['duration_value'],
            'duration_unit' => $request['duration_unit'],
        ]);
    }

    public function update($request, TreatmentNote $treatmentNote)
    {
        $treatmentNote->update([
            'title' => $request['title'] ?? $treatmentNote['title'],
            'text' => $request['text'] ?? $treatmentNote['text'],
            'duration_value' => $request['duration_value'] ?? $treatmentNote['duration_value'],
            'duration_unit' => $request['duration_unit'] ?? $treatmentNote['duration_unit'],
        ]);
        return $treatmentNote;
    }

    public function delete(TreatmentNote $treatmentNote)
    {
        return $treatmentNote->delete();
    }
}
