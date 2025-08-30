<?php

namespace Database\Seeders;

use App\Models\AppointmentStatus;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointmentStatuses = [
            'Pending',
            'Deleted',
            'Refused',
            'Scheduled',
            'Cancelled',
            'No Show',
            'Checked In',
            'In Progress',
            'Completed'
        ];

        foreach ($appointmentStatuses as $appointmentStatus) {
            AppointmentStatus::create(['name' => $appointmentStatus]);
        }
    }
}
