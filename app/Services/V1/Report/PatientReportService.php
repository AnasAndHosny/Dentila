<?php

namespace App\Services\V1\Report;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\PatientTreatment;
use App\Models\AppointmentStatus;
use App\Models\TreatmentEvaluation;

class PatientReportService
{
    public function report($request): array
    {
        $startDate = $request->has('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Patient::orderBy('created_at')->first()?->created_at ?? now()->startOfDay();

        $endDate = $request->has('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $frequency = $request->input('frequency', 'monthly');

        $report = [];

        $total = [
            'from' => $startDate->toDateString(),
            'to' => $endDate->toDateString(),
            'new_patients' => 0,
            'returning_patients' => 0,
            'total_visits' => 0,
            'scheduled_appointments' => 0,
            'canceled_appointments' => 0,
            'completed_treatments' => 0,
            'inprogress_treatments' => 0,
            'avg_patient_rating' => 0,
            'avg_visits_per_patient' => 0,
        ];

        // لحساب المتوسط الكلي للتقييمات
        $ratingSum = 0;
        $ratingCount = 0;

        while ($startDate->lt($endDate)) {
            $toDate = $this->calculateEndDate($startDate, $frequency, $endDate);

            // المرضى الجدد
            $newPatients = Patient::whereBetween('created_at', [$startDate, $toDate])->count();

            // المرضى اللي عندهم مواعيد
            $patientsWithAppointments = Appointment::whereBetween('start_time', [$startDate, $toDate])
                ->distinct('patient_id')
                ->count('patient_id');

            // المرضى العائدين
            $returningPatients = max(0, $patientsWithAppointments - $newPatients);

            // عدد المواعيد
            $totalVisits = Appointment::whereBetween('start_time', [$startDate, $toDate])->count();

            // المواعيد المحجوزة
            $scheduledAppointments = Appointment::whereBetween('start_time', [$startDate, $toDate])
                ->where('appointment_status_id', AppointmentStatus::firstWhere('name', 'Scheduled')->id)
                ->count();

            // المواعيد الملغاة
            $canceledAppointments = Appointment::whereBetween('start_time', [$startDate, $toDate])
                ->where('appointment_status_id', AppointmentStatus::firstWhere('name', 'Cancelled')->id)
                ->count();

            // المعالجات المكتملة
            $completedTreatments = PatientTreatment::whereBetween('updated_at', [$startDate, $toDate])
                ->where('finished', true)
                ->count();

            // المعالجات قيد التنفيذ
            $inprogressTreatments = PatientTreatment::whereBetween('updated_at', [$startDate, $toDate])
                ->where('finished', false)
                ->count();

            // التقييم من جدول TreatmentEvaluation
            $avgRating = TreatmentEvaluation::whereBetween('created_at', [$startDate, $toDate])
                ->whereNotNull('rating')
                ->avg('rating') ?? 0;

            $ratingsCountPeriod = TreatmentEvaluation::whereBetween('created_at', [$startDate, $toDate])
                ->whereNotNull('rating')
                ->count();

            $ratingSum += $avgRating * $ratingsCountPeriod;
            $ratingCount += $ratingsCountPeriod;

            // متوسط عدد الزيارات لكل مريض
            $patientsCount = $newPatients + $returningPatients;
            $avgVisitsPerPatient = $patientsCount > 0 ? round($totalVisits / $patientsCount, 2) : 0;

            $entry = [
                'from' => $startDate->toDateString(),
                'to' => $toDate->toDateString(),
                'new_patients' => $newPatients,
                'returning_patients' => $returningPatients,
                'total_visits' => $totalVisits,
                'scheduled_appointments' => $scheduledAppointments,
                'canceled_appointments' => $canceledAppointments,
                'completed_treatments' => $completedTreatments,
                'inprogress_treatments' => $inprogressTreatments,
                'avg_patient_rating' => round($avgRating, 2),
                'avg_visits_per_patient' => $avgVisitsPerPatient,
            ];

            // تحديث المجاميع
            foreach ($total as $key => $value) {
                if (in_array($key, ['from', 'to', 'avg_patient_rating'])) {
                    continue;
                }
                $total[$key] += $entry[$key];
            }

            $report[] = $entry;
            $startDate = $this->calculateNextStartDate($startDate, $frequency);
        }

        // تحديث المتوسط الكلي للتقييمات
        $total['avg_patient_rating'] = $ratingCount > 0 ? round($ratingSum / $ratingCount, 2) : 0;

        // تحديث المدى الكلي
        $total['from'] = $request->has('start_date') ? $request->start_date : $report[0]['from'];
        $total['to'] = $request->has('end_date') ? $request->end_date : now()->toDateString();

        $report[] = $total;

        return [
            'data' => $report,
            'message' => __('messages.show_success', ['class' => __('patient report')]),
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
