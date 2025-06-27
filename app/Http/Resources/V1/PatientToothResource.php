<?php

namespace App\Http\Resources\V1;

use App\Models\ToothStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientToothResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'number' => (int)$this->number,
            'name' => (string)$this->name,
            'note' => (string)$this->pivot->note,
            'has_treatment' => (int)$this->pivot->has_treatment,
            'status_id' => (int)$this->pivot->tooth_status_id,
            'status_name' => (string)ToothStatus::find($this->pivot->tooth_status_id)?->name ?: 'سليم',
        ];
    }
}
