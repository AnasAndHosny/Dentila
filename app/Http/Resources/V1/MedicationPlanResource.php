<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\MedicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicationPlanResource extends JsonResource
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
            'duration_value' => (int)$this->duration_value,
            'duration_unit' => $this->duration_unit,
        ];
    }
}
