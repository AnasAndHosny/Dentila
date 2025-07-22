<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Models\Employee;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\DB;

class EmployeeRepository
{
    public function all()
    {
        return Employee::with('user')->get();
    }

    public function create($request)
    {
        return DB::transaction(function () use ($request) {
            $image = ImageHelper::store($request);

            $data = $request->validated();
            $data['image'] = $image;

            $user = User::updateOrCreate(
                ['phone_number' => $request['phone_number'],],
                $request->only(['name', 'phone_number', 'password'])
            );

            return $user->employee()->create($data);
        });
    }

    public function update($request, Employee $employee)
    {
        return DB::transaction(function () use ($request, $employee) {
            $image = ImageHelper::update($request, $employee);

            $data = $request->validated();
            $data['image'] = $image;

            $user = $employee->user;
            $user->fill($request->only(['name', 'phone_number', 'password']));
            $user->save();

            $employee->update($data);
            return $employee;
        });
    }

    public function delete(Employee $employee)
    {
        return DB::transaction(function () use ($employee) {
            ImageHelper::destroy($employee);

            $user = $employee->user;
            if (!$user->patient()->exists()) $user->delete();

            return $employee->delete();
        });
    }
}
