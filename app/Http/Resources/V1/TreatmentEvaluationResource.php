<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentEvaluationResource extends JsonResource
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
            'doctor' => [
                'id' => (int)$this->doctor->id,
                'name' => (string)$this->doctor->user->name,
            ],
            'patient' => [
                'id' => (int)$this->patient->id,
                'name' => (string)$this->patient->name,
            ],
            'treatment' => [
                'id' => (int)$this->treatment->id,
                'name' => (string)$this->treatment->name,
                'finished_at' => $this->treatment->updated_at->format('Y-m-d'),
            ],
            $this->mergeWhen($this->rating, [
                'rating' => (int)$this->rating,
                'comment' => $this->comment,
            ]),
        ];
    }
}
