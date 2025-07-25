<?php

namespace App\Services\V1;

use App\Http\Resources\V1\PatientTreatmentCollection;
use App\Http\Resources\V1\PatientTreatmentResource;
use App\Models\Patient;
use App\Models\PatientTreatment;
use App\Repositories\V1\PatientTreatmentRepository;

class PatientTreatmentService
{
    protected $patientTreatmentRepository;

    public function __construct(PatientTreatmentRepository $patientTreatmentRepository)
    {
        $this->patientTreatmentRepository = $patientTreatmentRepository;
    }

    public function index($request, Patient $patient): array
    {
        $patientTreatments = $this->patientTreatmentRepository->all($request, $patient);
        $patientTreatments = new PatientTreatmentCollection( $patientTreatments);
        $message = __('messages.index_success', ['class' => __(key: 'patient treatments')]);
        $code = 200;
        return ['data' => $patientTreatments, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $patientTreatment = $this->patientTreatmentRepository->create($request);
        $patientTreatment = new PatientTreatmentResource($patientTreatment);

        $message = __('messages.store_success', ['class' => __('patient treatment')]);
        $code = 201;
        return ['data' =>  $patientTreatment, 'message' => $message, 'code' => $code];
    }

    public function show(PatientTreatment $patientTreatment): array
    {
        $patientTreatment = new PatientTreatmentResource($patientTreatment);

        $message = __('messages.show_success', ['class' => __('patient treatment')]);
        $code = 200;
        return ['data' => $patientTreatment, 'message' => $message, 'code' => $code];
    }

    public function update($request, PatientTreatment $patientTreatment): array
    {
        $patientTreatment = $this->patientTreatmentRepository->update($request, $patientTreatment);
        $patientTreatment = new PatientTreatmentResource($patientTreatment);

        $message = __('messages.update_success', ['class' => __('patient treatment')]);
        $code = 200;
        return ['data' => $patientTreatment, 'message' => $message, 'code' => $code];
    }

    public function updateNote($request, PatientTreatment $patientTreatment): array
    {
        $patientTreatment = $this->patientTreatmentRepository->updateNote($request, $patientTreatment);
        $patientTreatment = new PatientTreatmentResource($patientTreatment);

        $message = __('messages.update_success', ['class' => __('patient treatment')]);
        $code = 200;
        return ['data' => $patientTreatment, 'message' => $message, 'code' => $code];
    }

    public function updateCheck($request, PatientTreatment $patientTreatment): array
    {
        $patientTreatment = $this->patientTreatmentRepository->updateCheck($request, $patientTreatment);
        $patientTreatment = new PatientTreatmentResource($patientTreatment);

        $message = __('messages.update_success', ['class' => __('patient treatment')]);
        $code = 200;
        return ['data' => $patientTreatment, 'message' => $message, 'code' => $code];
    }

    public function destroy(PatientTreatment $patientTreatment): array
    {
        $this->patientTreatmentRepository->delete($patientTreatment);

        $message = __('messages.destroy_success', ['class' => __('patient treatment')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
