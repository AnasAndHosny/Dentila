<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\IntakeMedication;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\IntakeMedication\StoreIntakeMedicationRequest;
use App\Http\Requests\V1\IntakeMedication\UpdateIntakeMedicationRequest;
use App\Services\V1\IntakeMedicationService;

class IntakeMedicationController extends Controller
{
    use HandlesServiceResponse;

    private IntakeMedicationService $intakeMedicationService;

    public function __construct(IntakeMedicationService $intakeMedicationService)
    {
        $this->intakeMedicationService = $intakeMedicationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->intakeMedicationService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntakeMedicationRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->intakeMedicationService->store($request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntakeMedicationRequest $request, IntakeMedication $intakeMedication): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->intakeMedicationService->update($request, $intakeMedication)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IntakeMedication $intakeMedication): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->intakeMedicationService->destroy($intakeMedication)
        );
    }
}
