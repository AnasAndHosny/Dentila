<?php

namespace App\Repositories\V1;

use App\Models\DoctorWorkingHour;
use App\Models\Employee;

class DoctorWorkingHourRepository
{
    public function all(Employee $doctor)
    {
        return $doctor->workingHours()->orderedByDay()->get();
    }

    public function find($id)
    {
        return DoctorWorkingHour::with('doctor')->findOrFail($id);
    }

    public function create($data)
    {
        return DoctorWorkingHour::create($data);
    }

    public function update($data, DoctorWorkingHour $workingHour)
    {
        $workingHour->update($data);
        return $workingHour;
    }

    public function delete(DoctorWorkingHour $workingHour)
    {
        return $workingHour->delete();
    }
}
