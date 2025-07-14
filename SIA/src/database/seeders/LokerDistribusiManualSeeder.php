<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokerDistribusiManualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('loker_distribusi_manual')->insert([
            [
                'alumni_id' => 1,
                'loker_id' => 1,
                'status_kirim' => 'sukses',
                'waktu_kirim' => now(),
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
