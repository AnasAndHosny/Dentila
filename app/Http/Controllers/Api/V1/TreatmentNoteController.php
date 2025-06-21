<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TreatmentNote;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\TreatmentNoteService;
use App\Http\Requests\V1\TreatmentNote\StoreTreatmentNoteRequest;
use App\Http\Requests\V1\TreatmentNote\UpdateTreatmentNoteRequest;

class TreatmentNoteController extends Controller
{
    use HandlesServiceResponse;

    private TreatmentNoteService $treatmentNoteService;

    public function __construct(TreatmentNoteService $treatmentNoteService)
    {
        $this->treatmentNoteService = $treatmentNoteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentNoteService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreatmentNoteRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentNoteService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentNote $treatmentNote): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentNoteService->show($treatmentNote)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentNoteRequest $request, TreatmentNote $treatmentNote): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentNoteService->update($request, $treatmentNote)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentNote $treatmentNote): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->treatmentNoteService->destroy($treatmentNote)
        );
    }
}
