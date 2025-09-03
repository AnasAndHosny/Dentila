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

    protected function prepareForValidation()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // إذا عنده فقط دور patient
            if ($user->hasRole('patient') && $user->roles->count() === 1) {
                $this->merge([
                    'patient_id' => $user->patient->id ?? null,
                ]);
            }

            // إذا عنده patient + سكرتيرة (أو دور إضافي)
            if ($user->hasRole('patient') && $user->roles->count() > 1) {
                // إذا ما مرر patient_id ناخد تبعه
                if (!$this->filled('patient_id')) {
                    $this->merge([
                        'patient_id' => $user->patient->id ?? null,
                    ]);
                }
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'doctor_id' => ['required', new DoctorExists()],
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:' . now()->format('Y-m-d H:i:s'),
                'before:' . now()->addDays(15)->toDateString()
            ],
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

        $user = auth()->user();

        // إذا المستخدم مريض فقط => patient_id ما منخليه required (بيتعبى تلقائياً)
        if (!($user->hasRole('patient') && $user->roles->count() === 1)) {
            // إذا عنده سكرتيرة أو أي دور إضافي => patient_id اختياري، بس إذا انبعت لازم يكون valid
            $rules['patient_id'] = ['required', 'exists:patients,id'];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, ApiResponse::Validation([], $validator->errors()));
    }
}
