<?php

namespace App\Http\Requests\V1\PatientTreatment;

use App\Helpers\ApiResponse;
use App\Rules\V1\StepBelongsToTreatment;
use App\Rules\V1\SubStepBelongsToTreatment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePatientStepCheckRequest extends FormRequest
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
        $patientTreatment = $this->route('patientTreatment');

        return [
            'steps' => ['array'],
            'steps.*' => [
                'required',
                new StepBelongsToTreatment($patientTreatment),
            ],
            'substeps' => ['array'],
            'substeps.*' => [
                'required',
                new SubStepBelongsToTreatment($patientTreatment),
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
