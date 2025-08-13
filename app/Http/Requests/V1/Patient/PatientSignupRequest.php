<?php

namespace App\Http\Requests\V1\Patient;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PatientSignupRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'numeric', 'digits:10', 'starts_with:09', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'in:male,female'],
            'job' => ['nullable', 'string'],
            'marital_status' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'social_history' => ['nullable', 'string'],
            'diseases_ids.*' => ['exists:diseases,id'],
            'intake_medications_ids.*' => ['exists:intake_medications,id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
