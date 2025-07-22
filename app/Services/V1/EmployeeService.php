<?php

namespace App\Services\V1;

use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Repositories\V1\EmployeeRepository;

class EmployeeService
{
    protected $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function index(): array
    {
        $employees = $this->employeeRepo->all();
        $employees = EmployeeResource::collection($employees);
        $message = __('messages.index_success', ['class' => __('employees')]);
        $code = 200;
        return ['data' => $employees, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $employee = $this->employeeRepo->create($request);
        $employee = new EmployeeResource($employee);

        $message = __('messages.store_success', ['class' => __('employee')]);
        $code = 201;
        return ['data' =>  $employee, 'message' => $message, 'code' => $code];
    }

    public function show(Employee $employee): array
    {
        $employee = new EmployeeResource($employee);

        $message = __('messages.show_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function update($request, Employee $employee): array
    {
        $employee = $this->employeeRepo->update($request, $employee);
        $employee = new EmployeeResource($employee);

        $message = __('messages.update_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function destroy(Employee $employee): array
    {
        $employee = $this->employeeRepo->delete($employee);

        $message = __('messages.destroy_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
