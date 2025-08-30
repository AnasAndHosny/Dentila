<?php

namespace App\Repositories\V1;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use Illuminate\Support\Facades\DB;
use App\Queries\V1\AppointmentsQuery;

class AppointmentRepository
{

    public function all($request)
    {
        $appointments = new AppointmentsQuery(Appointment::query(), $request);
        return $appointments->orderBy('start_date')->get();
    }

    public function getByPatient($request, Patient $patient)
    {
        $appointments = new AppointmentsQuery($patient->appointments(), $request);
        return $appointments->orderBy('start_date')->get();
    }

    public function getByDoctor($request, Employee $employee)
    {
        $appointments = new AppointmentsQuery($employee->appointments(), $request);
        return $appointments->orderBy('start_date')->get();
    }
    public function create($request)
    {
        return Appointment::create([
            'patient_id' => $request['patient_id'],
            'employee_id' => $request['doctor_id'],
            'appointment_status_id' => AppointmentStatus::firstWhere('name', 'Scheduled')->id,
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
        ]);
    }

    public function update($request, Appointment $appointment)
{
    $data = $request->validated();

    if ($request->has('appointment_status')) {
        $newStatusName     = $request['appointment_status'];
        $currentStatusName = $appointment->appointmentStatus->name;

        // transitions array لتحديد الانتقالات المسموحة
        $transitions = [
            'Pending'     => ['Scheduled', 'Refused', 'Deleted'],
            'Scheduled'   => ['Checked In', 'Cancelled', 'No Show'],
            'Checked In'  => ['In Progress', 'Cancelled'],
            'In Progress' => ['Completed', 'Cancelled'],
            'Refused'     => [],
            'No Show'     => [],
            'Completed'   => [],
            'Cancelled'   => [],
            'Deleted'     => [],
        ];

        if (! in_array($newStatusName, $transitions[$currentStatusName] ?? [])) {
            throw new \Exception(
                trans('messages.appointment.invalid_transition', [
                    'from' => $currentStatusName,
                    'to'   => $newStatusName,
                ])
            );
        }

        $data['appointment_status_id'] = AppointmentStatus::firstWhere('name', $newStatusName)->id;

        // ---------------- sync مع الطابور ----------------
        $queueTurn = \App\Models\QueueTurn::where('appointment_id', $appointment->id)->first();

        if ($newStatusName === 'Checked In' && ! $queueTurn) {
            // أول دخول للطابور
            \App\Models\QueueTurn::create([
                'appointment_id'       => $appointment->id,
                'patient_id'           => $appointment->patient_id,
                'employee_id'          => $appointment->employee_id,
                'queue_turn_status_id' => \App\Models\QueueTurnStatus::firstWhere('name', 'Checked In')->id,
                'arrival_time'         => now(),
            ]);
        } elseif ($queueTurn) {
            // إذا الدور موجود، نشوف إذا الحالة الجديدة وحدة من حالات الدور
            $validQueueStatuses = ['Checked In', 'In Progress', 'Completed', 'Cancelled'];

            if (in_array($newStatusName, $validQueueStatuses)) {
                $queueTurn->update([
                    'queue_turn_status_id' => \App\Models\QueueTurnStatus::firstWhere('name', $newStatusName)->id,
                ]);
            }
            // ملاحظة: ما عاد في delete → حتى لو الموعد راح لـ Cancelled/Completed الدور بيضل بس مع حالة جديدة.
        }
    }

    $appointment->update($data);
    $appointment->load('appointmentStatus');

    return $appointment;
}




    public function shiftAppointments($request, Employee $employee)
    {
        $from = $request['from'];
        $to = $request['to'];
        $newStartDateTime = $request['target_time'];
        return DB::transaction(function () use ($employee, $from, $to, $newStartDateTime) {

            $appointments = $employee->appointments()
                ->whereBetween('start_time', [$from, $to])
                ->orderBy('start_time')
                ->get();

            if ($appointments->isEmpty()) {
                throw new \Exception(trans('messages.appointment.not_found'));
            }

            $firstOriginalStart = Carbon::parse($appointments->first()->start_time);
            $newStart = Carbon::parse($newStartDateTime);
            $diff = $firstOriginalStart->diffInSeconds($newStart, false);

            foreach ($appointments as $appointment) {
                $newStartTime = Carbon::parse($appointment->start_time)->addSeconds($diff);
                $newEndTime   = Carbon::parse($appointment->end_time)->addSeconds($diff);

                $conflict = $employee->appointments()
                    ->whereHas('appointmentStatus', function ($q) {
                        $q->where('name', 'Scheduled');
                    })
                    ->where('id', '!=', $appointment->id)
                    ->where(function ($q) use ($newStartTime, $newEndTime) {
                        $q->whereBetween('start_time', [$newStartTime, $newEndTime])
                            ->orWhereBetween('end_time', [$newStartTime, $newEndTime])
                            ->orWhere(function ($q2) use ($newStartTime, $newEndTime) {
                                $q2->where('start_time', '<', $newStartTime)
                                    ->where('end_time', '>', $newEndTime);
                            });
                    })
                    ->first();

                if ($conflict) {
                    throw new \Exception(
                        trans('messages.appointment.conflict', [
                            'date'  => Carbon::parse($conflict->start_time)->format('Y-m-d'),
                            'start' => Carbon::parse($conflict->start_time)->format('H:i'),
                            'end'   => Carbon::parse($conflict->end_time)->format('H:i'),
                        ])
                    );
                }

                $appointment->update([
                    'start_time' => $newStartTime,
                    'end_time'   => $newEndTime,
                ]);
            }

            return $appointments;
        });
    }
}
