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
            'number' => (int)$this->tooth->number,
            'name' => (string)$this->tooth->name,
            'note' => (string)$this->note,
            'has_treatment' => (bool)$this->has_treatment,
            'status_id' => (int)$this->tooth_status_id,
            'status_name' => (string)ToothStatus::find($this->tooth_status_id)?->name ?: 'سليم',
        ];
    }
}
