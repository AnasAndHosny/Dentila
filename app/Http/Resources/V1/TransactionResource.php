<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => (int)$this->amount,
            'type' => $this->type,
            'note' => (string)$this->note,
            'method' => $this->method,
            'created_by' => $this->creator->user->name ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
