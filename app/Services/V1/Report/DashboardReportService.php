<?php

namespace App\Services\V1\Report;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\PatientTreatment;
use App\Models\PatientAccount;

class DashboardReportService
{
    public function report(): array
    {
        // تحديد بداية ونهاية الشهر الحالي
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // 🧑‍⚕️ المرضى
        $newPatients = Patient::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $patientsWithAppointments = Appointment::whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->distinct('patient_id')
            ->count('patient_id');

        $returningPatients = max(0, $patientsWithAppointments - $newPatients);
        $totalPatients = $newPatients + $returningPatients;

        // 📅 المواعيد
        $totalAppointments = Appointment::whereBetween('start_time', [$startOfMonth, $endOfMonth])->count();

        $scheduledAppointments = Appointment::whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->where('appointment_status_id', AppointmentStatus::firstWhere('name', 'Scheduled')->id)
            ->count();

        $cancelledAppointments = Appointment::whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->where('appointment_status_id', AppointmentStatus::firstWhere('name', 'Cancelled')->id)
            ->count();

        $completedAppointments = Appointment::whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->where('appointment_status_id', AppointmentStatus::firstWhere('name', 'Completed')->id)
            ->count();

        // 🦷 المعالجات
        $completedTreatments = PatientTreatment::whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->where('finished', true)
            ->count();

        $inProgressTreatments = PatientTreatment::whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->where('finished', false)
            ->count();

        // 💰 الحسابات
        $accounts = PatientAccount::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        $accountsCreated = $accounts->count();
        $accountsWithDue = $accounts->where('balance', '<', 0)->count();
        $totalBalance = $accounts->sum('balance');

        // 📊 تجهيز البيانات
        $data = [
            'patients' => [
                'new' => $newPatients,
                'returning' => $returningPatients,
                'total' => $totalPatients,
            ],
            'appointments' => [
                'total' => $totalAppointments,
                'scheduled' => $scheduledAppointments,
                'cancelled' => $cancelledAppointments,
                'completed' => $completedAppointments,
            ],
            'treatments' => [
                'completed' => $completedTreatments,
                'in_progress' => $inProgressTreatments,
            ],
            'accounts' => [
                'created' => $accountsCreated,
                'with_due' => $accountsWithDue,
                'total_balance' => $totalBalance,
            ]
        ];

        return [
            'data' => $data,
            'message' => __('Dashboard statistics for current month'),
            'code' => 200,
        ];
    }
}
