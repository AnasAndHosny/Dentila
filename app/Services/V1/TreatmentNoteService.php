<?php

namespace App\Services\V1;

use App\Http\Resources\V1\TreatmentNoteResource;
use App\Models\TreatmentNote;
use App\Repositories\V1\TreatmentNoteRepository;

class TreatmentNoteService
{
    protected $treatmentNoteRepo;

    public function __construct(TreatmentNoteRepository $treatmentNoteRepo)
    {
        $this->treatmentNoteRepo = $treatmentNoteRepo;
    }

    public function index(): array
    {
        $treatmentNotes = $this->treatmentNoteRepo->all();
        $treatmentNotes = TreatmentNoteResource::collection($treatmentNotes);
        $message = __('messages.index_success', ['class' => __('treatment notes')]);
        $code = 200;
        return ['data' => $treatmentNotes, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $treatmentNote = $this->treatmentNoteRepo->create($request);
        $treatmentNote = new TreatmentNoteResource($treatmentNote);

        $message = __('messages.store_success', ['class' => __('treatment note')]);
        $code = 201;
        return ['data' =>  $treatmentNote, 'message' => $message, 'code' => $code];
    }

    public function show(TreatmentNote $treatmentNote): array
    {
        $treatmentNote = new TreatmentNoteResource($treatmentNote);

        $message = __('messages.show_success', ['class' => __('treatment note')]);
        $code = 200;
        return ['data' => $treatmentNote, 'message' => $message, 'code' => $code];
    }

    public function update($request, TreatmentNote $treatmentNote): array
    {
        $treatmentNote = $this->treatmentNoteRepo->update($request, $treatmentNote);
        $treatmentNote = new TreatmentNoteResource($treatmentNote);

        $message = __('messages.update_success', ['class' => __('treatment note')]);
        $code = 200;
        return ['data' => $treatmentNote, 'message' => $message, 'code' => $code];
    }

    public function destroy(TreatmentNote $treatmentNote): array
    {
        $treatmentNote = $this->treatmentNoteRepo->delete($treatmentNote);

        $message = __('messages.destroy_success', ['class' => __('treatment note')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
