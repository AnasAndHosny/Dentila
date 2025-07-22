<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'phone_number' => 'admin',
            'password' => 'admin'
        ]);

        $this->call([
            RolesPermissionsSeeder::class,
            MedicationSeeder::class,
            TreatmentNoteSeeder::class,
            ToothStatusSeeder::class,
            TreatmentPlanSeeder::class,
            DiseaseSeeder::class,
            IntakeMedicationSeeder::class,
            PatientSeeder::class,
            ToothSeeder::class,
            PatientTreatmentSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
