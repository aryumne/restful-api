<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\RoleHasPermission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create roles
        $superadminRole = Role::create(['role_name' => 'superadmin']);
        $supervisorRole = Role::create(['role_name' => 'supervisor']);
        $userRole = Role::create(['role_name' => 'user']);

        // create permissions
        $permission1 = Permission::create(['permission_name' => 'create-user']);
        $permission2 = Permission::create(['permission_name' => 'approve-epresence']);
        $permission3 = Permission::create(['permission_name' => 'create-epresence']);

        // create permissions for each of role
        RoleHasPermission::create([
            'role_id' => $superadminRole->id,
            'permission_id' => $permission1->id
        ]);
        RoleHasPermission::create([
            'role_id' => $superadminRole->id,
            'permission_id' => $permission2->id
        ]);
        RoleHasPermission::create([
            'role_id' => $superadminRole->id,
            'permission_id' => $permission3->id
        ]);

        RoleHasPermission::create([
            'role_id' => $supervisorRole->id,
            'permission_id' => $permission2->id
        ]);

        RoleHasPermission::create([
            'role_id' => $userRole->id,
            'permission_id' => $permission3->id
        ]);

        // create user
        User::create([
            'name'      => 'Superadmin',
            'email'     => 'spa@email.com',
            'password'  => Hash::make('password'),
            'npp'       => '99999',
            'role_id'   => $superadminRole->id
        ]);

        User::create([
            'name'      => 'Supervisor',
            'email'     => 'spv@email.com',
            'password'  => Hash::make('password'),
            'npp'       => '11111',
            'role_id'   => $supervisorRole->id
        ]);
        User::create([
            'name'      => 'Ananda Bayu',
            'email'     => 'bayu@email.com',
            'password'  => Hash::make('password'),
            'npp'       => '12345',
            'role_id'   => $userRole->id,
            'npp_supervisor' => '11111',
        ]);
    }
}
