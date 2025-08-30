<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QueueTurnStatus;

class QueueTurnStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Checked In',
            'Cancelled',
            'In Progress',
            'Completed',
        ];

        foreach ($statuses as $status) {
            QueueTurnStatus::create(['name' => $status]);
        }
    }
}

