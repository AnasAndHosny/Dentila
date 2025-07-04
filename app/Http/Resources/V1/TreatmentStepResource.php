<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentStepResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'name' => $this->name,
            'queue' => (int)$this->queue,
            'optional' => (int)$this->optional,
            'treatment_note' => new TreatmentNoteResource($this->treatmentNote),
            'medication_plan' => new MedicationPlanResource($this->medicationPlan),
            'treatment_substeps' => SubstepResource::collection($this->treatmentSubsteps),
        ];
    }
}
