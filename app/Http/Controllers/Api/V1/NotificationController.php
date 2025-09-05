<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\V1\NotificationService;
use App\Http\Requests\V1\Notifications\SendNotificationRequest;

class NotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationService->index();
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }

    public function markAllAsRead(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationService->markAllAsRead();
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }

    public function send(SendNotificationRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationService->send($request);
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return ApiResponse::Error($data, $message);
        }
    }
}
