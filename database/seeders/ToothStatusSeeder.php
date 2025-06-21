<?php

namespace Database\Seeders;

use App\Models\ToothStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToothStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toothStatuses = [
            'حشوة',
            'لبية',
            'تاج',
            'وتد',
            'زرعة',
            'قلع',
        ];

        foreach ($toothStatuses as $toothStatus) {
            ToothStatus::create([
                'name' => $toothStatus
            ]);
        }
    }
}
