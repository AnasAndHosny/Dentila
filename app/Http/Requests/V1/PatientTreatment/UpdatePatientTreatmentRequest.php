<?php

namespace App\Http\Requests\V1\PatientTreatment;

use App\Helpers\ApiResponse;
use App\Rules\V1\SubstepBelongsToStep;
use App\Rules\V1\StepBelongsToTreatment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePatientTreatmentRequest extends FormRequest
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

        $rules = [
            'name' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'main_complaint' => ['present', 'nullable', 'string'],
            'diagnoses' => ['present', 'nullable', 'string'],

            'teeth' => ['present', 'filled', 'array'],
            'teeth.*.number' => ['required', 'exists:teeth,number'],

            'steps' => ['present', 'nullable', 'array'],
        ];

        // Dynamically add rules for each step and its substeps
        foreach ($this->input('steps', []) as $i => $step) {
            $stepId = $step['id'] ?? null;

            $rules["steps.$i.id"] = [
                'required',
                'integer',
                new StepBelongsToTreatment($patientTreatment),
            ];
            $rules["steps.$i.name"] = ['required', 'string'];
            $rules["steps.$i.queue"] = ['required', 'integer'];
            $rules["steps.$i.finished"] = ['required', 'boolean'];
            $rules["steps.$i.note"] = ['present', 'nullable', 'string'];
            $rules["steps.$i.treatment_substeps"] = ['present', 'nullable', 'array'];

            foreach ($step['treatment_substeps'] ?? [] as $j => $substep) {
                $rules["steps.$i.treatment_substeps.$j.id"] = [
                    'required',
                    'integer',
                    new SubstepBelongsToStep($patientTreatment, $stepId),
                ];
                $rules["steps.$i.treatment_substeps.$j.name"] = ['required', 'string'];
                $rules["steps.$i.treatment_substeps.$j.queue"] = ['required', 'integer'];
                $rules["steps.$i.treatment_substeps.$j.finished"] = ['required', 'boolean'];
                $rules["steps.$i.treatment_substeps.$j.note"] = ['present', 'nullable', 'string'];
            }
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
