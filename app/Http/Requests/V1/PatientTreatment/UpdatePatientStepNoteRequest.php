<?php

namespace App\Http\Requests\V1\PatientTreatment;

use App\Helpers\ApiResponse;
use App\Rules\V1\StepBelongsToTreatment;
use App\Rules\V1\SubStepBelongsToTreatment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePatientStepNoteRequest extends FormRequest
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
            'step_id' => [
                'prohibited_unless:substep_id,null',
                'required_without:substep_id',
                new StepBelongsToTreatment($patientTreatment),
            ],
            'substep_id' => [
                'prohibited_unless:step_id,null',
                'required_without:step_id',
                new SubStepBelongsToTreatment($patientTreatment),
            ],
            'note' => ['required', 'nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
