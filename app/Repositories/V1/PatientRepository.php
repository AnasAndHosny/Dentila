<?php

namespace App\Repositories\V1;

use App\Models\Patient;
use App\Models\User;

class PatientRepository
{
    public function all()
    {
        return Patient::latest()->get();
    }

    public function create($request)
    {
        $user = User::query()
            ->where('phone_number', $request['phone_number'])
            ->first();

        if (is_null($user)) {
            $user = User::create(attributes: [
                'name' => $request['name'],
                'phone_number' => $request['phone_number'],
                'password' => $request['phone_number'],
            ]);
        }

        $patient = $user->patient()->create($request->validated());

        $patient->diseases()->attach($request['diseases_ids']);
        $patient->intakeMedications()->attach($request['intake_medications_ids']);


        return $patient;
    }

    public function update($request, Patient $patient)
    {
        $patient->update($request->validated());

        $user = $patient->user;
        $user->update([
            'name' => $request['name'] ?? $user['name'],
            'phone_number' => $request['phone_number'] ?? $user['phone_number'],
        ]);

        if ($request->has('diseases_ids')) {
            $patient->diseases()->whereNotIn('id', $request['diseases_ids'])->detach();
            $patient->diseases()->syncWithoutDetaching($request['diseases_ids']);
        }

        if ($request->has('intake_medications_ids')) {
            $patient->intakeMedications()->whereNotIn('id', $request['intake_medications_ids'])->detach();
            $patient->intakeMedications()->syncWithoutDetaching($request['intake_medications_ids']);
        }

        return $patient;
    }

    public function delete(Patient $patient)
    {
        return $patient->delete();
    }
}
