<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('lokers')->insert([
            [
                'judul_loker' => 'Frontend Developer Internship',
                'deskripsi' => 'Magang 6 bulan di PT Techno.',
                'perusahaan' => 'PT Techno',
                'lokasi' => 'Jakarta',
                'tipe' => 'magang',
                'tanggal_berakhir' => '2025-08-01',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
