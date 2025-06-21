<?php

namespace App\Repositories\V1;

use App\Models\Medication;
use App\Helpers\ImageHelper;

class MedicationRepository
{
    public function all()
    {
        return Medication::latest()->get();
    }

    public function create($request)
    {
        $image = ImageHelper::store($request);
        return Medication::create([
            'image' => $image,
            'name' => $request['name'],
            'info' => $request['info'],
        ]);
    }

    public function update($request, Medication $medication)
    {
        $image = ImageHelper::update($request, $medication);
        $medication->update([
            'image' => $image,
            'name' => $request['name'] ?? $medication['name'],
            'info' => $request['info'] ?? $medication['info'],
        ]);
        return $medication;
    }

    public function delete(Medication $medication)
    {
        return $medication->delete();
    }

    public function getPlans(Medication $medication)
    {
        return $medication->medicationPlans()->latest()->get();
    }
}
