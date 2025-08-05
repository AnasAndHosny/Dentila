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
        $managerPermissions = [];

        // Create permissions
        foreach ($managerPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $doctorPermissions = [];

        // Create permissions
        foreach ($doctorPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $patientPermissions = [];

        // Create permissions
        foreach ($patientPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $receptionistPermissions = [];

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
