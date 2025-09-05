<?php

namespace App\Repositories\V1;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\QueueTurn;
use App\Models\Appointment;
use App\Models\QueueTurnStatus;
use App\Models\AppointmentStatus;
use App\Models\DoctorWorkingHour;
use Illuminate\Support\Facades\DB;
use App\Queries\V1\AppointmentsQuery;

class AppointmentRepository
{

    public function all($request)
    {
        $appointments = new AppointmentsQuery(Appointment::query(), $request);
        return $appointments->orderBy('start_time')->get();
    }

    public function getByPatient($request, Patient $patient)
    {
        $appointments = new AppointmentsQuery($patient->appointments(), $request);
        return $appointments->orderByDesc('start_time')->get();
    }

    public function getByDoctor($request, Employee $employee)
    {
        $appointments = new AppointmentsQuery($employee->appointments(), $request);
        return $appointments->orderBy('start_time')->get();
    }
    public function create($request)
    {
        $status = 'Scheduled';
        if (auth()->user() && auth()->user()->patient && auth()->user()->patient->id == $request['patient_id']) {
            $status = 'Pending';
        }

        return Appointment::create([
            'patient_id' => $request['patient_id'],
            'employee_id' => $request['doctor_id'],
            'appointment_status_id' => AppointmentStatus::firstWhere('name', $status)->id,
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

    public function getAvailableSlots($request)
    {
        $doctorId = $request->doctor_id;
        $date     = Carbon::parse($request->date);
        $patientId = auth()->user()->patient->id;

        // نجيب دوام الدكتور حسب اليوم المطلوب
        $dayOfWeek = $date->format('l'); // Monday, Tuesday...
        $workingHour = DoctorWorkingHour::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$workingHour) {
            return []; // الدكتور ما عندو دوام بهاليوم
        }

        $workStart = $date->copy()->setTimeFromTimeString($workingHour->start_time);
        $workEnd   = $date->copy()->setTimeFromTimeString($workingHour->end_time);

        // المواعيد الحالية عند الدكتور
        $appointments = Appointment::where('employee_id', $doctorId)
            ->whereDate('start_time', $date)
            ->get();

        // آخر موعد للمريض
        $lastPatientAppointment = Appointment::where('patient_id', $patientId)
            ->orderBy('start_time', 'desc')
            ->first();

        $interval = 30; // دقائق افتراضية
        if ($lastPatientAppointment && $lastPatientAppointment->appointmentStatus->name === 'Cancelled') {
            $diff = Carbon::parse($lastPatientAppointment->end_time)
                ->diffInMinutes(Carbon::parse($lastPatientAppointment->start_time));
            if ($diff > 30) {
                $interval = $diff;
            }
        }

        $slots = [];
        $cursor = $workStart->copy();

        while ($cursor->lt($workEnd)) {
            $slotStart = $cursor->copy();
            $slotEnd   = $cursor->copy()->addMinutes($interval);

            // تحقق من التعارض مع مواعيد الدكتور
            $conflict = $appointments->first(function ($app) use ($slotStart, $slotEnd) {
                return !(
                    Carbon::parse($app->end_time)->lte($slotStart) ||
                    Carbon::parse($app->start_time)->gte($slotEnd)
                );
            });

            if (!$conflict && $slotEnd->lte($workEnd)) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time'   => $slotEnd->format('H:i'),
                ];
            }

            $cursor->addMinutes($interval);
        }

        return $slots;
    }

    public function delete(Appointment $appointment)
    {
        if ($appointment->appointmentStatus->name == 'Pending') {
            $appointment->update(['appointment_status_id' => AppointmentStatus::firstWhere('name', 'Deleted')->id]);
        }
        if ($appointment->appointmentStatus->name == 'Scheduled') {
            $appointment->update(['appointment_status_id' => AppointmentStatus::firstWhere('name', 'Cancelled')->id]);
        }

        return $appointment->refresh();
    }

    public function checkInWithCode(string $code, $user)
    {
        // تحقق من الكود العام
        $isValid = \App\Models\CheckInCode::where('code', $code)->where('is_active', true)->exists();
        if (! $isValid) {
            throw new \Exception(__('messages.appointment.invalid_code'));
        }

        // نجيب المريض من المستخدم
        $patient = $user->patient;
        if (! $patient) {
            throw new \Exception(__('messages.appointment.patient_not_found'));
        }

        // نجيب موعده القادم
        $appointment = Appointment::where('patient_id', $patient->id)
            ->whereHas('appointmentStatus', fn($q) => $q->where('name', 'Scheduled'))
            ->orderBy('start_time')
            ->first();

        if (! $appointment) {
            throw new \Exception(__('messages.appointment.no_scheduled'));
        }

        // تحقق من الوقت
        $now       = now();
        $startTime = Carbon::parse($appointment->start_time);
        $earliest  = $startTime->copy()->subMinutes(30);
        $latest    = $startTime->copy()->addMinutes(15);

        if (! $now->between($earliest, $latest)) {
            throw new \Exception(__('messages.appointment.check_in_not_allowed', [
                'time' => $appointment->start_time
            ]));
        }

        // تحديث الحالة
        $appointment->update([
            'appointment_status_id' => AppointmentStatus::firstWhere('name', 'Checked In')->id,
        ]);

        // إدخال في الطابور إذا لسا ما موجود
        if (! $appointment->queueTurn) {
            QueueTurn::create([
                'appointment_id'       => $appointment->id,
                'patient_id'           => $appointment->patient_id,
                'employee_id'          => $appointment->employee_id,
                'queue_turn_status_id' => QueueTurnStatus::firstWhere('name', 'Checked In')->id,
                'arrival_time'         => now(),
            ]);
        }

        return $appointment->fresh('appointmentStatus');
    }
}
