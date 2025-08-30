<?php

namespace App\Services\V1;

use App\Models\Category;
use App\Models\Appointment;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\AppointmentResource;
use App\Repositories\V1\AppointmentRepository;
use App\Http\Resources\V1\TreatmentPlanResource;
use App\Models\Employee;
use App\Models\Patient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentService
{
    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }

    public function index($request): array
    {
        $appointments = $this->appointmentRepo->all($request);
        $appointments = AppointmentResource::collection($appointments);
        $message = __('messages.index_success', ['class' => __('appointments')]);
        $code = 200;
        return ['data' => $appointments, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $appointment = $this->appointmentRepo->create($request);
        $appointment = new AppointmentResource($appointment);

        $message = __('messages.store_success', ['class' => __('appointment')]);
        $code = 201;
        return ['data' =>  $appointment, 'message' => $message, 'code' => $code];
    }

    public function update($request, Appointment $appointment): array
    {
        $appointment = $this->appointmentRepo->update($request, $appointment);
        $appointment = new AppointmentResource($appointment);

        $message = __('messages.update_success', ['class' => __('appointment')]);
        $code = 200;
        return ['data' => $appointment, 'message' => $message, 'code' => $code];
    }

    public function getByPatient($request, Patient $patient)
    {
        $appointments = $this->appointmentRepo->getByPatient($request, $patient);
        $appointments = AppointmentResource::collection($appointments);
        $message = __('messages.index_success', ['class' => __('appointments')]);
        $code = 200;
        return ['data' => $appointments, 'message' => $message, 'code' => $code];
    }

    public function getByDoctor($request, Employee $employee)
    {
        $appointments = $this->appointmentRepo->getByDoctor($request, $employee);
        $appointments = AppointmentResource::collection($appointments);
        $message = __('messages.index_success', ['class' => __('appointments')]);
        $code = 200;
        return ['data' => $appointments, 'message' => $message, 'code' => $code];
    }

    public function shiftAppointments($request, Employee $employee)
    {
        $appointments = $this->appointmentRepo->shiftAppointments($request, $employee);
        $appointments = AppointmentResource::collection($appointments);
        $message = __('messages.index_success', ['class' => __('appointments')]);
        $code = 200;
        return ['data' => $appointments, 'message' => $message, 'code' => $code];
    }
}
