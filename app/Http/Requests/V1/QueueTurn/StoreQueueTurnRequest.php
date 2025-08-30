<?php

namespace App\Http\Requests\V1\QueueTurn;

use Illuminate\Foundation\Http\FormRequest;

class StoreQueueTurnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // حط صلاحيات إذا بدك
    }

    public function rules(): array
    {
        return [
            'patient_id'           => 'required|exists:patients,id',
            'doctor_id'          => 'required|exists:employees,id',
            'appointment_id'       => 'nullable|exists:appointments,id',
        ];
    }
}
