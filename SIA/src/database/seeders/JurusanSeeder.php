<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('jurusan')->insert([
        ['fakultas_id' => 1, 'nama_jurusan' => 'Teknik Informatika'],
        ['fakultas_id' => 1, 'nama_jurusan' => 'Sistem Informasi'],
        ['fakultas_id' => 2, 'nama_jurusan' => 'Manajemen'],
        ['fakultas_id' => 3, 'nama_jurusan' => 'Teknik Elektro'],
        ['fakultas_id' => 4, 'nama_jurusan' => 'Pendidikan Dokter'],
        ['fakultas_id' => 5, 'nama_jurusan' => 'Ilmu Hukum'], 
        ['fakultas_id' => 1, 'nama_jurusan' => 'Desain Komunikasi Visual'],
    ]);
}
}
