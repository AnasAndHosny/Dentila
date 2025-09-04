<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use App\Models\DoctorWorkingHour;
use Illuminate\Auth\Access\Response;

class DoctorWorkingHourPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DoctorWorkingHour $doctorWorkingHour): Response
    {
        $workingHourUser = $doctorWorkingHour->doctor->user;

        if ($user->can('working-hours.update')) return Response::allow();

        if ($user->can('working-hours.update.my')) {
            if ($user->id == $workingHourUser->id) return Response::allow();
            else ExceptionHelper::throwModelNotFound($doctorWorkingHour);
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DoctorWorkingHour $doctorWorkingHour): Response
    {
        $workingHourUser = $doctorWorkingHour->doctor->user;

        if ($user->can('working-hours.destroy')) return Response::allow();

        if ($user->can('working-hours.destroy.my')) {
            if ($user->id == $workingHourUser->id) return Response::allow();
            else ExceptionHelper::throwModelNotFound($doctorWorkingHour);
        }

        return Response::deny();
    }
}
