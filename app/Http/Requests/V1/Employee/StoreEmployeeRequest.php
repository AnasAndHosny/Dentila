<?php

namespace App\Http\Requests\V1\Employee;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Rules\V1\UniqueEmployeePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreEmployeeRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                'starts_with:09',
                new UniqueEmployeePhone(),
            ],
            'password' => ['required', 'min:8'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'in:male,female'],
            'ssn' => ['nullable', 'numeric', 'digits:11'],
            'address' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
