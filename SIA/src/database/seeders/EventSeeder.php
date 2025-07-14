<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'judul_event' => 'Reuni Akbar 2024',
                'deskripsi' => 'Acara reuni seluruh angkatan TI dan SI.',
                'tanggal_mulai' => '2024-08-15',
                'tanggal_selesai' => '2024-08-15',
                'lokasi' => 'Aula Utama UEU',
                'target_fakultas_id' => 1,
                'target_jurusan_id' => null,
                'target_angkatan' => null,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
