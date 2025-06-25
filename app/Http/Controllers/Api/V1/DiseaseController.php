<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Disease;
use Illuminate\Http\JsonResponse;
use App\Services\V1\DiseaseService;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Disease\StoreDiseaseRequest;
use App\Http\Requests\V1\Disease\UpdateDiseaseRequest;

class DiseaseController extends Controller
{
    use HandlesServiceResponse;

    private DiseaseService $diseaseService;

    public function __construct(DiseaseService $diseaseService)
    {
        $this->diseaseService = $diseaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->diseaseService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiseaseRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->diseaseService->store($request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiseaseRequest $request, Disease $disease): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->diseaseService->update($request, $disease)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disease $disease): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->diseaseService->destroy($disease)
        );
    }
}
