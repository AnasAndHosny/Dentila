<?php

namespace App\Services\V1;

use App\Models\Patient;
use App\Models\PatientTreatmentNote;
use App\Http\Resources\V1\PatientTreatmentNoteResource;
use App\Repositories\V1\PatientTreatmentNoteRepository;
use App\Http\Resources\V1\PatientTreatmentNoteCollection;

class PatientTreatmentNoteService
{
    protected $patientTreatmentNoteRepo;

    public function __construct(PatientTreatmentNoteRepository $patientTreatmentNoteRepo)
    {
        $this->patientTreatmentNoteRepo = $patientTreatmentNoteRepo;
    }

    public function index(Patient $patient): array
    {
        $treatmentNotes = $this->patientTreatmentNoteRepo->all($patient);
        $treatmentNotes = new PatientTreatmentNoteCollection($treatmentNotes->paginate());
        $message = __('messages.index_success', ['class' => __('treatment notes')]);
        $code = 200;
        return ['data' => $treatmentNotes, 'message' => $message, 'code' => $code];
    }

    public function myIndex(): array
    {
        $patient = auth()->user()->patient;
        $treatmentNotes = $this->patientTreatmentNoteRepo->all($patient);
        $treatmentNotes = PatientTreatmentNoteResource::collection($treatmentNotes->active()->get());
        $message = __('messages.index_success', ['class' => __('treatment notes')]);
        $code = 200;
        return ['data' => $treatmentNotes, 'message' => $message, 'code' => $code];
    }

    public function store($request, Patient $patient): array
    {
        $treatmentNote = $this->patientTreatmentNoteRepo->create($request, $patient);
        $treatmentNote = new PatientTreatmentNoteResource($treatmentNote);

        $message = __('messages.store_success', ['class' => __('treatment note')]);
        $code = 201;
        return ['data' =>  $treatmentNote, 'message' => $message, 'code' => $code];
    }

    public function show(PatientTreatmentNote $patientTreatmentNote): array
    {
        $patientTreatmentNote = new PatientTreatmentNoteResource($patientTreatmentNote);

        $message = __('messages.show_success', ['class' => __('treatment note')]);
        $code = 200;
        return ['data' => $patientTreatmentNote, 'message' => $message, 'code' => $code];
    }

    public function destroy(PatientTreatmentNote $patientTreatmentNote): array
    {
        $patientTreatmentNote = $this->patientTreatmentNoteRepo->delete($patientTreatmentNote);

        $message = __('messages.destroy_success', ['class' => __('treatment note')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
