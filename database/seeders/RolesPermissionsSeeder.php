<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $managerRole = Role::create(['name' => 'manager']);
        $doctorRole = Role::create(['name' => 'doctor']);
        $patientRole = Role::create(['name' => 'patient']);
        $receptionistRole = Role::create(['name' => 'receptionist']);

        // $managerRole = Role::findByName('manager');
        // $doctorRole = Role::findByName('doctor');
        // $patientRole = Role::findByName('patient');
        // $receptionistRole = Role::findByName('receptionist');


        // Define permissions
        $managerPermissions = [
            'user.ban', 'user.unban',
            'medication.index', 'medication.store', 'medication.show', 'medication.update', 'medication.destroy', 'medication.showPlans',
            'medicationPlan.index', 'medicationPlan.store', 'medicationPlan.show', 'medicationPlan.update', 'medicationPlan.destroy',
            'treatmentNote.index', 'treatmentNote.store', 'treatmentNote.show', 'treatmentNote.update', 'treatmentNote.destroy',
            'category.index', 'category.store', 'category.show', 'category.update', 'category.destroy', 'category.showPlans',
            'toothStatus.index',
            'treatmentPlan.index', 'treatmentPlan.store', 'treatmentPlan.show', 'treatmentPlan.update', 'treatmentPlan.destroy',
            'patient.index', 'patient.show', 'patient.destroy',
            'employee.index', 'employee.store', 'employee.show', 'employee.update', 'employee.destroy',
            'doctor.index', 'doctor.showReviews', 'treatmentEvaluation.show',
            'working-hours.index', 'working-hours.store', 'working-hours.update', 'working-hours.destroy',
        ];

        // Create permissions
        foreach ($managerPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $doctorPermissions = [
            'disease.store', 'disease.update', 'disease.destroy',
            'intakeMedication.store', 'intakeMedication.update', 'intakeMedication.destroy',
            'patient.index', 'patient.show', 'patient.update', 'patient.destroy',
            'treatmentPlan.index', 'treatmentPlan.show',
            'patientTreatment.index', 'patientTreatment.store', 'patientTreatment.show', 'patientTreatment.update', 'patientTreatment.destroy',
            'treatmentNote.index', 'treatmentNote.show',
            'medicationPlan.index', 'medicationPlan.show',
            'patientNote.index', 'patientNote.store', 'patientNote.show', 'patientNote.destroy',
            'patientMedication.index', 'patientMedication.store', 'patientMedication.show', 'patientMedication.destroy',
            'appointment.doctor.my',
            'working-hours.index.my', 'working-hours.store', 'working-hours.update.my', 'working-hours.destroy.my',
        ];

        // Create permissions
        foreach ($doctorPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $patientPermissions = [
            'account.index.my',
            'patientNote.index.my', 'patientNote.show.my',
            'patientMedication.index.my', 'patientMedication.show.my',
            'treatmentEvaluation.index.my', 'treatmentEvaluation.show.my', 'treatmentEvaluation.rate.my', 'treatmentEvaluation.dismes.my',
            'doctor.index',
            'appointment.patient.my', 'appointment.delete.my', 'appointment.store',
            'queue.checkIn',
        ];

        // Create permissions
        foreach ($patientPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $receptionistPermissions = [
            'patient.index', 'patient.store', 'patient.show', 'patient.destroy',
            'account.index', 'account.deposit', 'account.withdraw',
            'doctor.index',
            'appointment.index', 'appointment.store', 'appointment.update',
            'queue.index', 'queue.update', 'queue.store',
        ];

        // Create permissions
        foreach ($receptionistPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Assign permissions to roles
        $managerRole->syncPermissions($managerPermissions); // delete old permissions and keep those inside the $permissions
        $doctorRole->syncPermissions($doctorPermissions);
        $patientRole->syncPermissions($patientPermissions);
        $receptionistRole->syncPermissions($receptionistPermissions);

        $admin = User::Create([
            'name' => 'admin',
            'phone_number' => 'admin',
            'password' => 'admin'
        ]);
        $admin->markPhoneAsVerified();
        $admin->assignRole($managerRole);

        $admin = User::Create([
            'name' => 'zaid alshamaa',
            'phone_number' => '0936293119',
            'password' => 'superadmin'
        ]);
        $admin->markPhoneAsVerified();
        $admin->assignRole($managerRole);
    }
}
