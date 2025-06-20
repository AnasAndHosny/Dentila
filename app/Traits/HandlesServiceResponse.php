<?php

namespace App\Traits;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

trait HandlesServiceResponse
{
    public function handleService(callable $callback): JsonResponse
    {
        $data = [];
        try {
            $data = $callback();
            return ApiResponse::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return ApiResponse::error($data, $th->getMessage());
        }
    }
}
