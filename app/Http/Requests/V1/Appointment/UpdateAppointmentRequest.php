<?php

namespace App\Http\Requests\V1\Appointment;

use App\Helpers\ApiResponse;
use App\Models\AppointmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateAppointmentRequest extends FormRequest
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
        $appointmentStatuses = '';
        foreach (AppointmentStatus::all()->pluck('name') as $appointmentStatus) {
            $appointmentStatuses .= $appointmentStatus . ',';
        }

        return [
            'appointment_status' => ['string', 'in:' . $appointmentStatuses],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
