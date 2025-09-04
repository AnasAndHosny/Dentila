<?php

namespace App\Http\Controllers\Api\V1\Report;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientAccountReportExport;
use App\Services\V1\Report\PatientAccountReportService;
use App\Http\Requests\V1\Reports\PatientAccountReportRequest;

class PatientAccountReportController extends Controller
{
    private PatientAccountReportService $reportService;

    public function __construct(PatientAccountReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(PatientAccountReportRequest $request): JsonResponse
    {
        try {
            $data = $this->reportService->report($request);
            return ApiResponse::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return ApiResponse::Error([], $th->getMessage());
        }
    }

    public function reportExcel(PatientAccountReportRequest $request)
    {
        $data = $this->reportService->report($request);
        return Excel::download(new PatientAccountReportExport($data['data']), 'patient_accounts_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function reportPdf(PatientAccountReportRequest $request)
    {
        $data = $this->reportService->report($request);
        return Excel::download(new PatientAccountReportExport($data['data'], 'pdf'), 'patient_accounts_report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
