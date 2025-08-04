<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => (int)$this->balance,
            'transactions' => new BasePaginatedCollection($this->transactions()->latest()->paginate(), TransactionResource::class),
        ];
    }
}
