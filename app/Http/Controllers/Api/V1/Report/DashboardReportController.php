<?php

namespace App\Http\Controllers\Api\V1\Report;

use Throwable;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\V1\Report\DashboardReportService;

class DashboardReportController extends Controller
{
    protected DashboardReportService $service;

    public function __construct(DashboardReportService $service)
    {
        $this->service = $service;
    }

    public function report()
    {
        try {
            $data = $this->service->report();
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return ApiResponse::Error([], $th->getMessage());
        }
    }
}
