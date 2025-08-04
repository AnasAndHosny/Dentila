<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Helpers\ApiResponse;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\BanRequest;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Auth\SigninRequest;
use App\Models\Employee;
use App\Models\Patient;

class AuthController extends Controller
{
    use HandlesServiceResponse;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(SigninRequest $request, String $role): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->login($request, $role);

            $user = $data['data'];

            if ($user && $user->isBanned()) {
                $user->tokens()->delete();
                $data = [
                    'error' => 'Banned'
                ];
                return ApiResponse::Error($data, __('messages.banned'), 403);
            }

            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }

    public function logout(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->logout()
        );
    }

    public function employeeBan(BanRequest $request, Employee $employee): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->ban($request, $employee)
        );
    }

    public function employeeUnban(Employee $employee): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->unban($employee)
        );
    }

    public function patientBan(BanRequest $request, Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->ban($request, $patient)
        );
    }

    public function patientUnban(Patient $patient): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->unban($patient)
        );
    }

    public function employeeProfile(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->employeeProfile()
        );
    }

    public function patientProfile(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->userService->patientProfile()
        );
    }
}
