<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Traits\HandlesServiceResponse;
use App\Services\V1\AppointmentService;
use App\Http\Requests\V1\Appointment\GetAppointmentRequest;
use App\Http\Requests\V1\Appointment\StoreAppointmentRequest;
use App\Http\Requests\V1\Appointment\GetAvailableSlotsRequest;
use App\Http\Requests\V1\Appointment\ShiftAppointmentsRequest;
use App\Http\Requests\V1\Appointment\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    use HandlesServiceResponse;

    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetAppointmentRequest $request)
    {
        return $this->handleService(
            fn() => $this->appointmentService->index($request)
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->store($request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->update($request, $appointment)
        );
    }

    public function delete(Appointment $appointment)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->delete($appointment)
        );
    }

    public function getAppointmentsByPatient(GetAppointmentRequest $request, Patient $patient)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->getByPatient($request, $patient)
        );
    }

    public function getPatientAppointments()
    {
        $patient = auth()->user()->patient;
        $request = request()->merge([
            'filter' => [
                'date_range' => [
                    'from' => Carbon::now()->subWeeks(2)->toDateString(),
                    'to'   => Carbon::now()->addWeeks(2)->toDateString(),
                ]
            ]
        ]);

        return $this->handleService(
            fn() => $this->appointmentService->getByPatient($request, $patient)
        );
    }

    public function getAppointmentsByDoctor(GetAppointmentRequest $request, Employee $employee)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->getByDoctor($request, $employee)
        );
    }

    public function getDoctorAppointments(GetAppointmentRequest $request)
    {
        $employee = auth()->user()->employee;

        return $this->handleService(
            fn() =>
            $this->appointmentService->getByDoctor($request, $employee)
        );
    }

    public function shiftAppointments(ShiftAppointmentsRequest $request, Employee $employee)
    {
        return $this->handleService(
            fn() =>
            $this->appointmentService->shiftAppointments($request, $employee)
        );
    }

    public function getAvailableSlots(GetAvailableSlotsRequest $request)
    {
        return $this->handleService(
            fn() => $this->appointmentService->getAvailableSlots($request)
        );
    }
}
