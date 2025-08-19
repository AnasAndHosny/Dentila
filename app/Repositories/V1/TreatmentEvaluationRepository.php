<?php

namespace App\Repositories\V1;

use App\Models\Disease;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TreatmentEvaluation;
use App\Http\Requests\V1\TreatmentEvaluation\RateEvaluationRequest;
use App\Models\User;

class TreatmentEvaluationRepository
{
    public function myEvaluations()
    {
        return TreatmentEvaluation::where('patient_id', auth()->user()->patient->id)
            ->pending()
            ->latest()
            ->get();
    }

    public function rate(RateEvaluationRequest $request, TreatmentEvaluation $evaluation)
    {
        $evaluation->update([
            'rating'     => $request['rating'],
            'comment'    => $request['comment'],
        ]);

        return $evaluation;
    }

    public function dismes(TreatmentEvaluation $evaluation)
    {
        return $evaluation->delete();
    }

    public function doctors()
    {
        return Employee::query()
            ->whereHas('user', function ($q) {
                $q->role('doctor'); // من Spatie
            })
            ->withAvg(['treatmentEvaluations as avg_rating' => function ($q) {
                $q->completed();
            }], 'rating')
            ->orderByDesc('avg_rating')
            ->get();
    }

    public function doctorReviews(Employee $doctor)
    {
        return $doctor->treatmentEvaluations()
            ->completed()
            ->latest('updated_at')
            ->paginate();
    }
}
