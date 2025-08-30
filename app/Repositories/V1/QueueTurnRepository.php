<?php

namespace App\Repositories\V1;

use App\Models\QueueTurn;
use App\Models\QueueTurnStatus;
use App\Models\Appointment;

class QueueTurnRepository
{
    public function store(array $data): QueueTurn
    {
        return QueueTurn::create([
            'appointment_id'       => $data['appointment_id'] ?? null,
            'patient_id'           => $data['patient_id'],
            'employee_id'          => $data['doctor_id'],
            'queue_turn_status_id' => QueueTurnStatus::firstWhere('name', 'Checked In')->id,
            'arrival_time'         => now(),
        ]);
    }

    public function update($request, QueueTurn $queueTurn): QueueTurn
    {
        $data = $request->validated();
        $newStatusName     = $data['queue_turn_status'];
        $currentStatusName = $queueTurn->status->name;

        $transitions = [
            'Checked In'  => ['In Progress', 'Cancelled'],
            'In Progress' => ['Completed', 'Cancelled'],
            'Completed'   => [],
            'Cancelled'   => [],
        ];

        if (! in_array($newStatusName, $transitions[$currentStatusName] ?? [])) {
            throw new \Exception(
                trans('messages.queue_turn.invalid_transition', [
                    'from' => $currentStatusName,
                    'to'   => $newStatusName,
                ])
            );
        }

        $queueTurn->update([
            'queue_turn_status_id' => QueueTurnStatus::firstWhere('name', $newStatusName)->id,
        ]);

        if ($queueTurn->appointment) {
            $validAppointmentStatuses = ['Checked In', 'In Progress', 'Completed', 'Cancelled'];

            if (in_array($newStatusName, $validAppointmentStatuses)) {
                $queueTurn->appointment->update([
                    'appointment_status_id' => \App\Models\AppointmentStatus::firstWhere('name', $newStatusName)->id,
                ]);
            }
        }

        $queueTurn->load('status');

        return $queueTurn;
    }

    public function getQueueTurns()
    {
        return QueueTurn::with(['status', 'appointment', 'appointment.appointmentStatus', 'patient', 'doctor'])
            ->whereHas('status', function ($q) {
                $q->whereIn('name', ['Checked In']);
            })
            ->get()
            ->map(function ($turn) {
                $appointmentTime = $turn->appointment?->start_time;
                $checkInTime     = $turn->arrival_time;

                $effectiveTime = $appointmentTime
                    ? max(strtotime($appointmentTime), strtotime($checkInTime))
                    : strtotime($checkInTime);

                $turn->effective_time = $effectiveTime;
                return $turn;
            })
            ->sortBy('effective_time')
            ->values();
    }

    public function getHistory()
{
    return QueueTurn::with(['patient', 'doctor', 'status', 'appointment'])
        ->whereHas('status', function ($q) {
            $q->whereIn('name', ['Completed', 'Cancelled']);
        })
        ->orderByDesc('updated_at') // الأحدث أولاً
        ->get();
}

}
