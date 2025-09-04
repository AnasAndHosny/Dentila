<?php

namespace App\Http\Controllers\Api\V1\Report;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Exports\PatientReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\V1\Report\PatientReportService;
use App\Http\Requests\V1\Reports\PatientReportRequest;

class PatientReportController extends Controller
{
    private PatientReportService $reportService;

    public function __construct(PatientReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(PatientReportRequest $request): JsonResponse
    {
        try {
            $data = $this->reportService->report($request);
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return ApiResponse::Error([], $th->getMessage());
        }
    }

    public function reportExcel(PatientReportRequest $request)
    {
        $data = $this->reportService->report($request);
        return Excel::download(new PatientReportExport($data['data']), 'patient_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function reportPdf(PatientReportRequest $request)
    {
        $data = $this->reportService->report($request);
        return Excel::download(new PatientReportExport($data['data'], 'pdf'), 'patient_report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
