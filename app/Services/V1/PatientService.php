<?php

namespace App\Services\V1;

use App\Http\Resources\V1\PatientCollection;
use App\Http\Resources\V1\PatientResource;
use App\Models\Patient;
use App\Repositories\V1\PatientRepository;

class PatientService
{
    protected $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function index($request): array
    {
        $patients = $this->patientRepository->all($request);
        $patients = new PatientCollection($patients);
        $message = __('messages.index_success', ['class' => __('patients')]);
        $code = 200;
        return ['data' => $patients, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $patient = $this->patientRepository->create($request);
        $patient = new PatientResource($patient);

        $message = __('messages.store_success', ['class' => __('patient')]);
        $code = 201;
        return ['data' =>  $patient, 'message' => $message, 'code' => $code];
    }

    public function show(Patient $patient): array
    {
        $patient = new PatientResource($patient);

        $message = __('messages.show_success', ['class' => __('patient')]);
        $code = 200;
        return ['data' => $patient, 'message' => $message, 'code' => $code];
    }

    public function update($request, Patient $patient): array
    {
        $patient = $this->patientRepository->update($request, $patient);
        $patient = new PatientResource($patient);

        $message = __('messages.update_success', ['class' => __('patient')]);
        $code = 200;
        return ['data' => $patient, 'message' => $message, 'code' => $code];
    }

    public function destroy(Patient $patient): array
    {
        $patient = $this->patientRepository->delete($patient);

        $message = __('messages.destroy_success', ['class' => __('patient')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
