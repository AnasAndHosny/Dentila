<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Services\V1\EmployeeService;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Employee\StoreEmployeeRequest;
use App\Http\Requests\V1\Employee\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    use HandlesServiceResponse;

    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->handleService(
            fn() =>
            $this->employeeService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        return $this->handleService(
            fn() =>
            $this->employeeService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $this->handleService(
            fn() =>
            $this->employeeService->show($employee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        return $this->handleService(
            fn() =>
            $this->employeeService->update($request, $employee)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        return $this->handleService(
            fn() =>
            $this->employeeService->destroy($employee)
        );
    }
}
