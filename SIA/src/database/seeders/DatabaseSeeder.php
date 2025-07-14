<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            FakultasSeeder::class,
            JurusanSeeder::class,
            AlumniSeeder::class,
            EventSeeder::class,
            LokerSeeder::class,
            LokerDistribusiManualSeeder::class,
        ]);

        // Buat user admin (jika belum)
        $admin = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('super_admin');
    }
}