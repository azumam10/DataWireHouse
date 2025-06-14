<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        Fakultas::create([
            'kode_fakultas' => 'FTI',
            'nama_fakultas' => 'Fakultas Teknologi Informasi',
            'prodi' => 'Informatika',
            'jenjang' => 'S1',
            'deskripsi' => 'Fakultas yang fokus pada teknologi dan informatika',
        ]);
    }
}
