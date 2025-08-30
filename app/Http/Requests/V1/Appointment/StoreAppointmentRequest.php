<?php

namespace App\Http\Requests\V1\Appointment;

use Carbon\Carbon;
use App\Helpers\ApiResponse;
use App\Rules\V1\DoctorExists;
use App\Rules\V1\AppointmentNotOverlapping;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreAppointmentRequest extends FormRequest
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
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', new DoctorExists()],
            'start_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:' . Carbon::now()->toDateString()],
            'end_time'   => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:start_time',
                new AppointmentNotOverlapping(
                    $this->doctor_id,
                    $this->start_time,
                    $this->end_time
                )
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
