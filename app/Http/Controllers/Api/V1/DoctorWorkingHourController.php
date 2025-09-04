<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\DoctorWorkingHour;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\DoctorWorkingHourService;
use App\Http\Requests\V1\DoctorWorkingHour\StoreDoctorWorkingHourRequest;
use App\Http\Requests\V1\DoctorWorkingHour\UpdateDoctorWorkingHourRequest;
use App\Models\Employee;

class DoctorWorkingHourController extends Controller
{
    use HandlesServiceResponse;

    protected $service;

    public function __construct(DoctorWorkingHourService $service)
    {
        $this->service = $service;
    }

    public function index(Employee $employee): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->service->index($employee)
        );
    }

    public function myIndex(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->service->index(auth()->user()->employee)
        );
    }

    public function store(StoreDoctorWorkingHourRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->service->store($request)
        );
    }

    public function update(UpdateDoctorWorkingHourRequest $request, DoctorWorkingHour $doctorWorkingHour): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->service->update($request, $doctorWorkingHour)
        );
    }

    public function destroy(DoctorWorkingHour $doctorWorkingHour): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->service->destroy($doctorWorkingHour)
        );
    }
}
