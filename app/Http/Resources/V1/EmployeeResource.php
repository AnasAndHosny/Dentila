<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'image' => $this->image,
            'name' => $this->user->name,
            'phone_number' => $this->user->phone_number,
            'birthdate' => $this->birthdate,
            'age' => (int)$this->age,
            'gender' => $this->gender,
            'ssn' => (string)$this->ssn,
            'address' => (string)$this->address,
            'roles' => $this->user->roles->pluck('name'),
            'is_banned' => $this->user->isBanned(),
            'ban_expired_at' => $this->user->bans()->latest()->first()->expired_at ?? null,
        ];
    }
}
