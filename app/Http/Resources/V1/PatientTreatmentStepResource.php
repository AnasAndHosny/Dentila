<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientTreatmentStepResource extends JsonResource
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
            'finished' => (int)$this->finished,
            'note' => $this->note,
            'treatment_substeps' => PatientTreatmentSubstepResource::collection($this->substeps),
        ];
    }
}
