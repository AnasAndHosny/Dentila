<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;
use App\Models\PatientMedicationPlan;

class PatientMedicationPlanPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PatientMedicationPlan $patientMedicationPlan): Response
    {
        $medicationUser = $patientMedicationPlan->patient->user;

        if ($user->can('patientMedication.show')) return Response::allow();
        if ($user->can('patientMedication.show.my')) {
            if (($patientMedicationPlan->until_date >= now()) && ($user->id == $medicationUser->id))
                return Response::allow();
            else ExceptionHelper::throwModelNotFound($patientMedicationPlan);
        }

        return Response::deny();
    }
}
