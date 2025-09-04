<?php

namespace App\Http\Requests\V1\DoctorWorkingHour;

use App\Helpers\ApiResponse;
use App\Rules\V1\DoctorExists;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreDoctorWorkingHourRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->hasRole('doctor') && $user->roles->count() === 1) {
                $this->merge([
                    'doctor_id' => $user->employee->id ?? null,
                ]);
            }

            if ($user->hasRole('doctor') && $user->roles->count() > 1) {
                if (!$this->filled('doctor_id')) {
                    $this->merge([
                        'doctor_id' => $user->employee->id ?? null,
                    ]);
                }
            }
        }
    }

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
        $rules = [
            'day_of_week' => [
                'required',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
                Rule::unique('doctor_working_hours')->where(function ($query) {
                    return $query->where('doctor_id', $this->doctor_id);
                }),
            ],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
        ];

        $user = auth()->user();

        if (!($user->hasRole('doctor') && $user->roles->count() === 1)) {
            $rules['doctor_id'] = ['required', new DoctorExists()];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
