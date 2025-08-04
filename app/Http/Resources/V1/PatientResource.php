<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'birthdate' => $this->birthdate,
            'age' => (int)$this->age,
            'gender' => $this->gender,
            'balance' => (int)$this->account->balance,
            'job' => (string)$this->job,
            'marital_status' => (string)$this->marital_status,
            'address' => (string)$this->address,
            'social_history' => (string)$this->social_history,
            'note' => (string)$this->note,
            'intake_medications' => IntakeMedicationResource::collection($this->intakeMedications),
            'diseases' => DiseaseResource::collection($this->diseases),
            'teeth' => $this->when($request->user()->hasRole('doctor'), PatientToothResource::collection($this->teeth)),
            $this->mergeWhen($request->user()->hasAnyRole(['manager', 'receptionist']), [
                'is_banned' => $this->user->isBanned(),
                'ban_expired_at' => $this->user->bans()->latest()->first()->expired_at ?? null,
            ]),
        ];
    }
}
