<?php

return [
    'notFound'                      => 'Sorry, :class not found.',
    'notAuthorized'                 => 'Sorry, you do not have the required authorization to make this action.',
    'index_success'                 => ':Class list retrieved successfully.',
    'store_success'                 => ':Class created successfully.',
    'show_success'                  => ':Class retrieved successfully.',
    'update_success'                => ':Class updated successfully.',
    'destroy_success'               => ':Class deleted successfully.',
    'banned'                        => 'Sorry, your account has been banned.',
    'notVerified'                   => 'Please verify your phone number.',
    'appointment' => [
        'not_found'   => "No appointments found to shift.",
        'conflict'    => "This appointment conflicts with another appointment for the doctor (Date: :date, Time: :start - :end).",
        'invalid_transition' => 'Invalid transition from :from to :to.',
        'updated_successfully' => 'Appointment status updated successfully to :to.',
    ],

    'queue' => [
        'added'   => 'Patient has been added to the queue.',
        'removed' => 'Patient has been removed from the queue.',
        'updated' => 'Queue turn status updated to :status.',
    ],

    'queue_turn' => [
        'created_successfully' => 'Queue turn created successfully',
        'updated_successfully' => 'Queue turn updated successfully.',
        'invalid_transition'   => 'Cannot transition from :from to :to.',
        'list'                 => 'Queue turn list retrieved successfully.',
    ],

    // Appointment check-in
    'appointment' => [
        'invalid_code'         => 'Invalid check-in code.',
        'checked_in_success'   => 'You have been checked in successfully.',
        'patient_not_found'    => 'No patient profile linked to this user.',
        'no_scheduled'         => 'No scheduled appointment found.',
        'check_in_not_allowed' => 'Check-in is only allowed from 30 minutes before until 15 minutes after the appointment time (:time).',
    ],
];
