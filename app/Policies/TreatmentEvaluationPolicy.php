<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use App\Models\TreatmentEvaluation;
use Illuminate\Auth\Access\Response;

class TreatmentEvaluationPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TreatmentEvaluation $treatmentEvaluation): Response
    {
        $evaluationUser = $treatmentEvaluation->patient->user;

        if ($user->can('treatmentEvaluation.show')) return Response::allow();

        if ($user->can('treatmentEvaluation.show.my')) {
            if ($user->id == $evaluationUser->id  && $treatmentEvaluation->rating == null) return Response::allow();
            else ExceptionHelper::throwModelNotFound($treatmentEvaluation);
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function rate(User $user, TreatmentEvaluation $treatmentEvaluation): Response
    {
        $evaluationUser = $treatmentEvaluation->patient->user;

        if ($user->can('treatmentEvaluation.rate.my')) {
            if (($user->id == $evaluationUser->id) && ($treatmentEvaluation->rating == null)) return Response::allow();
            else ExceptionHelper::throwModelNotFound($treatmentEvaluation);
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function dismes(User $user, TreatmentEvaluation $treatmentEvaluation): Response
    {
        $evaluationUser = $treatmentEvaluation->patient->user;

        if ($user->can('treatmentEvaluation.dismes.my')) {
            if ($user->id == $evaluationUser->id  && $treatmentEvaluation->rating == null) return Response::allow();
            else ExceptionHelper::throwModelNotFound($treatmentEvaluation);
        }

        return Response::deny();
    }
}
