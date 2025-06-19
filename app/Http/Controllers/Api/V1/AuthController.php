<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Helpers\ApiResponse;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\SigninRequest;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(SigninRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->login($request);
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }

    public function logout(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->logout();
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }
}
