<?php

namespace Database\Seeders;

use App\Models\Tooth;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ToothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teeth = [
            [
                'number' => 11,
                'name' => 'ثنية علوي يمين'
            ],
            [
                'number' => 12,
                'name' => 'رباعية علوي يمين'
            ],
            [
                'number' => 13,
                'name' => 'ناب علوي يمين'
            ],
            [
                'number' => 14,
                'name' => 'ضاحك أول علوي يمين'
            ],
            [
                'number' => 15,
                'name' => 'ضاحك ثاني علوي يمين'
            ],
            [
                'number' => 16,
                'name' => 'رحى أولى علوي يمين'
            ],
            [
                'number' => 17,
                'name' => 'رحى ثانية علوي يمين'
            ],
            [
                'number' => 18,
                'name' => 'رحى ثالثة علوي يمين'
            ],
            [
                'number' => 21,
                'name' => 'ثنية علوي يسار'
            ],
            [
                'number' => 22,
                'name' => 'رباعية علوي يسار'
            ],
            [
                'number' => 23,
                'name' => 'ناب علوي يسار'
            ],
            [
                'number' => 24,
                'name' => 'ضاحك أول علوي يسار'
            ],
            [
                'number' => 25,
                'name' => 'ضاحك ثاني علوي يسار'
            ],
            [
                'number' => 26,
                'name' => 'رحى أولى علوي يسار'
            ],
            [
                'number' => 27,
                'name' => 'رحى ثانية علوي يسار'
            ],
            [
                'number' => 28,
                'name' => 'رحى ثالثة علوي يسار'
            ],
            [
                'number' => 31,
                'name' => 'ثنية سفلي يسار'
            ],
            [
                'number' => 32,
                'name' => 'رباعية سفلي يسار'
            ],
            [
                'number' => 33,
                'name' => 'ناب سفلي يسار'
            ],
            [
                'number' => 34,
                'name' => 'ضاحك أول سفلي يسار'
            ],
            [
                'number' => 35,
                'name' => 'ضاحك ثاني سفلي يسار'
            ],
            [
                'number' => 36,
                'name' => 'رحى أولى سفلي يسار'
            ],
            [
                'number' => 37,
                'name' => 'رحى ثانية سفلي يسار'
            ],
            [
                'number' => 38,
                'name' => 'رحى ثالثة سفلي يسار'
            ],
            [
                'number' => 41,
                'name' => 'ثنية سفلي يمين'
            ],
            [
                'number' => 42,
                'name' => 'رباعية سفلي يمين'
            ],
            [
                'number' => 43,
                'name' => 'ناب سفلي يمين'
            ],
            [
                'number' => 44,
                'name' => 'ضاحك أول سفلي يمين'
            ],
            [
                'number' => 45,
                'name' => 'ضاحك ثاني سفلي يمين'
            ],
            [
                'number' => 46,
                'name' => 'رحى أولى سفلي يمين'
            ],
            [
                'number' => 47,
                'name' => 'رحى ثانية سفلي يمين'
            ],
            [
                'number' => 48,
                'name' => 'رحى ثالثة سفلي يمين'
            ],
        ];

        foreach ($teeth as $tooth) {
            Tooth::create($tooth);
        }
    }
}
