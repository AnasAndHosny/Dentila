<?php

namespace App\Http\Requests\V1\Appointment;

use Carbon\Carbon;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class ShiftAppointmentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d H:i:s'],
            'to' => ['required', 'date_format:Y-m-d H:i:s', 'after:from'],
            'target_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:' . Carbon::now()->toDateString()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
