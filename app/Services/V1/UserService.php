<?php

namespace App\Services\V1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\EmployeeResource;
use App\Http\Resources\V1\PatientResource;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function login($request, String $role): array
    {
        $user = User::query()
            ->where('phone_number', $request['phone_number'])
            ->first();
        if (!is_null($user) && $user->hasRole($role)) {
            if (!Auth::attempt($request->only(['phone_number', 'password']))) {
                $user = null;
                $message = __('Phone number & password does not match with our record.');
                $code = 401;
            } else {
                $user['token'] = $user->createToken('token')->plainTextToken;
                $message = __(key: 'User logged in successfully.');
                $code = 200;
            }
        } else {
            $user = null;
            $message = __('Phone number & password does not match with our record.');
            $code = 401;
        }
        return ['data' => $user, 'message' => $message, 'code' => $code];
    }

    public function checkPhone($request): array
    {
        $phone = $request->validated();
        $message = __('Phone number is available.');
        $code = 200;
        return ['data' => $phone, 'message' => $message, 'code' => $code];
    }

    public function logout(): array
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $user->currentAccessToken()->delete();

            $message = __('User logged out successfully.');
            $code = 200;
        } else {
            $message = __('invalid token.');
            $code = 404;
        }
        return ['data' => $user, 'message' => $message, 'code' => $code];
    }

    public function ban($request, Model $model): array
    {
        $user = $model->user;
        if ($user->isNotBanned()) {
            $user->ban([
                'expired_at' => $request['until_value']
                    ? Carbon::now()->add($request['until_unit'], (int)$request['until_value'])
                    : null
            ]);
            $message = __('The account has been banned successfully.');
        } else {
            $message = __('The account has already been banned.');
        }


        if ($model instanceof Employee)
            $model = new EmployeeResource(Employee::find($model->id));

        if ($model instanceof Patient)
            $model = new PatientResource(Patient::find($model->id));

        $code = 200;
        return ['data' => $model, 'message' => $message, 'code' => $code];
    }

    public function unban(Model $model): array
    {
        $model->user->unban();

        if ($model instanceof Employee)
            $model = new EmployeeResource(Employee::find($model->id));

        if ($model instanceof Patient)
            $model = new PatientResource(Patient::find($model->id));

        $message = __('The account has been unbanned successfully.');
        $code = 200;
        return ['data' => $model, 'message' => $message, 'code' => $code];
    }

    public function employeeProfile(): array
    {
        $employee = auth()->user()->employee;

        $employee = new EmployeeResource($employee);

        $message = __('messages.show_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function patientProfile(): array
    {
        $patient = auth()->user()->patient;

        $patient = new PatientResource($patient);

        $message = __('messages.show_success', ['class' => __('patient')]);
        $code = 200;
        return ['data' => $patient, 'message' => $message, 'code' => $code];
    }
}
