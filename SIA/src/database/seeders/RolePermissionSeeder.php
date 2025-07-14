<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Opsional: Tambahkan permission
        $permissions = [
            'kelola alumni',
            'kelola event',
            'kelola loker',
            'kirim email',
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $role->givePermissionTo($permission);
        }
    }
}
