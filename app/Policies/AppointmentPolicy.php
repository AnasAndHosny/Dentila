<?php

namespace App\Policies;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\Appointment;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can('appointment.store')) {
            if ($user->hasAnyRole('receptionist')) return Response::allow();

            $appointment = $user->patient->appointments()
                ->whereHas('appointmentStatus', function ($q) {
                    $q->where('name', 'Pending');
                    $q->orWhere('name', 'Scheduled');
                })
                ->first();

            if ($appointment) return Response::deny('You already have an appointment booked.');

            return Response::allow();
        }
        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): Response
    {
        if ($user->can('appointment.delete.my')) {
            if ($user->patient->id == $appointment->patient->id) {
                if ($appointment->canDelete()) {
                    return Response::allow();
                } else {
                    return Response::deny(__('You can\'t delete this appointment.'));
                }
            } else ExceptionHelper::throwModelNotFound($appointment);
        }
        return Response::deny();
    }
}
