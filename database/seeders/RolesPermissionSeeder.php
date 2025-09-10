<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superadminRole = Role::create(['name' => 'superadmin']);
        $financeRole = Role::create(['name' => 'finance']);
        $admin = Role::create(['name' => 'admin']);
        $asman = Role::create(['name' => 'asman']);

        // // Create permissions
        // $editPermission = Permission::create(['name' => 'edit users']);
        // $viewPermission = Permission::create(['name' => 'view users']);

        // // Assign permissions to roles
        // $superadminRole->givePermissionTo($editPermission, $viewPermission);


        // Assign role to user
        $user = User::find(1); // Example user with ID 1
        $user->assignRole('admin');

           // Assign role to user
           $user = User::find(2); // Example user with ID 1
           $user->assignRole('superadmin');
    }
}
