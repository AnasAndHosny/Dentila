<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class QueueTurnResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'patient'      => [
                'id'   => $this->patient?->id,
                'name' => $this->patient?->name,
            ],
            'doctor'       => [
                'id'   => $this->doctor?->id,
                'name' => $this->doctor?->name,
            ],
            'status'       => $this->status->name,
            'arrival_time' => $this->arrival_time,
            'appointment_time' => $this->appointment?->start_time,
        ];
    }
}

