<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('fakultas')->insert([
        ['nama_fakultas' => 'Fakultas Ilmu Komputer'],
        ['nama_fakultas' => 'Fakultas Ekonomi dan Bisnis'],
        ['nama_fakultas' => 'Fakultas Teknik'],
        // Add more faculties here
        ['nama_fakultas' => 'Fakultas Kedokteran'],
        ['nama_fakultas' => 'Fakultas Hukum'],
    ]);
}
}
