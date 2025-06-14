<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organisasi;
use App\Models\Alumni;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        $alumni = Alumni::first();

        Organisasi::create([
            'jenis_organisasi' => 'Himpunan Mahasiswa',
            'alumni_id' => $alumni->id,
            'kegiatan' => 'Pengabdian masyarakat',
            'periode_jabatan' => '2019-2020',
            'status_aktif' => false,
        ]);
    }
}
