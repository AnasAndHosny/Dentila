<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->name,
            ],
            'doctor' => [
                'id' => $this->employee->id,
                'name' => $this->employee->user->name,
            ],
            'status_id' => (int)$this->appointment_status_id,
            'status' => $this->appointmentStatus->name,
            'date' => $this->date,
            'start_time' => $this->startTimeOnly,
            'end_time' => $this->endTimeOnly,
            'duration' => $this->durationInMinutes,
            $this->mergeWhen($request->user() && $request->user()->hasAnyRole(['patient']), [
                'can_delete' => $this->canDelete(),
            ]),
        ];
    }
}
