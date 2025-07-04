<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentPlanResource extends JsonResource
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
            'tooth_status' => new CategoryResource($this->toothStatus),
            'steps' => TreatmentStepResource::collection($this->treatmentSteps),
        ];
    }
}
