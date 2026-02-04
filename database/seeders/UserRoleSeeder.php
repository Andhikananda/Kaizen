<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['manager', 'pegawai'];
        $permissions = ['create role', 'edit role', 'delete role', 'view role'];

        // 1. Buat Permissions
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'api'
            ]);
        }

        // 2. Buat Role dan Assign Permission ke Manager
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'api'
            ]);

            if ($roleName === 'manager') {
                $role->givePermissionTo($permissions);
            }
        }

        // 3. Buat User Dummy & Assign Role
        foreach ($roles as $roleName) {
            $user = User::factory()->create([
                'name' => ucfirst($roleName) . ' User',
                'email' => $roleName . '@example.com',
                'password' => Hash::make('password123')
            ]);

            // Cukup gunakan satu baris ini saja
            $user->assignRole($roleName);
        }
    }
}
