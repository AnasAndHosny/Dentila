<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\PatientAccountService;
use App\Http\Requests\V1\PatientAccount\DepositRequest;
use App\Http\Requests\V1\PatientAccount\WithdrawRequest;

class PatientAccountController extends Controller
{
    use HandlesServiceResponse;

    private PatientAccountService $patientAccountService;

    public function __construct(PatientAccountService $patientAccountService)
    {
        $this->patientAccountService = $patientAccountService;
    }

    public function transactions(Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientAccountService->transactions($patient)
        );
    }

    public function deposit(DepositRequest $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientAccountService->deposit($request, $patient)
        );
    }

    public function withdraw(WithdrawRequest $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->patientAccountService->withdraw($request, $patient)
        );
    }
}
