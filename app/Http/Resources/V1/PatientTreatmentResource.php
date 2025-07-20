<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientTreatmentResource extends JsonResource
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
            'category' => new CategoryResource($this->category),
            'cost' => (int)$this->cost,
            'main_complaint' => (string)$this->main_complaint,
            'diagnoses' => (string)$this->diagnoses,
            'finished' => (int)$this->finished,
            'complete_percentage' => (int)$this->complete_percentage,
            'teeth' => PatientTreatmentToothResource::collection($this->patientTeeth),
            'steps' => PatientTreatmentStepResource::collection($this->steps),
        ];
    }
}
