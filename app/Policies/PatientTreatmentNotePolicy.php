<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use App\Models\PatientTreatmentNote;
use Illuminate\Auth\Access\Response;

class PatientTreatmentNotePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PatientTreatmentNote $patientNote): Response
    {
        $noteUser = $patientNote->patient->user;

        if ($user->can('patientNote.show')) return Response::allow();
        if ($user->can('patientNote.show.my')) {
            if (($patientNote->until_date >= now()) && ($user->id == $noteUser->id))
                return Response::allow();
            else ExceptionHelper::throwModelNotFound($patientNote);
        }

        return Response::deny();
    }
}
