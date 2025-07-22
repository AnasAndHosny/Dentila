<?php

namespace App\Http\Requests\V1\Employee;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use App\Rules\V1\UniqueEmployeePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeRequest extends FormRequest
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
            'image' => ['image', 'nullable', 'mimes:jpeg,png,bmp,jpg,gif,svg', 'max:256'],
            'name' => ['string'],
            'phone_number' => [
                'numeric',
                'digits:10',
                'starts_with:09',
                new UniqueEmployeePhone(),
            ],
            'password' => ['min:8'],
            'birthdate' => ['date_format:Y-m-d'],
            'gender' => ['in:male,female'],
            'ssn' => ['nullable', 'numeric', 'digits:11'],
            'address' => ['nullable', 'string'],
            'roles' => ['array', 'filled'],
            'roles.*' => ['required', 'string', 'in:doctor,receptionist'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
