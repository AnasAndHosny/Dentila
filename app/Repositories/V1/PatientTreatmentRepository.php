<?php

namespace App\Repositories\V1;

use App\Models\Tooth;
use App\Models\Patient;
use Illuminate\Support\Arr;
use App\Models\PatientTooth;
use App\Models\TreatmentPlan;
use App\Models\PatientTreatment;
use App\Events\TreatmentCompleted;
use Illuminate\Support\Facades\DB;
use App\Models\PatientTreatmentStep;
use App\Models\PatientTreatmentSubstep;
use App\Queries\V1\PatientTreatmentsQuery;

class PatientTreatmentRepository
{
    public function all($request, Patient $patient)
    {
        $patients = new PatientTreatmentsQuery($patient->Treatments(), $request);
        return $patients->latest()->paginate();
    }

    public function create($request)
    {
        $patient = Patient::find($request['patient_id']);
        $treatmentPlan = TreatmentPlan::find($request['treatment_plan_id']);

        $patientTreatment = $treatmentPlan->toArray();
        $patientTreatment['main_complaint'] = $request['main_complaint'];
        $patientTreatment['diagnoses'] = $request['diagnoses'];

        $patientTreatment = $patient->Treatments()->create($patientTreatment);

        foreach ($request['teeth'] as $tooth) {
            $toothId = Tooth::where('number', $tooth)->first()->id;
            $patientTooth = PatientTooth::query()
                ->where('patient_id', $request['patient_id'])
                ->where('tooth_id', $toothId)
                ->first();

            if (!$patientTooth) {
                $patientTooth = PatientTooth::create([
                    'patient_id' => $request['patient_id'],
                    'tooth_id' => $toothId,
                ]);
            }

            $patientTreatment->patientTeeth()->attach($patientTooth);
        }

        foreach ($treatmentPlan->treatmentSteps as $treatmentPlanStep) {
            $treatmentStep = $patientTreatment->steps()->create($treatmentPlanStep->toArray());

            foreach ($treatmentPlanStep->treatmentSubsteps as $treatmentPlanSubstep) {
                $treatmentStep->substeps()->create($treatmentPlanSubstep->toArray());
            }
        }
        return $patientTreatment;
    }

    public function update($request, PatientTreatment $patientTreatment)
    {
        return DB::transaction(function () use ($request, $patientTreatment) {
            $patientTreatment->update([
                'name' => $request['name'],
                'cost' => $request['cost'],
                'main_complaint' => $request['main_complaint'],
                'diagnoses' => $request['diagnoses'],
            ]);

            $teeth = Arr::pluck($request['teeth'], 'number');
            $patientTreatment->patientTeeth()->whereNotIn('tooth_id', $teeth)->detach();

            foreach ($teeth as $tooth) {
                $toothId = Tooth::where('number', $tooth)->first()->id;
                $patientTooth = PatientTooth::query()
                    ->where('patient_id', $patientTreatment->patient_id)
                    ->where('tooth_id', $toothId)
                    ->first();

                if (!$patientTooth) {
                    $patientTooth = PatientTooth::create([
                        'patient_id' => $patientTreatment->patient_id,
                        'tooth_id' => $toothId,
                    ]);
                }

                $patientTreatment->patientTeeth()->syncWithoutDetaching($patientTooth);
            }

            $steps = Arr::pluck($request['steps'], 'id');
            $patientTreatment->steps()->whereNotIn('id', $steps)->delete();
            foreach ($request['steps'] as $step) {
                if ($step['id'] == -1) {
                    $treatmentStep = $patientTreatment->steps()->create([
                        'name' => $step['name'],
                        'queue' => $step['queue'],
                        'finished' => $step['finished'],
                        'note' => $step['note'],
                    ]);
                } else {
                    $treatmentStep = $patientTreatment->steps()
                        ->find($step['id']);

                    $treatmentStep->update([
                        'name' => $step['name'],
                        'queue' => $step['queue'],
                        'finished' => $step['finished'],
                        'note' => $step['note'],
                    ]);
                }

                $substeps = Arr::pluck($step['treatment_substeps'], 'id');
                $treatmentStep->substeps()->whereNotIn('id', $substeps)->delete();
                foreach ($step['treatment_substeps'] as $substep) {
                    if ($substep['id'] == -1) {
                        $treatmentStep->substeps()
                            ->create([
                                'name' => $substep['name'],
                                'queue' => $substep['queue'],
                                'finished' => $substep['finished'],
                                'note' => $substep['note'],
                            ]);
                    } else {
                        $treatmentStep->substeps()
                            ->find($substep['id'])
                            ->update([
                                'name' => $substep['name'],
                                'queue' => $substep['queue'],
                                'finished' => $substep['finished'],
                                'note' => $substep['note'],
                            ]);
                    }
                }
            }

            // calculate completed percentage
            $mandatoryStepsCount = $patientTreatment->steps()
                ->where(function ($query) {
                    $query->where('optional', false)
                        ->orWhere('finished', true);
                })
                ->count();

            $completedMandatoryStepsCount = $patientTreatment->steps()
                ->where('finished', true)
                ->count();

            $progress = 0;
            if ($mandatoryStepsCount > 0) {
                $progress = ($completedMandatoryStepsCount / $mandatoryStepsCount) * 100;
            }

            $finished = false;
            if ($progress == 100) {
                $finished = true;
                event(new TreatmentCompleted($patientTreatment));
            }

            $patientTreatment->update(attributes: [
                'complete_percentage' => round($progress, 2),
                'finished' => $finished,
            ]);


            return $patientTreatment->fresh();
        });
    }

    public function updateNote($request, PatientTreatment $patientTreatment)
    {
        if ($request['step_id']) {
            PatientTreatmentStep::find($request['step_id'])->update($request->validated());
        } else {
            PatientTreatmentSubstep::find($request['substep_id'])->update($request->validated());
        }

        return $patientTreatment;
    }

    public function updateCheck($request, PatientTreatment $patientTreatment)
    {
        return DB::transaction(function () use ($request, $patientTreatment) {
            if ($request->has('steps')) {
                foreach ($request['steps'] as $stepId) {
                    $step = PatientTreatmentStep::find($stepId);
                    $step->update(['finished' => !$step['finished']]);
                }
            }

            if ($request->has('substeps')) {
                foreach ($request['substeps'] as $substepId) {
                    $substep = PatientTreatmentSubstep::find($substepId);
                    $substep->update(['finished' => !$substep['finished']]);
                }
            }

            return $patientTreatment;
        });
    }

    public function delete(PatientTreatment $patientTreatment)
    {
        return $patientTreatment->delete();
    }
}
