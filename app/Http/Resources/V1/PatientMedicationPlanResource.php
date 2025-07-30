<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientMedicationPlanResource extends JsonResource
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
            'medication' => new MedicationResource($this->medication),
            'dose' => $this->dose,
            'starts_at' => $this->starts_at,
            'until_date' => $this->until_date,
        ];
    }
}
