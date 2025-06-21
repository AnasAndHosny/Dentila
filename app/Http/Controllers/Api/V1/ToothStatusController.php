<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\ToothStatusService;

class ToothStatusController extends Controller
{
    use HandlesServiceResponse;

    private ToothStatusService $toothStatusService;

    public function __construct(ToothStatusService $toothStatusService)
    {
        $this->toothStatusService = $toothStatusService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->toothStatusService->index()
        );
    }
}
