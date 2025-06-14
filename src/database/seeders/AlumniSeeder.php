<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\Fakultas;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $fakultas = Fakultas::first();

        Alumni::create([
            'nama' => 'Ahmad Fadli',
            'nim' => '1234567890',
            'fakultas_id' => $fakultas->id,
            'tahun_lulus' => 2022,
            'pekerjaan' => 'Software Engineer',
            'email' => 'ahmad@example.com',
            'no_telepon' => '08123456789',
            'alamat' => 'Jl. Teknologi No. 1',
            'status_pekerjaan' => 'Bekerja',
        ]);
    }
}
