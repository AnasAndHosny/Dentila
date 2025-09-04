<?php

namespace App\Services\V1\Report;

use Carbon\Carbon;
use App\Models\PatientAccount;

class PatientAccountReportService
{
    public function report($request): array
    {
        $startDate = $request->has('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : PatientAccount::orderBy('created_at')->first()?->created_at ?? now()->startOfDay();

        $endDate = $request->has('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $frequency = $request->input('frequency', 'monthly');

        $report = [];
        $total = [
            'from' => $startDate->toDateString(),
            'to' => $endDate->toDateString(),
            'accounts' => 0,
            'with_due' => 0,
            'clear_balance' => 0,
            'total_balance' => 0,
            'avg_balance' => 0,
        ];

        while ($startDate->lt($endDate)) {
            $toDate = $this->calculateEndDate($startDate, $frequency, $endDate);

            $accounts = PatientAccount::whereBetween('created_at', [$startDate, $toDate])->get();

            $entry = [
                'from' => $startDate->toDateString(),
                'to' => $toDate->toDateString(),
                'accounts' => $accounts->count(),
                'with_due' => $accounts->where('balance', '<', 0)->count(),
                'clear_balance' => $accounts->where('balance', '>=', 0)->count(),
                'total_balance' => $accounts->sum('balance'),
                'avg_balance' => round($accounts->avg('balance'), 2),
            ];

            $total['accounts'] += $entry['accounts'];
            $total['with_due'] += $entry['with_due'];
            $total['clear_balance'] += $entry['clear_balance'];
            $total['total_balance'] += $entry['total_balance'];
            $total['avg_balance'] = round(($total['avg_balance'] + $entry['avg_balance']) / 2, 2);

            $report[] = $entry;

            $startDate = $this->calculateNextStartDate($startDate, $frequency);
        }

        $report[] = $total;

        return [
            'data' => $report,
            'message' => __('messages.show_success', ['class' => __('patient account report')]),
            'code' => 200
        ];
    }

    private function calculateEndDate(Carbon $startDate, string $frequency, Carbon $endDate): Carbon
    {
        $toDate = match($frequency) {
            'daily' => $startDate->copy()->endOfDay(),
            'weekly' => $startDate->copy()->endOfWeek(),
            'yearly' => $startDate->copy()->endOfYear(),
            default => $startDate->copy()->endOfMonth(),
        };
        return $toDate->gt($endDate) ? $endDate->copy() : $toDate;
    }

    private function calculateNextStartDate(Carbon $startDate, string $frequency): Carbon
    {
        return match($frequency) {
            'daily' => $startDate->copy()->addDay()->startOfDay(),
            'weekly' => $startDate->copy()->addWeek()->startOfWeek(),
            'yearly' => $startDate->copy()->addYear()->startOfYear(),
            default => $startDate->copy()->addMonth()->startOfMonth(),
        };
    }
}
