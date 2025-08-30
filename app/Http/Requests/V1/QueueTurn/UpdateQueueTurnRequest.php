<?php

namespace App\Http\Requests\V1\QueueTurn;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\QueueTurnStatus;


class UpdateQueueTurnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    $allowed = QueueTurnStatus::query()->pluck('name')->toArray();

    return [
        'queue_turn_status' => ['required','string','in:'.implode(',', $allowed)],
    ];
}
}
